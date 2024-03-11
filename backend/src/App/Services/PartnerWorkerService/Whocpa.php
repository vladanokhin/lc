<?php

namespace src\App\Services\PartnerWorkerService;

use src\App\Services\ApiResponseHandler\ResponseHandler;
use src\interfaces\PartnerInterface\PartnerInterface;

class Whocpa implements PartnerInterface
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
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api2.whocpa.asia/lead/add',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => ['Content-Type: application/x-www-form-urlencoded'],
        CURLOPT_POST => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_POSTFIELDS => http_build_query($this->prepareDataForPartner($lead)),
      )
    );
    $res = curl_exec($curl);
    curl_close($curl);
    $result = json_decode($res, true);

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
      'click_id' => $lead['click_id'],
      'api_key' => $this->apiKey,
      'phone' => $lead['phone'],
      'name' => $lead['name'],
      'offer_id' => $lead['product'],
      'aff_id' => 1015489475,
      'ip' => $lead['data_1'],
      'sub1' => $lead['click_id'],
      'sub2' => 'Empty',
      'sub3' => 'Empty',
      'sub4' => 'Empty',
      'comment' => 'Empty'
    ];
    $result['sub5'] = (isset($lead['second_phone']) && null != $lead['second_phone']) ?
      $lead['second_phone'] : 'Empty';

    return $result;
  }
}
