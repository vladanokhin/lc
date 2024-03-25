<?php

namespace src\App\Services\LeadCollectorService;

use Exception;
use src\App\Container\AppContainer;
use GuzzleHttp\Exception\GuzzleException;
use src\App\Services\DatabaseService\{Connection, DataBaseManager};
use src\App\Services\ApiService\ApiService;
use src\App\Services\LeadCollectorRequestService\LeadCollectorRequestManager;
use src\App\Services\ScheduledLeads\ScheduledLeadsManager;
use src\exceptions\StatusSchemeException;

class LeadProcesses
{
  /**
   * @var DataBaseManager
   */
  protected $query;

  /**
   * @var LeadDataHandler
   */
  private $leadValidator;

  /**
   * @var LeadOrderProcessing
   */
  public $orderProcess;

  /**
   * @var LeadDataReader
   */
  private $leadDataReader;

  /**
   * @var LeadDataWriter
   */
  private $leadDataWriter;

  /**
   * @var ScheduledLeadsManager
   */
  private $scheduled;
  /**
   * @var StatusScheme
   */
  private $statusScheme;
  /**
   * @var LeadCollectorRequestManager
   */
  private $requestManager;

  /**
   * LeadProcesses constructor.
   * @throws Exception
   */
  public function __construct()
  {
    $this->query = new DataBaseManager(Connection::make(), AppContainer::get('table_for_leads'));
    $this->leadValidator = new LeadDataHandler();
    $this->leadDataReader = new LeadDataReader($this->query);
    $this->leadDataWriter = new LeadDataWriter($this->query);
    $this->scheduled = new ScheduledLeadsManager();
    $this->requestManager = new LeadCollectorRequestManager();
    $this->orderProcess = new LeadOrderProcessing(
      $this->leadDataReader,
      $this->leadDataWriter,
      $this->leadValidator
    );
    $this->statusScheme = new StatusScheme($this->query);
  }

  /**
   * Commit short-form lead in lead collector, in tracker and send to partner.
   *
   * @param array $request
   * @return bool
   * @throws Exception
   * @throws GuzzleException
   */
  public function leadProcessing(array $request): bool
  {
    $this->leadValidator->validateUserData($request);
    $lead = $this->query->getBy(['click_id' => $request['click_id']]);
    if ($lead === null) {
      $request['conversion_status'] = 'Collected';
      $this->syncStatusByLeadCollector($request);

      return $this->orderProcess->orderShortFormLead($request);
    }
    return $this->orderProcess->processExistingLead($request, $lead);
  }

  /**
   * @throws Exception
   */
  public function longFormLeadProcessing($request): bool
  {
    return $this->orderProcess->orderLongFormLead($request);
  }

  /**
   * @param $lead
   * @return bool
   * @throws Exception
   */
  public function reorderLead($lead): bool
  {
    if ($lead['click_id'] === NULL) {
      return false;
    }
    $this->scheduled->removeReorderedFromScheduled($lead['click_id']);

    return $this->orderProcess->reorderLead($lead);
  }

  /**
   * Returns array with only needed lead or returns null
   *
   * @param $clickId
   * @param array|null $rows
   * @return array|null
   */
  public function getLeadByClickId($clickId, array $rows = null): ?array
  {
    return $this->leadDataReader->getLeadByClickId($clickId, $rows);
  }

  /**
   * Returns array with needed lead or returns null
   *
   * @param string $unique
   * @param array|null $rows
   * @return array
   * @throws Exception
   */
  public function getLeadByUniqueId(string $unique, array $rows = null): ?array
  {
    return $this->leadDataReader->getLeadByUniqueId($unique, $rows);
  }

  /**
   * Updating lead
   *
   * @param array $data
   * @param array $clickId
   * @return bool
   * @throws Exception
   */
  public function updateLeadInfo(array $data, array $clickId): bool
  {
    return $this->query->update($data, $clickId);
  }

  /**
   * Registration lead in tracker via request
   *
   * @param array $lead
   * @return void
   * @throws GuzzleException
   * @throws Exception
   */
  public function syncStatusByLeadCollector(array $lead): void
  {
      $tracker = $this->getResponsibleTracker($lead['t_id']);
      $binomClient = ApiService::getBinomClientByApiVersion(
          $tracker['api_version'],
          $tracker['t_url'],
          $tracker['t_api_key']
      );
      $data = [
          'cnv_id' => $lead['click_id'],
          'cnv_status' => $lead['conversion_status'],
      ];

      if (strtolower($lead['conversion_status']) ==='collected') {
          $data['cnv_status2'] = $lead['conversion_status'];
      }

      $binomClient->updateLead($data);
  }

  /**
   * @throws GuzzleException
   */
  public function syncLeadByLeadCollector($lead): void
  {
    $data = $this->orderProcess->grubLeadFromTrackerForOrder($lead['click_id'], $lead['t_id']);
    $data = $this->leadValidator->filterLeadBeforeUpdate($data);
    $data = array_merge($lead, $data);
    $this->syncStatusByLeadCollector($lead);
    unset($data['conversion_status']);
    $this->leadDataWriter->updateLeadInfo($data, ['click_id' => $data['click_id']]);
  }

  /**
   * Setting the value {is_deleted} in database to 1, means is_deleted = true.
   *
   * @param array $item
   * @return bool
   * @throws Exception
   */
  public function disableLead(array $item): bool
  {
    return $this->query->delete($item);
  }

  /**
   * If postback is valid we will send it to tracker
   *
   * @param $data
   * @return void
   * @throws GuzzleException
   */
  public function handlePostback($data): void
  {
    $postback = $this->clearEmptyValuesFromPostback($data);
    if ($this->leadValidator->validPostback($postback)) {
      $this->commitPostback($postback);
    }
  }

  private function clearEmptyValuesFromPostback(array $data): array
  {
    foreach ($data as $k => $v) {
      if ($v === null) {
        unset($data[$k]);
      }
    }
    return $data;
  }

  /**
   * @param $postback
   * @return void
   * @throws GuzzleException
   * @throws StatusSchemeException
   * @throws Exception
   */
  public function postbackStatusScheme($postback): void
  {
    $localLead = $this->query->getBy(['click_id' => $postback['cnv_id']]);
    if (null === $localLead) {
      throw new StatusSchemeException('Lead not found. Unable to handle postback');
    }
    $localLead['conversion_status'] = strtolower($localLead['conversion_status']);
    $localLead['postback'] = $postback;
    $localLead = $this->validateStatusScheme($localLead);
    $this->commitPostback($localLead);
  }

  /**
   * Returns Null IF something wrong ELSE postback data in array
   * @param array $lead
   * @return array|null
   * @throws StatusSchemeException
   */
  private function validateStatusScheme(array $lead): ?array
  {
    $postback = null;
    $postbackWeight = null;
    $currentStatusWeight = null;
    $statusScheme = $this->statusScheme->getStatusScheme();

    foreach ($statusScheme as $category => $payload) {
      $currentStatusWeight = (in_array($lead['conversion_status'], $payload['status'])) ?
        $payload['weight'] : 0;
      if (in_array($lead['postback']['cnv_status'], $payload['status'])) {
        $postback = $payload;
        $postbackWeight = $postback['weight'];
      }
    }
    if (isset($lead['postback']['payout'])) {
      if (false === $postback['payment']) {
        unset($lead['postback']['payout']);
      }
    }
    if ($postbackWeight == $currentStatusWeight) {
      return null;
    }
    if ($postbackWeight < $currentStatusWeight) {
      throw new StatusSchemeException('You can\'t downgrade status!');
    }

    return $lead['postback'];
  }

  /**
   * @param $data
   * @return void
   * @throws Exception
   */
  private function commitPostback($data): void
  {
      if ($data === null)
          return;

      $lead = $this->getLeadByClickId($data['cnv_id']);

      if(is_null($lead) && !isset($data['t_id']))
          return;

      $trackerId = $lead['t_id'] ?? null;
      $tracker = $this->getResponsibleTracker($trackerId ?? $data['t_id']);

      $binomClient = ApiService::getBinomClientByApiVersion(
          $tracker['api_version'],
          $tracker['t_url'],
          $tracker['t_api_key']
      );

      if (is_null($lead) || $lead['conversion_status'] === $data['cnv_status']) {
        $binomClient->updateLead($data);
        return;
      }

      $this->commitPostbackToHistory($data['cnv_id']);
      $this->updateLeadInfo(
          ['conversion_status' => $data['cnv_status']],
          ['click_id' => $data['cnv_id']]
      );

      $binomClient->updateLead($data);
  }

  /**
   * Because we have few trackers in our system we must identify tracker for each lead
   *
   * @param int $trackerId
   * @return mixed
   * @throws Exception
   */
  private function getResponsibleTracker(int $trackerId): ?array
  {
      $tracker = $this->query->getTracker($trackerId);
      return $tracker[0] ?? $tracker;
  }

  /**
   * @param string $clickId
   * @return void
   */
  public function commitPostbackToHistory(string $clickId): void
  {
    $this->transferLeadToArchive($clickId);
  }

  /**
   * @param string $clickId
   * @return void
   */
  private function transferLeadToArchive(string $clickId): void
  {
    $this->query->sendLeadToArchive($clickId);
  }

  /**
   * @param string $clickId
   * @param string $number
   * @param string $email
   * @return void
   */
  public function additionalDataFromThankYouPage(string $clickId, string $number, string $email): void
  {
    $lead = ['click_id' => $clickId, 'second_phone' => $number, 'user_email' => $email];
    $this->leadValidator->validateSecondPhone($lead);
    $this->leadValidator->validateEmail($lead);
    $this->scheduled->updateScheduledLead(
      $lead
    );

    $this->query->update(
      ['second_phone' => $number, 'user_email' => $email],
      ['click_id' => $clickId]
    );
  }

  /**
   * @param array $payload
   * @return bool
   */
  public function processingReorderedLead(array $payload): ?bool
  {
    $lead = $this->query->getBy(['click_id' => $payload['click_id']]);

    return (
      $lead['name'] == $payload['new_name'] &&
      $lead['phone'] == $payload['new_phone']
    ) ? null : $this->updatingReorderedLeadData($payload, $lead);
  }

  /**
   * @param array $payload
   * @param array $lead
   * @return bool
   */
  private function updatingReorderedLeadData(array $payload, array $lead = []): ?bool
  {
    $lead['name'] = $payload['new_name'];
    $lead['phone'] = $payload['new_phone'];
    $this->transferLeadToArchive($payload['click_id']);
    $this->scheduled->addToScheduled($lead);

    return $this->query->update($lead, ['click_id' => $payload['click_id']]);
  }

  /**
   * @return array|null
   */
  public function getPartnersData(): ?array
  {
    return $this->query->getPartnersData();
  }

  /**
   * @return array|null
   */
  public function getTrackers(): ?array
  {
    return $this->query->getTrackers();
  }

  public function prepareLeadsForDataFix(array $request): ?array
  {
    $result = [];
    $request = $request['payload'];
    $click_id_list = explode(',', $request['click-id-list']);
    unset($request['click-id-list']);

    foreach ($click_id_list as $_ => $clickId) {
      $_tmp = [];
      foreach ($request as $k => $v) {
        $_tmp[$k] = $v;
      }
      $preparedPayload[$clickId] = $_tmp;
    }
    foreach ($preparedPayload as $clickId => $statement) {
      $result[$clickId] = $this->updateLeadInfo($statement, ['click_id' => $clickId]);
    }
    if (!in_array(false, $result)) {
      return $result;
    }

    $problematicLeads = [];
    foreach ($result as $cid => $v) {
      if (true !== $v) {
        $problematicLeads['problem'][$v] = $cid;
      }
    }
    return $problematicLeads;
  }
}