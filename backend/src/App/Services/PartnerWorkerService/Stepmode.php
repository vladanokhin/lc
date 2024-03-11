<?php

namespace src\App\Services\PartnerWorkerService;

use src\App\Services\ApiResponseHandler\ResponseHandler;
use src\interfaces\PartnerInterface\PartnerInterface;

final class Stepmode implements PartnerInterface
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
    $this->endpoint = $endpoint. "/apiNewLead.php?token=". $apiKey;
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

    return true;
//    $response = new ResponseHandler();
//    $message = '';
//    foreach($result as $k => $v) {
//      $message .= "$k => $v;\n";
//    }

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
    $result = [
      'full_name' => $lead['name'],
      'phone' => $lead['phone'],
      'goodID' => $lead['product'],
      'quantity' => 1,
      'price' => $lead['data_1'],
      'country' => $lead['data_2'],
      'additional1' => "",
      'additional2' => $lead['click_id'],
      'additional3' => "",
      'external' => "",
    ];
    $result['additional3'] = (isset($lead['second_phone']) && null != $lead['second_phone']) ?
      $lead['second_phone'] : "";

    if (!empty($lead['data_3'])) {
      $decoded = json_decode($lead['data_3'], true);

      if (false !== $decoded) {
        if (!empty($decoded['id'])) {
          $result['additional1'] = $decoded['id'];
        }
      }
    }
    return $result;
  }
}