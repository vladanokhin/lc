<?php

namespace src\App\Services\PartnerWorkerService;

use src\App\Services\ApiResponseHandler\ResponseHandler;
use src\interfaces\PartnerInterface\PartnerInterface;

class Offerstore implements PartnerInterface
{

  private $apiKey;
  private $endpoint;

  public function __construct(string $apiKey, string $endpoint = null)
  {
    $this->apiKey = $apiKey;
    $this->endpoint = "$endpoint?id=281-$apiKey";
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

  private function prepareDataForPartner(array $lead): array
  {
    $result = [
      'name' => $lead['name'],
      'phone' => $lead['phone'],
      'offer' => $lead['product'],
      'flow' => $lead['data_1'],
      'ip' => $lead['data_2'],
      'uc' => $lead['click_id'],
    ];
    $result['comm'] = (isset($lead['second_phone']) && null != $lead['second_phone']) ?
      $lead['second_phone'] : null;

    return $result;
  }
}
