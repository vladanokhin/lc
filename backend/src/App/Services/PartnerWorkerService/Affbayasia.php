<?php

namespace src\App\Services\PartnerWorkerService;

use src\App\Services\ApiResponseHandler\ResponseHandler;
use src\interfaces\PartnerInterface\PartnerInterface;

class Affbayasia implements PartnerInterface
{
  /**
   * @var mixed|null
   */
  private $token;
  /**
   * @var mixed|null
   */
  private $endpoint;

  public function __construct($apiKey = null, $endpoint = null)
  {
    $this->token = $apiKey;
    $this->endpoint = $endpoint;
  }

  /**
   * @param array $lead
   * @param string $product
   * @return bool
   */
  public function sendLead(array $lead, string $product): bool
  {
    $lead = json_encode($this->prepareDataForPartner($lead));
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $this->endpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $lead);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    $result = curl_exec($ch);
    $result = json_decode($result, true);

    return true;
//    $response = new ResponseHandler();
//    return $response->commitResponseToDatabase(
//      $result, $lead['click_id']
//    );
  }

  /**
   * @param array $lead
   * @return array
   */
  private function prepareDataForPartner(array $lead): array
  {
    $result = [];
    $result['phone2'] = (isset($lead['second_phone']) && null != $lead['second_phone']) ?
      $lead['second_phone'] : null;
    $result['token'] = $this->token;
    $result['first_name'] = $lead['name'];
    $result['phone'] = $lead['phone'];
    $result['product'] = $lead['product'];
    $result['click_id'] = $lead['click_id'];
    if (!empty($lead['data_3'])) {
      $decoded = json_decode($lead['data_3'], true);
      if (false !== $decoded) {
        if (!empty($decoded['gender'])) {
          $result['sex'] = ($decoded['gender'] == "w") ? "female" : "male";
        }
        if (!empty($decoded['age'])) {
          $today = date("Y-m-d");
          $diff = date_diff(date_create($decoded['age']), date_create($today));
          $result['age'] = $diff->format('%y');
        }
      }
    }
    return $result;
  }
}