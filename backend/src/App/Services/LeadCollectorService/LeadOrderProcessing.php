<?php

namespace src\App\Services\LeadCollectorService;

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;
use src\App\Container\AppContainer;
use src\App\Container\LeadCollectorContainer;
use src\App\Services\LeadCollectorRequestService\LeadCollectorRequestManager;
use src\App\Services\ScheduledLeads\ScheduledLeadsManager;

class LeadOrderProcessing
{
    /**
     * @var LeadDataWriter
     */
    private $writer;

    /**
     * @var LeadDataReader
     */
    private $reader;

    /**
     * @var LeadDataHandler
     */
    private $validator;

    /**
     * @var ScheduledLeadsManager
     */
    private $scheduler;

    /**
     * LeadOrderProcessing constructor.
     * @param LeadDataReader $reader
     * @param LeadDataWriter $writer
     * @param LeadDataHandler $validator
     */
    public function __construct(
        LeadDataReader $reader,
        LeadDataWriter $writer,
        LeadDataHandler $validator
    ) {
        $this->reader = $reader;
        $this->writer = $writer;
        $this->validator = $validator;
        $this->scheduler = new ScheduledLeadsManager();
    }

    /**
     * [DEV]
     * @param array $order
     * @return bool
     * @throws GuzzleException
     * @throws Exception
     */
    public function orderShortFormLead(array $order): bool
    {
        $lead = $this->validator->leadHandling($order);
        $this->commitLeadToDB($lead);
        $leadDataFromTracker = $this->grubLeadFromTrackerForOrder($lead['click_id'], (int)$lead['t_id']);
        $fullLeadData = $this->validator->leadHandling(array_merge($lead, $leadDataFromTracker));
        $this->writer->updateLeadInfo($fullLeadData, ['click_id' => $lead['click_id']]);
        $this->scheduler->addToScheduled($fullLeadData);

        return true;
    }

    /**
     * @param array $order
     * @param array $lead
     * @return bool|null
     */
    public function processExistingLead(array $order, array $lead): ?bool
    {
        $order = array_filter($order, function ($data, $key) {
            return ($key == 'click_id' || $key == 'phone' || $key == 'name') ? $data : null;
        }, ARRAY_FILTER_USE_BOTH);
        if (true === $this->compareIncomeLeadWithExisting($order, $lead)) {
            return false;   // Returns false IF this lead already existing with same data. No need to update.
        }
        $this->scheduler->updateScheduledLead($order);

        return ($this->writer->transferLeadToArchive($order)) ?
            $this
                ->writer
                ->updateLeadInfo($order, ['click_id' => $order['click_id']])
            : null;
    }

    /**
     * @param $lead
     * @return bool
     * @throws Exception
     */
    public function reorderLead($lead): bool
    {
        return (new LeadCollectorRequestManager())->sendLeadToPartner($lead);
    }

    /**
     * Commit lead in lead collector database.
     *
     * @param array $leadData
     * @return bool
     * @throws Exception
     */
    protected function commitLeadToDB(array &$leadData): bool
    {
        $leadData['unique_id'] = uniqid(); // adding lead unique id to data from request

        return $this->writer->moveToDatabase($leadData);
    }

    /**
     * Querying lead info from tracker by click_id
     * [DEV] ====>>> LOOK AT COMMENTED CODE!
     * @param string|null $clickId
     * @param int|null $trackerId
     * @return array
     * @throws GuzzleException
     */
    public function grubLeadFromTrackerForOrder(string $clickId = null, int $trackerId = null): ?array
    {
        if (null === $clickId || null === $trackerId) return null;
        $response = $this->getClickInfoByClickId($clickId, $trackerId);
        $data = json_decode($response->getBody()->getContents(), true);
//        if (!isset($data['click'])) {
//            return null;
//        }
        $this->fixStatusProblem($data);

        return (isset($data['click'])) ? $data['click'] : null;
//        return $data['click'];
    }

    /**
     * Request to tracker to get all lead info.
     *
     * @param string $click
     * @param int $trackerId
     * @return ResponseInterface
     * @throws GuzzleException
     */
    private function getClickInfoByClickId(string $click, int $trackerId)
    {
        $apiUrl = LeadCollectorContainer::get($trackerId);
        $link = sprintf("https://%s/arm.php?api_key=%s&action=clickinfo@get&clickid=%s",
            $apiUrl['t_url'],
            $apiUrl['t_api_key'],
            $click
        );
        return (new LeadCollectorRequestManager())->sendLeadToTracker($link);
    }

    /**
     * Binom bug:   status updates too long and as a result we have ruined conversion status.
     * Decision:    We query all info except conversion status;
     *
     * @param array $data
     */
    private function fixStatusProblem(array &$data)
    {
        if (array_key_exists('conversion_status', $data['click'])) {
            $data['click']['conversion_status'] = 'Collected';
        }
    }

    /**
     * @param array $incomeLead
     * @param array $existingLead
     * @return bool
     */
    private function compareIncomeLeadWithExisting(array $incomeLead, array $existingLead): bool
    {
        foreach ($incomeLead as $item => $value) {
            if (!array_key_exists($item, $existingLead) || $incomeLead[$item] != $existingLead[$item]) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param $request
     * @return bool
     * @throws Exception
     */
    public function orderLongFormLead($request): bool
    {
        return $this->commitLeadToDB($request);
    }
}