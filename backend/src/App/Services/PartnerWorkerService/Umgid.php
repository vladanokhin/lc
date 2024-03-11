<?php

namespace src\App\Services\PartnerWorkerService;

use src\App\Services\ApiResponseHandler\ResponseHandler;
use src\interfaces\PartnerInterface\PartnerInterface;

class Umgid implements PartnerInterface
{
  protected $apiKey;
  /**
   * @var mixed|null
   */
  private $endpoint;

  public function __construct(string $apiKey, $endpoint = null)
  {
    $this->apiKey = $apiKey;
    $this->endpoint = $endpoint;
  }

  public function sendLead(array $lead, string $product): bool
  {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 60);
    curl_setopt($curl, CURLOPT_URL, $this->endpoint);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_POST, TRUE);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($this->prepareDataForPartner($lead)));
    curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
    $response = curl_exec($curl);
    $response = json_decode($response, true);

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

  private function prepareDataForPartner(array $data): array
  {
    $data['data_3'] = json_decode($data['data_3'], true);
    $secondPhone = (isset($lead['second_phone']) && null != $lead['second_phone']) ?
      $lead['second_phone'] : null;

    return [
      'subid1' => $data['click_id'],
      'first_name' => $data['name'],
      'phone_input' => $data['phone'],
      'key' => $data['data_1'],
      'offer_id' => $data['data_2'],
      'geo' => $data['data_3']['geo'],
      'ip' => $data['data_3']['ip'],
      'payment_id' => $data['data_3']['payment_id'] ?? '625',
      'product_id' => $data['product'],
      'comment' => "Second phone: {$secondPhone}",
    ];
  }
}