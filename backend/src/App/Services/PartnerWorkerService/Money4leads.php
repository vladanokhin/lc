<?php

namespace src\App\Services\PartnerWorkerService;

use src\App\Services\ApiResponseHandler\ResponseHandler;
use src\interfaces\PartnerInterface\PartnerInterface;

class Money4leads implements PartnerInterface
{
  /**
   * @var string|null
   */
  private $endpoint;
  /**
   * @var string
   */
  private $apiKey;

  public function __construct(string $apiKey, string $endpoint = null)
  {
    $this->apiKey = $apiKey;
    $this->endpoint = $endpoint;
  }

  public function sendLead(array $lead, string $product): bool
  {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL,
      $this->endpoint . '?' . http_build_query($this->prepareDataForPartner($lead))
    );
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($curl);
    curl_close($curl);
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
      'partnerId' => '1901',
      'access-token' => $this->apiKey,
      'fullName' => $lead['name'],
      'phone' => $lead['phone'],
      'offerId' => $lead['product'],
      'price' => $lead['data_1'],
      'country' => $lead['data_2'],
      'sub_id' => [$lead['click_id']],
    ];
    if (isset($lead['second_phone']) && !empty($lead['second_phone'])) {
      $result['comment'] = $lead['second_phone'];
    }

    return $result;
  }
}