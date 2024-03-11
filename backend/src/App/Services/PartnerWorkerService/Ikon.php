<?php

namespace src\App\Services\PartnerWorkerService;

use src\App\Services\ApiResponseHandler\ResponseHandler;
use src\interfaces\PartnerInterface\PartnerInterface;

class Ikon implements PartnerInterface
{

  /**
   * @var string
   */
  private $apiKey;
  /**
   * @var string|null
   */
  private $endpoint;

  /**
   * @param string $apiKey
   * @param string|null $endpoint
   */
  public function __construct(string $apiKey, string $endpoint = null)
  {
    $this->apiKey = $apiKey;
    $this->endpoint = $endpoint;
  }

  /**
   * @param array $lead
   * @param string $product
   * @return bool
   */
  public function sendLead(array $lead, string $product): bool
  {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $this->endpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($this->prepareDataForPartner($lead)));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    $ch = curl_exec($ch);
    $result = json_decode($ch, true);

    return true;
//    $response = new ResponseHandler();
//    $message = '';
//    foreach ($result as $k => $v) {
//      $message .= "$k => $v;\n";
//    }
//
//    return $response->commitResponseToDatabase(
//      $message, $lead['click_id']
//    );
  }

  /**
   * @param array $lead
   * @return array
   */
  private function prepareDataForPartner(array $lead): array
  {
    $result = [
      'name' => $lead['name'],
      'phone' => $lead['phone'],
      'flow_hash' => $lead['product'],
      'sub1' => $lead['click_id'],
      'ip_address' => $lead['data_1'],
      'api_key' => $this->apiKey,
    ];

    $result['comment'] = (isset($lead['second_phone']) && null != $lead['second_phone']) ?
      $lead['second_phone'] : null;

    return $result;
  }
}