<?php

namespace src\App\Services\PartnerWorkerService;

use src\App\Services\ApiResponseHandler\ResponseHandler;
use src\interfaces\PartnerInterface\PartnerInterface;

class Traffury implements PartnerInterface
{
  private $apiKey;
  private $endpoint;

  public function __construct(string $apiKey, string $endpoint = null)
  {
    $this->apiKey = $apiKey;
    $this->endpoint = "$endpoint?webmasterID=69&token=$apiKey";
  }

  public function sendLead(array $lead, string $product): bool
  {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $this->endpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($this->prepareDataForPartner($lead)));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
    $response = curl_exec($ch);
    $result = json_decode($response, true);

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

  protected function prepareDataForPartner(array $lead): array
  {
    $lead['data_3'] = json_decode($lead['data_3'], true);
    $result = [
      'utm_campaign' => $lead['click_id'],
      'fio' => $lead['name'],
      'phone' => $lead['phone'],
      'ip' => $lead['data_1'],
      'goods' => [0 => $lead['data_3']],
    ];
    if (isset($lead['second_phone']) && !empty($lead['second_phone'])) {
      $result['comment'] = $lead['second_phone'];
    }

    return $result;
  }
}