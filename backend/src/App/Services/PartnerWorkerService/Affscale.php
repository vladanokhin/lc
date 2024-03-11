<?php

namespace src\App\Services\PartnerWorkerService;

use src\App\Services\ApiResponseHandler\ResponseHandler;
use src\interfaces\PartnerInterface\PartnerInterface;

class Affscale implements PartnerInterface
{

  /**
   * @var string
   */
  protected $apiKey;

  /**
   * @var string
   */
  protected $endpoint;

  public function __construct(string $apiKey, $endpoint = null)
  {
    $this->apiKey = $apiKey;
    $this->endpoint = "{$endpoint}?api-key={$apiKey}";
  }

  /**
   * @param array $lead
   * @param string $product
   * @return bool
   */
  public function sendLead(array $lead, string $product): bool
  {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $this->endpoint);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $this->prepareDataForPartner($lead));
    curl_setopt($curl, CURLOPT_HTTPHEADER,
      ['Cache-Control: no-cache', 'Content-Type: application/json']
    );
    $result = curl_exec($curl);
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

  /**
   * @param array $lead
   * @return string
   */
  protected function prepareDataForPartner(array $lead): string
  {
    $secondPhone = (isset($lead['second_phone']) && null != $lead['second_phone']) ?
      $lead['second_phone'] : null;

    return json_encode([
      'api-key' => $this->apiKey,       // api key
      'firstname' => $lead['name'],       // name
      'phone' => $lead['phone'],      // phone
      'goal_id' => $lead['product'],    // name of product
      'aff_click_id' => $lead['click_id'],   // click_id
      'custom1' => $secondPhone,
    ]);
  }

  /**
   * @param string $response
   * @return bool
   */
  private function handlePartnerResponse(string $response): bool
  {
    $response = json_decode($response, true);
    if (
      is_array($response) &&
      array_key_exists('code', $response) &&
      array_key_exists('message', $response) &&
      array_key_exists('status', $response)
    ) {
      return
        $response['code'] == '200' &&
        $response['message'] == 'OK' &&
        $response['status'] == 'success';
    }

    return false;
  }
}

