<?php

namespace src\App\Services\PartnerWorkerService;

use src\App\Services\ApiResponseHandler\ResponseHandler;
use src\interfaces\PartnerInterface\PartnerInterface;

class Barracuda implements PartnerInterface
{
  /**
   * @var string
   */
  private $endpoint;

  public function __construct(string $apiKey, string $endpoint = null)
  {
    $this->endpoint = "{$endpoint}?api_key={$apiKey}";
  }

  public function sendLead(array $lead, string $product): bool
  {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $this->endpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($this->prepareDataForPartner($lead)));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
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
    $lead['data_3'] = json_decode($lead['data_3'], true);
    $result = [
      'name' => $lead['name'],
      'phone' => $lead['phone'],
      'product' => $lead['product'],
      'subid1' => $lead['click_id'],
      'address' => $lead['data_1'],
      'utm_term' => $lead['data_2'],
      'price' => $lead['data_3']['price'],
      'payout' => $lead['data_3']['payout'],
    ];
    if (isset($lead['second_phone']) && !empty($lead['second_phone'])) {
      $result['comment'] = "Second phone: {$lead['second_phone']}";
    }

    return $result;
  }
}