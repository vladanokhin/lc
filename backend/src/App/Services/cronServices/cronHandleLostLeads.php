<?php

namespace src\App\Services\cronServices;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use src\App\Container\AppContainer;
use src\App\Services\ApiService\ApiService;
use src\App\Services\DatabaseService\Connection;
use src\App\Services\DatabaseService\DataBaseManager;
use src\App\Services\FileJobService\FileJobManager;
use src\App\Services\ScheduledLeads\ScheduledLeadsManager;

final class cronHandleLostLeads
{
    /**
     * @var DataBaseManager
     */
    private $db;

    /**
     * @var ScheduledLeadsManager
     */
    private $scheduledLeads;

    /**
     * @throws \Exception
     */
    public function __construct()
    {
        date_default_timezone_set('Europe/Moscow');
        $this->db = new DataBaseManager(Connection::make(), AppContainer::get('table_for_leads'));
        $this->scheduledLeads = new ScheduledLeadsManager();
    }

    /**
     * @throws GuzzleException
     */
    public function launcher(): void
    {
        $this->requestPayloadFromTracker($this->getProblematicLeads());
    }

    private function getProblematicLeads(int $timeTo = 120, int $timeFrom = 520): ?array
    {
        $timeTo = date('Y-m-d H:i:s', time() - $timeTo);

        return $this->scheduledLeads->customQuery(
            "aff_network_name LIKE 'Without%' AND created_at <= '{$timeTo}'",
            '*'
        );
    }

    /**
     * @throws GuzzleException
     * @throws \Exception
     */
    private function requestPayloadFromTracker(array $leads = null): void
    {
        if (null === $leads) {
            print_r("\r\n\r\n\t\t\tEverything is fine. Problematic leads not found.\r\n");
            exit();
        }
        $client = new Client();

        foreach ($leads as $k => $lead) {

            $logDate = "insert-week-" . date('W-Y') . "-lost-leads";
            (new FileJobManager())->write($lead, "{$logDate}");

            $tracker = $this->db->getTracker($lead['t_id'])[0];
            $binomClient = ApiService::getBinomClientByApiVersion(
                $tracker['api_version'],
                $tracker['t_url'],
                $tracker['t_api_key']
            );
            $data = $binomClient->getLead($lead['click_id']);

            if (count($data) === 0) {
                $this->discardLead($lead['click_id'], 'lead-not-found');
                continue;
            }
            $data = $data['click'] ?? $data;
            $data['click_id'] = $lead['click_id'];
            $this->scheduledLeads->updateScheduledLead($data);
            $client->get(AppContainer::get('app_url') . "/refresh/{$lead['click_id']}");
        }
    }

    /**
     * @throws \Exception
     */
    private function discardLead(string $clickId = '', string $reason = 'unknown-reason'): void
    {
        $this->scheduledLeads->hardRemoveFromQueue($clickId);
        $this->db->update(['conversion_status' => "discard_{$reason}"], ['click_id' => $clickId]);
    }
}