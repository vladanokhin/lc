<?php

namespace src\App\Services\PartnerWorkerService;

use src\App\Services\ApiResponseHandler\ResponseHandler;
use src\interfaces\PartnerInterface\PartnerInterface;

class Leadreaktor implements PartnerInterface
{
  protected $api_key;
  protected $endpoint;

  public function __construct(string $apiKey, string $endpoint = null)
  {
    $this->apiKey = $apiKey;
    $this->url = $endpoint;
  }

  public function sendLead(array $lead, string $product): bool
  {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "{$this->url}?api_key={$this->apiKey}");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $this->prepareDataForPartner($lead));
    $result = curl_exec($ch);
    curl_close($ch);
    $result = json_decode($result, true);

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
    return [
      'msisdn' => $lead['phone'],
      'name' => $lead['name'],
      'country' => $lead['data_1'],  // geo
      'goods_id' => $lead['product'], // offer id
      'url_params[sub1]' => $lead['click_id']
    ];
  }
}