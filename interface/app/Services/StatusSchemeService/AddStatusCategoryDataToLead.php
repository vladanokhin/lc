<?php

namespace App\Services\StatusSchemeService;

use Illuminate\Support\Facades\DB;

class AddStatusCategoryDataToLead
{
  protected $statuses = [];

  public function addData(
    &$leads
  ): void
  {
    $payloads = DB::connection('mysql_lc')->table('lead_collector_statuses')
      ->join('ad2lynx_statuses', 'lead_collector_statuses.status_id', '=', 'ad2lynx_statuses.ad2lynx_statuses_id')
      ->get()->toArray();

    foreach ($leads as $lead) {
      foreach ($payloads as $_ => $payload) {

        $payload = (array)$payload;
        if (in_array($lead->conversion_status, $payload)) {
          $lead->category = strtolower($payload["status_category"]);
        }
      }
    }
  }
}
