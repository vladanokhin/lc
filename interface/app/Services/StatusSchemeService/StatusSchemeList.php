<?php

namespace App\Services\StatusSchemeService;

use Illuminate\Support\Facades\DB;

class StatusSchemeList
{
  protected $statuses = [];

  public function getJoinedStatusList(): array
  {
    $payloads = DB::connection('mysql_lc')->table('lead_collector_statuses')
      ->join('ad2lynx_statuses', 'lead_collector_statuses.status_id', '=', 'ad2lynx_statuses.ad2lynx_statuses_id')
      ->get()->toArray();

    foreach ($payloads as $key => $value) {
      $this->statuses[$value->status_category]['related_statuses'][$value->incoming_status_name] = [
        'id' => $value->id,
        'status_locked' => $value->status_locked,
        'accept_payment' => $value->accept_payment,
      ];
      $this->statuses[$value->status_category]['weight'] = $value->weight;
    }

    return $this->statuses;
  }

  public static function getAd2LynxCategories()
  {
    return DB::connection('mysql_lc')->table('ad2lynx_statuses')->get()->toArray();
  }
}
