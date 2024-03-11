<?php

namespace src\App\Services\PartnerWorkerService;

use src\App\Services\ApiResponseHandler\ResponseHandler;
use src\interfaces\PartnerInterface\PartnerInterface;

final class Admad implements PartnerInterface
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
    $json = json_encode($this->prepareDataForPartner($lead));
    $response = new ResponseHandler();
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $this->endpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    $affHeader = $this->setupHeaderWithAffiliateCode($lead['data_1']);
    if (null === $affHeader) {
      curl_close($ch);
      return $response->commitResponseToDatabase(
        "geo: {$lead['data_1']} - not registered!", $lead['click_id']
      );
    }
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
      'Content-Type: application/json',
      'Accept: application/json',
      'Content-Length: ' . strlen($json),
      'Affiliate: '.$affHeader,
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);

    $result = curl_exec($ch);
    $result = json_decode($result, true);

    $message = '';
    foreach ($result as $k => $v) {
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
    return [
      'order' => [
        'firstname' => $lead['name'],
        'phone' => $lead['phone'],
        'country' => $lead['data_1'],
        'offer_code' => $lead['product'],
        'external_partner_id' => $lead['click_id'],
      ]
    ];
  }

  private function setupHeaderWithAffiliateCode($geo): ?string
  {
    $geo = strtolower($geo);
    $keys = [
      "pl" => "36fb4c8d-28aa-4306-87e9-f55a7f6d7f4a",
      "cz" => "455ca528-0d46-4121-8e2f-12a4bd003a36",
      "sk" => "29e65216-fcd9-4d35-b1bb-d0cb999d0853",
      "de" => "f0a90d39-a2d1-438b-a9e5-90321ff577d2",
      "at" => "8fa0f78f-ce60-4919-9743-e46b9bcd1d2b",
      "it" => "1e4b9339-0be2-40aa-86a3-6bb44cec4078",
      "es" => "8051b51b-6130-4ce9-abcd-a91721d79c9b",
      "pt" => "311cc8a4-475b-44a6-9a87-be00e098a09d",
      "hu" => "ebb77009-565d-4890-9f7b-33bb1e829df2",
      "hr" => "eba0faee-1bf3-49c2-8c4f-c4919957133a",
      "ro" => "57171beb-3ecf-4ea6-b15a-d22b3ca37545",
      "si" => "06c12c9f-e73f-49b0-8d82-0618c4757925",
    ];
    return (isset($keys[$geo])) ? $keys[$geo] : null;
  }
}