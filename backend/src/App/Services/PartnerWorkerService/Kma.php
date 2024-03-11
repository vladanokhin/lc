<?php

namespace src\App\Services\PartnerWorkerService;

use src\App\Services\ApiResponseHandler\ResponseHandler;
use src\interfaces\PartnerInterface\PartnerInterface;

class Kma implements PartnerInterface
{
  protected $apiKey;
  protected $endpoint;

  public function __construct(string $apiKey, string $endpoint = null)
  {
    $this->apiKey = $apiKey;
    $this->url = $endpoint;
  }

  public function sendLead(array $lead, string $product): bool
  {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $this->url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
      'Content-Type: application/x-www-form-urlencoded',
      'Authorization: Bearer ' . $this->apiKey
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS,
      http_build_query($this->prepareDataForPartner($lead))
    );
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
      'name' => $lead['name'],
      'phone' => $lead['phone'],
      'channel' => $lead['product'],
      'data1' => $lead['click_id'],
      'country' => $lead['data_2'],
      'ip' => $lead['data_1'],
    ];
  }
}