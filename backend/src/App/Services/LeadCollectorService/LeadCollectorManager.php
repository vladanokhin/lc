<?php

namespace src\App\Services\LeadCollectorService;

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use src\App\Container\AppContainer;
use src\App\Container\LeadCollectorContainer;
use src\App\Services\DatabaseService\Connection;
use src\App\Services\DatabaseService\DataBaseManager;
use src\App\Services\FileJobService\FileJobManager;
use src\App\Services\ScheduledLeads\ScheduledLeadsManager;
use src\exceptions\StatusSchemeException;

class LeadCollectorManager
{
  /**
   * @var LeadProcesses
   */
  protected $process;
  /**
   * @var ScheduledLeadsManager
   */
  private $scheduledLeadsManager;

  /**
   * LeadCollectorManager constructor. Loading needed data from config/LeadCollector.php and filling container.
   */
  public function __construct()
  {
    $this->process = new LeadProcesses();
    $this->scheduledLeadsManager = new ScheduledLeadsManager();
    $source = $this->process->getPartnersData();
    foreach ($source as $k => $partner) {
      LeadCollectorContainer::fill($partner['partner_provider'], $partner);
    }
    $trackers = $this->process->getTrackers();
    foreach ($trackers as $k => $tracker) {
      LeadCollectorContainer::fill($tracker['t_id'], $tracker);
    }
  }

  /**
   * Returns array with only needed lead or returns null
   *
   * @param $clickId
   * @param array|null $rows
   * @return array|null
   * @throws Exception
   */
  public function getLeadByClickId($clickId, array $rows = null): ?array
  {
    return $this->process->getLeadByClickId($clickId, $rows);
  }

  /**
   * Returns array with needed lead or returns null
   *
   * @param string $unique
   * @param array|null $rows
   * @return array|null
   * @throws Exception
   */
  public function getLeadByUniqueId(string $unique, array $rows = null): ?array
  {
    return $this->process->getLeadByUniqueId($unique, $rows);
  }

  /**
   * Fix lead data and resend;
   *
   * @param array $request
   * @return array
   */
  public function leadsDataFix(array $request): array
  {
    if (empty($request)) {
      return ["status" => "error: empty request"];
    }
    $result = $this->process->prepareLeadsForDataFix($request);
    if (!isset($result['problem'])) {
      foreach ($result as $cid => $value) {
        if (false === $this->resendLeadToPartner($cid)) {
          return ["status" => "leads updated but not reordered"];
        }
      }
      return ["status" => "success"];
    }
    return ["status" => "partial success", "problem" => $result];
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
    return $this->process->updateLeadInfo($data, $clickId);
  }

  /**
   * Setting the value {is_deleted} in database to 1, means is_deleted = true.
   *
   * @param array $item
   * @return bool
   * @throws Exception
   * @throws GuzzleException
   */
  public function disableLead(array $item): bool
  {
    $lead = $this->getLeadByUniqueId($item['unique_id'], ['click_id', 'unique_id']);
    $this->scheduledLeadsManager->hardRemoveFromQueue($lead['click_id']);
    $this->handlePostback([
      'cnv_id' => $lead['click_id'],
      'cnv_status' => 'Removed',
    ]);

    return true;
  }

  /**
   * Writing lead data to CSV file
   *
   * @param array $data
   * @param string $filename
   * @return bool
   */
  public function writeLeadDataToFile(array $data, string $filename = 'reserve'): bool
  {
    return (new FileJobManager())->write($data, $filename);
  }

  /**
   * Querying leads from CSV file
   *
   * @param string $filename
   * @return array
   */
  public function getLeadDataFromFile(string $filename): array
  {
    return (new FileJobManager())->read($filename);
  }

  /**
   * Creating empty file or rewrite current data.
   * -----------------------------------------
   * | BE CAREFUL! YOU WILL LOSE ALL DATA    |
   * |        FROM CURRENT FILE!             |
   * -----------------------------------------
   *
   * @param array $data
   * @param string $filename
   * @return bool
   */
  public function rewriteFileWithLeads(array $data, string $filename): bool
  {
    return (new FileJobManager())->rewrite($data, $filename);
  }

  /**
   * Processing of new Lead
   *
   * @param array $request
   * @return bool
   * @throws GuzzleException
   */
  public function leadProcessing(array $request): bool
  {
    return $this->process->leadProcessing($request);
  }

  /**
   * @param string $click
   * @return void
   * @throws GuzzleException
   */
  public function refreshLeadData(string $click): void
  {
    $reader = new LeadDataReader();
    $this->process->syncLeadByLeadCollector($reader->getLeadByClickId($click));
  }

  /**
   * @param string $clickId
   * @return bool
   * @throws Exception
   */
  public function resendLeadToPartner(string $clickId): bool
  {
    $lead = $this->process->getLeadByClickId($clickId);
    if (null === $lead) {
      return false;
    }
    return $this->process->reorderLead($lead);
  }

  /**
   * Postback handler
   *
   * @param $data
   * @return void
   * @throws GuzzleException
   */
  public function handlePostback($data): void
  {
    $this->process->handlePostback($data);
  }

  /**
   * Postback handler
   *
   * @param $data
   * @return void
   * @throws Exception
   */
  public function handleTerraleadsPostback($data): void
  {
    if (isset($data['cnv_status'])) {
      $data['cnv_status'] = strtolower($data['cnv_status']);
    }
    try {
      $this->process->postbackStatusScheme($data);
    } catch (GuzzleException|StatusSchemeException $e) {
      echo $e->getMessage();
    }
  }

  public function handlePostbackByScheme($postback): void
  {
    if (!isset($postback['cnv_id']) || !isset($postback['cnv_status'])) {
      header("HTTP/1.1 404 Not Found");
      exit();
    }
    $postback['cnv_status'] = strtolower($postback['cnv_status']);
    $lead = $this->process->getLeadByClickId($postback['cnv_id']);
    if ($lead === null) {
      http_response_code(400);
      echo json_encode(['error' => 'lead not found']);
      return;
    }
    $connection = new DataBaseManager(
      (new Connection())->make(),
      AppContainer::get('income_status_frontend')
    );
    $partnersStatusScheme = new StatusScheme($connection);
    $statusScheme = $partnersStatusScheme->getStatusScheme(
      $lead['aff_network_name'],
      $postback['cnv_status']
    );
    $postback = $partnersStatusScheme->handlePostbackAccordingToStatusScheme($statusScheme, $postback, $lead);
    if ($statusScheme !== null && $postback !== null) {
      unset($postback['scheme']);
      $this->handlePostback($postback);
    }
  }

  /**
   * @param string $clickId
   * @param string $number
   * @param string $email
   * @return void
   */
  public function handleAdditionalData(string $clickId, string $number, string $email): void
  {
    $this->process->additionalDataFromThankYouPage($clickId, $number, $email);
  }

  /**
   * Leads with custom sender. LC Handler only commit lead to db without sending via scheduler.
   *
   * @param array $lead
   * @return bool
   * @throws GuzzleException
   */
  public function writeLeadToDatabase(array $lead): bool
  {
    $validator = new LeadDataHandler();
    $leadFromTracker = $this
      ->process
      ->orderProcess
      ->grubLeadFromTrackerForOrder($lead['click_id'], $lead['t_id']);
    $fullLeadData = $validator->leadHandling(array_merge($lead, $leadFromTracker));

    return $this->process->longFormLeadProcessing($fullLeadData);
  }
}