<?php

namespace src\App\Services\PartnerWorkerService;

use src\App\Services\ApiResponseHandler\ResponseHandler;
use src\interfaces\PartnerInterface\PartnerInterface;

final class Skylead implements PartnerInterface
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
    $this->endpoint = "$endpoint?id={$apiKey}";
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

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,
      $this->endpoint. "&flow={$lead['data_1']}&offer={$lead['product']}"
    );
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS,
      http_build_query($this->prepareDataForPartner($lead))
    );
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
    $result = curl_exec($ch);
    $result = json_decode($result, true);

//    echo '<pre>';
//    var_dump($result);
//    echo '</pre>';

    return true;
  }

  /**
   * Preparing lead for partner
   *
   * @param array $lead
   * @return array
   */
  private function prepareDataForPartner(array $lead): array
  {
    $result = [
      'name' => $lead['name'],
      'phone' => $lead['phone'],
      'offer' => $lead['product'],
      'sub1' => $lead['click_id'],
      'flow' => $lead['data_1'],
      'ip' => $lead['data_2'],
    ];
    if (isset($lead['second_phone']) && null != $lead['second_phone']) {
      $result['phonecc'] = $lead['second_phone'];
    }

//    $result['email'] = (isset($lead['user_email']) && null != $lead['user_email']) ?
//      $lead['user_email'] : null;

    return $result;
  }
}