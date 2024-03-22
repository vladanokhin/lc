<?php

namespace src\App\Services\LeadCollectorService;

use Exception;
use src\App\Container\AppContainer;

class LeadDataHandler
{
  /**
   * Filtering lead data. Filtering out unnecessary data.
   *
   * @param $data
   * @return array
   * @throws Exception
   */
  public function weedOutData($data): array
  {
    $needed = AppContainer::get('neededLeadData');
    $result = [];
    foreach ($data as $id => $item) {
      if (in_array($id, $needed)) {
        $result[$id] = $item;
      }
    }

    return $result;
  }

  /**
   * Check if all needed data is required. Otherwise, setting defaults
   *
   * @param array $request
   * @return array
   */
  public function leadHandling(array $request): array
  {
    return $this->isLeadValid($request);
  }

  /**
   * Lead data validation
   *
   * @param array $request
   * @return array
   */
  private function isLeadValid(array $request): array
  {
    $result = [];

    $result['t_id'] = (!empty($request['t_id']) ? $request['t_id'] :
      ((!array_key_exists('t_id', $request)) ?
        "Without Tracker ID" : "Tracker ID is empty"));

    $result['name'] = (!empty($request['name']) ? $request['name'] :
      ((!array_key_exists('name', $request)) ?
        "Without Name" : "Name is empty"));

    $result['phone'] = (!empty($request['phone']) ? $request['phone'] :
      ((!array_key_exists('phone', $request)) ?
        "Without Phone" : "Phone is empty"));

    $result['product'] = (!empty($request['product']) ? $request['product'] :
      ((!array_key_exists('product', $request)) ?
        "Without Product" : "Product is empty"));

    $result['click_id'] = (!empty($request['click_id']) ? $request['click_id'] :
      ((!array_key_exists('click_id', $request)) ?
        "Without Click ID" : "Click ID is empty"));

    $result['offer_name'] = (!empty($request['offer_name']) ? $request['offer_name'] :
      ((!array_key_exists('offer_name', $request)) ?
        "Without Offer Name" : "Offer Name is empty"));

    $result['conversion_status'] = (!empty($request['conversion_status']) ? $request['conversion_status'] :
      ((!array_key_exists('conversion_status', $request)) ?
        "Without Conversion status" : "Conversion status is empty"));

    $result['offer_id'] = (!empty($request['offer_id']) ? $request['offer_id'] :
      ((!array_key_exists('offer_id', $request)) ?
        "Without Offer Id" : "Offer Id is empty"));

    $result['country_code'] = (!empty($request['country_code']) ? $request['country_code'] :
      ((!array_key_exists('country_code', $request)) ?
        "Without Country Code" : "Country Code is empty"));

    $result['aff_network_name'] = (!empty($request['aff_network_name']) ? $request['aff_network_name'] :
      ((!array_key_exists('aff_network_name', $request)) ?
        "Without Affiliate Network Name" : "Affiliate Network Name is empty"));

    $result['data_1'] = (!empty($request['data_1']) ? $request['data_1'] : null);
    $result['data_2'] = (!empty($request['data_2']) ? $request['data_2'] : null);
    $result['data_3'] = (!empty($request['data_3']) ? $request['data_3'] : null);

    return $result;
  }

  public function filterLeadBeforeUpdate(array $request): array
  {
    return [
      'offer_name' => $request['offer_name'],
      'conversion_status' => $request['conversion_status_one'] ?? $request['conversion_status'],
      'offer_id' => $request['offer_id'],
      'country_code' => $request['country_code'] ?? '',
      'aff_network_name' => $request['aff_network_name']
    ];
  }

  /**
   * Postback data validation
   *
   * @param $data
   * @return bool
   */
  public function validPostback($data): bool
  {
    return (
      isset($data['cnv_id']) &&
      !empty($data['cnv_id'])
    );
  }


  public function validateSecondPhone(array &$lead): void
  {
    $lead['second_phone'] = isset($lead['second_phone']) ?
      preg_replace('/[^+?0-9]/', '', $lead['second_phone']) : null;
  }

  public function validateEmail(array &$lead): void
  {
    if (!filter_var($lead["user_email"], FILTER_VALIDATE_EMAIL)) {
      $lead['user_email'] = urlencode($lead['user_email']);
      $lead['user_email'] = "not valid: {$lead['user_email']}";
    }
  }

  /**
   * Суть валидации в ограничении длины и "санитаризации" строк (удаление опасных символов)
   *
   * @param array $lead
   * @return void
   */
  public function validateUserData(array &$lead): void
  {
    $lead['t_id'] = isset($lead['t_id']) ?
      preg_replace('/[^+0-9]/', '', $lead['t_id']) : null;

    $lead['name'] = isset($lead['name']) ? str_replace(
      ['\\', "\n", "\r", "'", '"', "\x1a", "#", "*", "@", "~", "`", ";", "|"],
      ['\\\\', '\\n', '\\r', "\\'", '\\"', '\\Z', '\\#', '\\*', '\\@', '\\~', '\\`', '\\;', '\\|'],
      $lead['name']
    ) : null;

    $lead['phone'] = isset($lead['phone']) ? str_replace(
      ['\\', "\n", "\r", "'", '"', "\x1a", "#", "*", "@", "~", "`", ";", "|"],
      ['\\\\', '\\n', '\\r', "\\'", '\\"', '\\Z', '\\#', '\\*', '\\@', '\\~', '\\`', '\\;', '\\|'],
      $lead['phone']
    ) : null;

    $lead['click_id'] = isset($lead['click_id']) ?
      preg_replace('/[^A-Za-z0-9]/', '', $lead['click_id']) : null;

    $lead['product'] = isset($lead['product']) ?
      preg_replace('/[^0-9A-Za-z_-]/', '', $lead['product']) : null;

    $lead['data_1'] = isset($lead['data_1']) ? str_replace(
      ['\\', "\n", "\r", "'", '"', "\x1a", "#", "*", "@", "~", "`", ";", "|"],
      ['\\\\', '\\n', '\\r', "\\'", '\\"', '\\Z', '\\#', '\\*', '\\@', '\\~', '\\`', '\\;', '\\|'],
      $lead['data_1']
    ) : null;

    $lead['data_2'] = isset($lead['data_2']) ? str_replace(
      ['\\', "\n", "\r", "'", '"', "\x1a", "#", "*", "@", "~", "`", ";", "|"],
      ['\\\\', '\\n', '\\r', "\\'", '\\"', '\\Z', '\\#', '\\*', '\\@', '\\~', '\\`', '\\;', '\\|'],
      $lead['data_2']
    ) : null;
  }

}