<?php

namespace src\App\Http\Controller;

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use src\App\Services\CallerService\ServiceManager;
use src\App\Services\LeadCollectorService\LeadCollectorManager;
use src\App\Services\LeadCollectorService\LeadResender;
use src\exceptions\LeadHandlingException;

class LeadCollectorApiController
{
  protected $service;
  /**
   * @var LeadCollectorManager
   */
  private $lcManager;

  public function __construct()
  {
    $this->service = new ServiceManager();
    $this->lcManager = new LeadCollectorManager();
  }

  /**
   * Empty function
   */
  public function index()
  {
    $_REQUEST = [];
    echo '<center><span style="font-size: 500px">&#128162;</span></center>';
  }

  /**
   * Returns Own lead.
   * Returns json
   * @param $clickId
   * @return false|string
   * @throws Exception
   */
  public function getLead($clickId): void
  {
    echo $this->service->getOwnLead($clickId['id']);
  }

  /**
   * Refreshing needed lead
   * @param $clickId
   * @return void
   * @throws GuzzleException
   */
  public function refreshLead($clickId): void
  {
    $this->service->refreshLead($clickId);
  }

  /**
   * Registering new lead
   * @return mixed
   * @throws Exception
   * @throws GuzzleException
   */
  public function registerLead(): bool
  {
    if (isset($_POST) && !empty($_POST)) {
      return ($this->service->orderLead($_POST));
    }
    throw new LeadHandlingException('Request is empty.');
  }

  /**
   * Receiving postback from partner
   */
  public function postback(): void
  {
    $this->lcManager->handlePostback($_REQUEST);
  }

  /**
   * [DEV] -> postback status scheme
   * @return void
   * @throws Exception
   */
  public function postbackTerraleads()
  {
    $this->lcManager->handleTerraleadsPostback($_REQUEST);
  }

  public function statusSchemePostback()
  {
    $this->lcManager->handlePostbackByScheme($_REQUEST);
  }

  /**
   * Delivering lead to partner
   *
   * @param $click
   * @return bool
   */
  public function sendLead($click): bool
  {
    return (new LeadResender())->sendToPartner($click);
  }

  /**
   * Hide unneeded lead. It WOULD NOT be removed from DB.
   * @param $uniqueId
   * @return bool
   * @throws Exception|GuzzleException
   */
  public function delete($uniqueId): bool
  {
    return $this->service->deleteLead($uniqueId['unique']);
  }

  /**
   * If lead is not delivered, or you need deliver it one more time
   * @param array $clickId
   * @return bool
   * @throws Exception
   */
  public function reorder(array $clickId): bool
  {
    return $this->service->reorderLead($clickId);
  }

  /**
   * Synchronizing data in lead collector and Binom tracker
   *
   * @param $clickId
   * @return void
   * @throws GuzzleException
   */
  public function refresh($clickId)
  {
    $this->service->refreshLeadData($clickId);
  }

  /**
   * @return void
   * @throws Exception
   */
  public function additionalData(): void
  {
    if (!isset($_POST['click_id'])) {
      echo 'not enough data';
      return;
    }
    $clickid = $_POST['click_id'];
    $lead = $this->lcManager->getLeadByClickId($clickid);
    $phone = $this->service->findPhoneInPostRequest($_POST, $lead);
    $email = $this->service->findEmailInPostRequest($_POST, $lead);
    $this->service->additionalDataFromThankYouPage($clickid, $phone, $email);
  }

  public function curlSender(): void
  {
    $this->service->initializeSendingLeads();
  }

  public function reorderEdited(): void
  {
    $this->service->reorderEdited([
      'click_id' => (isset($_GET['clickid'])) ? $_GET['clickid'] : null,
      'new_name' => (isset($_GET['new_name'])) ? $_GET['new_name'] : null,
      'new_phone' => (isset($_GET['new_phone'])) ? $_GET['new_phone'] : null,
    ]);
  }

  public function longFormLeads(): bool
  {
    if (!empty($_POST) && !empty($_POST['click_id'])) {
      return $this->service->longFormLeads($_POST);
    }
    throw new Exception('POST request missing.', 500);
  }

  public function lostLeads(): void
  {
    $this->service->handleLostLeads();
  }

  public function missedLeadsHandler()
  {
    $this->service->handleMissedLeads();
  }

  public function leadsDataFix()
  {
    $data = json_decode(file_get_contents('php://input'), true);

    header("Content-Type: application/json");
    echo json_encode($this->service->leadsDataFix($data));
    exit();
  }
}