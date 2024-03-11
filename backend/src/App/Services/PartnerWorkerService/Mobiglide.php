<?php

namespace src\App\Services\PartnerWorkerService;

use src\App\Services\ApiResponseHandler\ResponseHandler;
use src\interfaces\PartnerInterface\PartnerInterface;

class Mobiglide implements PartnerInterface
{

  private $apiKey;

  private $endpoint;

  public function __construct(string $apiKey, string $endpoint = null)
  {
    $this->apiKey = $apiKey;
    $this->endpoint = $endpoint;
  }

  public function sendLead(array $lead, string $product): bool
  {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $this->endpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
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

  private function prepareDataForPartner(array $lead): array
  {
    $result = [
      'api_key' => $this->apiKey,
      'click_id' => $lead['click_id'],
      'offer_id' => $lead['product'],
      'name' => $lead['name'],
      'phone' => $lead['phone'],
    ];

    $result['address'] = (isset($lead['second_phone']) && null != $lead['second_phone']) ?
      $lead['second_phone'] : null;

    return $result;
  }
}