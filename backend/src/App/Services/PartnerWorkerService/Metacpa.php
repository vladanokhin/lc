<?php

namespace src\App\Services\PartnerWorkerService;

use src\App\Services\ApiResponseHandler\ResponseHandler;
use src\interfaces\PartnerInterface\PartnerInterface;

class Metacpa implements PartnerInterface
{

  /**
   * @var string
   */
  private $apiKey;
  /**
   * @var string|null
   */
  private $url;

  public function __construct(string $apiKey, string $endpoint = null)
  {
    $this->apiKey = $apiKey;
    $this->url = $endpoint;
  }

  public function sendLead(array $lead, string $product): bool
  {
    $data = http_build_query($this->prepareDataForPartner($lead));
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $this->url);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_POST, 1);
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
    $result = [
      'sub1' => $lead['click_id'],
      'name' => $lead['name'],
      'phone' => $lead['phone'],
      'flow_id' => $lead['product'],
      'geo' => $lead['data_1'],
      'ip' => $lead['data_2'],
    ];
    if (isset($lead['second_phone']) && !empty($lead['second_phone'])) {
      $result['comment'] = "Second phone: {$lead['second_phone']}";
    }

    return $result;
  }
}