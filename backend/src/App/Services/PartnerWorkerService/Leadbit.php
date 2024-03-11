<?php

namespace src\App\Services\PartnerWorkerService;

use src\App\Services\ApiResponseHandler\ResponseHandler;
use src\interfaces\PartnerInterface\PartnerInterface;

class Leadbit implements PartnerInterface
{
  protected $apiKey;

  protected $url;

  public function __construct(string $apiKey, string $endpoint = null)
  {
    $this->apiKey = $apiKey;
    $this->url = "{$endpoint}{$this->apiKey}";
  }

  public function sendLead(array $lead, string $product): bool
  {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $this->url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($ch, CURLOPT_REFERER, $this->url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $this->prepareDataForPartner($lead));
    $return = curl_exec($ch);
    curl_close($ch);
    $result = json_decode($return, true);

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
    // Принимают только ОДИН номер телефона! Второй номер щлём в комментарий
    $result = [
      'country' => $lead['data_2'],     // country code
      'flow_hash' => $lead['product'],    // product
      'name' => $lead['name'],       //
      'phone' => $lead['phone'],      //
      'sub1' => $lead['click_id'],   //
      'sub4' => $lead['data_1'],     // ip

      'landing' => '',
      'referrer' => '',
      'address' => '',
      'email' => '',
      'lastname' => '',
      'layer' => '',
      'sub2' => '',
      'sub3' => '',
      'sub5' => '',
    ];
    if (isset($lead['second_phone']) && !empty($lead['second_phone'])) {
      $result['comment'] = $lead['second_phone'];
    }

    return $result;
  }
}
