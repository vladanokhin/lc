<?php

namespace src\App\Services\PartnerWorkerService;

use src\App\Services\ApiResponseHandler\ResponseHandler;
use src\interfaces\PartnerInterface\PartnerInterface;

final class Terraleads implements PartnerInterface
{
  /**
   * @var array
   */
  protected $config = [];

  const USER_ID = '3225';

  /**
   * Terraleads constructor.
   * @param string $apiKey
   * @param string|null $endpoint
   */
  public function __construct(string $apiKey, string $endpoint = null)
  {
    $this->config['api_key'] = $apiKey;
    $this->config['user_id'] = self::USER_ID;
    $this->config['create_url'] = $endpoint;
  }

  /**
   * @param array $lead
   * @param string $product
   * @return bool
   */
  public function sendLead(array $lead, string $product): bool
  {
    $data = [
      'user_id' => $this->config['user_id']
    ];

    $allow_params = [
      "name", "country", "phone", "tz", "offer_id", "stream_id", "address",
      "utm_source", "utm_medium", "utm_campaign", "utm_term", "utm_content",
      "sub_id", "sub_id_1", "sub_id_2", "sub_id_3", "sub_id_4", "subid1",
    ];
    $lead = $this->prepareDataForPartner($lead);

    foreach ($lead as $param_key => $param_value) {
      if (!empty($param_value) && in_array($param_key, $allow_params)) {
        $data[$param_key] = $param_value;
      }
    }
    $data['check_sum'] = sha1(
      $this->config['user_id'] .
      $lead['offer_id'] .
      $data['name'] .
      $data['phone'] .
      $this->config['api_key']
    );
    $result = $this->post_request($this->config['create_url'], json_encode($data));

    return true;
//    $response = new ResponseHandler();
//    $message = '';
//    foreach($result as $k => $v) {
//      $message .= "$k => $v; \n";
//    }
//
//    return $response->commitResponseToDatabase(
//      $message, $lead['click_id']
//    );
  }

  protected function post_request($url, $data): array
  {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);

    $curl_error = curl_error($ch);
    $curl_errno = curl_errno($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return [
      'error' => $curl_error,
      'errno' => $curl_errno,
      'http_code' => $http_code,
      'result' => $result,
    ];
  }

  protected function prepareDataForPartner(array $lead): array
  {
    $secondPhone = (isset($lead['second_phone']) && null != $lead['second_phone']) ?
      "Second phone: {$lead['second_phone']}" : null;
    return [
      "name" => $lead['name'],
      "phone" => $lead['phone'],
      "offer_id" => $lead['product'],
      "sub_id_1" => $lead['click_id'],
      "stream_id" => $lead['data_1'],
      "country" => $lead['data_2'],

      "address" => null,
      "utm_source" => null,
      "utm_medium" => null,
      "utm_campaign" => null,
      "utm_term" => null,
      "utm_content" => null,
      "sub_id" => null,
      "comment" => $secondPhone,
      "sub_id_3" => null,
      "sub_id_4" => null,
      "tz" => null,
    ];
  }
}