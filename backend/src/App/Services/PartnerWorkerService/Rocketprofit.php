<?php

namespace src\App\Services\PartnerWorkerService;

use src\App\Services\ApiResponseHandler\ResponseHandler;
use src\interfaces\PartnerInterface\PartnerInterface;

final class RocketProfit implements PartnerInterface
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
   * RocketProfit constructor.
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
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $this->endpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($this->prepareDataForPartner($lead)));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
    $result = curl_exec($ch);
    $result = json_decode($result, true);
    $response = new ResponseHandler();
    $message = '';
    foreach($result as $k => $v) {
      $message .= "$k => $v;\n";
    }

    return $response->commitResponseToDatabase(
      $message, $lead['click_id']
    );
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
      'api_key' => $this->apiKey,
      'name' => $lead['name'],
      'phone' => $lead['phone'],
      'ip' => $lead['data_1'],
      'campaign_id' => $lead['product'],
      'sid1' => $lead['click_id'],
      'country_code' => $lead['data_2'],
    ];
    $result['extra_phone'] = (isset($lead['second_phone']) && null != $lead['second_phone']) ?
      $lead['second_phone'] : null;

    if (!empty($lead['data_3'])) {
      $decoded = json_decode($lead['data_3'], true);

      if (false !== $decoded) {
        if (!empty($decoded['gender'])) {
          $result['comment'] = $decoded['gender'];
        }
        if (!empty($decoded['age'])) {
          $result['comment'] = "Gender: {$result['comment']} . Date of birth: {$decoded['age']}";
        }
      }
    }
    return $result;
  }
}