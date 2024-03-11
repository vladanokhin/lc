<?php

namespace src\App\Services\PartnerWorkerService;

use src\App\Services\ApiResponseHandler\ResponseHandler;
use src\interfaces\PartnerInterface\PartnerInterface;

class Aff1 implements PartnerInterface
{

  protected $apiKey;
  /**
   * @var mixed|null
   */
  private $endpoint;

  public function __construct($apiKey, $endpoint = null)
  {
    $this->apiKey = $apiKey;
    $this->endpoint = $endpoint;
  }

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
//    $message = '';
//    foreach($result as $k => $v) {
//      $message .= "$k => $v;\n";
//    }
//
//    return $response->commitResponseToDatabase(
//      $message, $lead['click_id']
//    );
  }

  private function prepareDataForPartner(array $lead): array
  {
    $result = [
      'api_key' => $this->apiKey,
      'phone' => $lead['phone'],
      'first_name' => $lead['name'],
      'target_hash' => $lead['product'],
      'country_code' => $lead['data_1'],
      'clickid' => $lead['click_id'],
    ];
    $result['phone2'] = (isset($lead['second_phone']) && null != $lead['second_phone']) ?
      $lead['second_phone'] : 'Empty';

    return $result;
  }
}