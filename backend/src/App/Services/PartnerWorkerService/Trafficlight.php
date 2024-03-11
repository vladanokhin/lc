<?php

namespace src\App\Services\PartnerWorkerService;

use src\App\Services\ApiResponseHandler\ResponseHandler;
use src\interfaces\PartnerInterface\PartnerInterface;

final class Trafficlight implements PartnerInterface
{
  /**
   * Set API key in constructor if needed
   * @var $apiKey
   */
  protected $apiKey;

  /**
   * @var string|null
   */
  private $endpoint;

  /**
   * Everad constructor.
   * @param string $apiKey
   * @param string|null $endpoint
   */
  public function __construct(string $apiKey, string $endpoint = null)
  {
    $this->apiKey = $apiKey;
    $this->endpoint = $endpoint;
  }

  /**
   * Send prepared lead via CURL to partner
   *
   * @param array $lead
   * @param string $product
   * @return bool
   */
  public function sendLead(array $lead, string $product): bool
  {
    $options = array(
      'http' => array(
        'header' => "Content-type: application/x-www-form-urlencoded\r\n",
        'method' => 'POST',
        'content' => http_build_query($this->prepareDataForPartner($lead)),
        'ignore_errors' => true,
      )
    );
    $context = stream_context_create($options);
    $result = file_get_contents($this->endpoint, false, $context);
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
   * Preparing lead for partner
   *
   * @param array $lead
   * @return array
   */
  private function prepareDataForPartner(array $lead): array
  {
    $secondPhone = (isset($lead['second_phone']) && null != $lead['second_phone']) ?
      $lead['second_phone'] : '';

    return [
      'key' => $this->apiKey,
      'id' => $lead['click_id'],
      'offer_id' => $lead['product'],
      'name' => $lead['name'],
      'phone' => $lead['phone'],
      'ip_address' => $lead['data_1'],
      'sub1' => $lead['click_id'],
      'country' => $lead['data_2'],
      'comments' => $secondPhone,
    ];
  }
}