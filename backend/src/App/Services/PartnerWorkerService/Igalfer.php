<?php

namespace src\App\Services\PartnerWorkerService;

use src\App\Services\ApiResponseHandler\ResponseHandler;
use src\interfaces\PartnerInterface\PartnerInterface;

final class Igalfer implements PartnerInterface
{

  public function __construct(string $apiKey, string $endpoint = null)
  {
    $this->apiKey = $apiKey;
    $this->endpoint = $endpoint;
  }

  public function sendLead(array $lead, string $product): bool
  {
    $url = $this->endpoint . "?" . http_build_query($this->prepareDataForPartner($lead));
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
    curl_setopt($ch, CURLOPT_HTTPGET, true);
    $result_json = curl_exec($ch);
    curl_close($ch);
    $result = json_decode($result_json, true);

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

  private function prepareDataForPartner($lead): array
  {
    return [
      'order_id' => $lead['click_id'],
      'name' => $lead['name'],
      'phone' => $lead['phone'],
      'api_key' => $this->apiKey,
      'goods_id' => $lead['product'],
    ];
  }
}