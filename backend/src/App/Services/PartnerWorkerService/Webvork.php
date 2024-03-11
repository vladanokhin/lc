<?php

namespace src\App\Services\PartnerWorkerService;

use src\App\Services\ApiResponseHandler\ResponseHandler;
use src\interfaces\PartnerInterface\PartnerInterface;

final class Webvork implements PartnerInterface
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
    $this->endpoint = explode(';', $endpoint);
  }

  /**
   * Send prepared lead via CURL to partner
   *
   * @param array $lead
   * @param string $product
   * @return bool
   * @throws \Exception
   */
  public function sendLead(array $lead, string $product): bool
  {
    $data = $this->prepareDataForPartner($lead);
    if ($this->checkData($data)) {
      $client = new WebvorkSuperClient();
      $client->addEndpoint($this->endpoint[0])
        ->addEndpoint($this->endpoint[1])
        ->setWaitHttpStatus200(true);
      $response = $client->send($data);
      $json = json_decode($response, true);

      return true;
//      $response = new ResponseHandler();
//      $message = '';
//      foreach ($result as $k => $v) {
//        $message .= "$k => $v;\n";
//      }
//
//      return $response->commitResponseToDatabase(
//        $message, $lead['click_id']
//      );
    }
    return false;
  }

  /**
   * Preparing lead for partner
   *
   * @param array $lead
   * @return array
   */
  private function prepareDataForPartner(array $lead): array
  {
    $data = [
      'token' => $this->apiKey,
      'offer_id' => $lead['product'],
      'name' => $lead['name'],
      'phone' => $lead['phone'],
      'country' => $lead['data_2'],
      'utm_source' => '',
      'utm_medium' => '',
      'utm_campaign' => '',
      'utm_content' => $lead['click_id'],
      'utm_term' => '',
      'ip' => $lead['data_1'],
    ];
    $data['requestId'] = sha1(time() . http_build_query($data));

    return $data;
  }

  private function checkData($data)
  {
    return (
      !empty($data['token']) ||
      !empty($data['offer_id']) ||
      !empty($data['phone']) ||
      !empty($data['country'])
    );
  }
}