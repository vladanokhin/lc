<?php

namespace src\App\Services\PartnerWorkerService;

use src\interfaces\PartnerInterface\PartnerInterface;

class newPartner implements PartnerInterface
{
  private $token;
  private $endpoint;

  public function __construct($token = null, $endpoint = null)
  {
    $this->token = $token;
    $this->endpoint = $endpoint;
  }

  public function sendLead(array $lead, string $product): bool
  {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $this->endpoint);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $this->prepareDataForPartner($lead));
    curl_setopt($curl, CURLOPT_HTTPHEADER, []);

    return curl_exec($curl);
  }

  protected function prepareDataForPartner(array $lead): string
  {
    if (isset($lead['data_3']) && !empty($lead['data_3'])) {
      $lead['data_3'] = json_decode($lead['data_3'], true);
    }

    $secondPhone = (isset($lead['second_phone']) && null != $lead['second_phone']) ?
      $lead['second_phone'] : null;

    return [
      'subid' => $lead['click_id'],
      'flow_id' => $lead['product'],
      'username' => $lead['name'],
      'username' => $secondPhone
    ];
  }
}