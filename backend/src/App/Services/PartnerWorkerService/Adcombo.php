<?php

namespace src\App\Services\PartnerWorkerService;

use src\App\Services\ApiResponseHandler\ResponseHandler;
use src\interfaces\PartnerInterface\PartnerInterface;

class Adcombo implements PartnerInterface
{
  private $apiKey;
  private $api_url;

  public function __construct($apiKey, $endpoint = null)
  {
    $this->apiKey = $apiKey;
    $this->api_url = $endpoint;
  }

  /**
   * @param array $lead
   * @param string $product
   * @return bool
   */
  public function sendLead(array $lead, string $product): bool
  {
    $url = $this->api_url . '?' . http_build_query($this->prepareDataForPartner($lead));
    $curl = curl_init();
    curl_setopt_array($curl, [
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
    ]);
    $result = curl_exec($curl);
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

  /**
   * @param array $lead
   * @return array
   */
  private function prepareDataForPartner(array $lead): array
  {
    return [
      'api_key' => $this->apiKey,
      'name' => $lead['name'],           // name
      'phone' => $lead['phone'],          // phone
      'price' => $lead['data_1'],         // CHANGE on page
      'ip' => $lead['data_2'],         // ip -> from tracker // or 176.37.67.132 ?
      'offer_id' => $lead['product'],        // product  -> from page
      'referrer' => '',                      // empty on page
      'base_url' => '',                      // empty on page
      'subacc' => $lead['click_id'],       // click_id
      'country_code' => $lead['country_code'],   // country_code -> from tracker
      'cc_phone' => (isset($lead['second_phone']) && null != $lead['second_phone']) ?
        $lead['second_phone'] : null,
      'utm_campaign' => 'my-utm_campaign',
      'utm_content' => 'my-utm_content',
      'utm_medium' => 'my-utm_medium',
      'utm_source' => 'my-utm_source',
      'utm_term' => 'my-utm_term',
    ];
  }
}