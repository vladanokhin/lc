<?php

namespace App\Services;


use Illuminate\Support\Facades\Http;

final class ResendFixedLeads {
  public function leadsDataFix(array $leads, $newProduct, $datas): ?array
  {
    $result = [];
    foreach ($datas as $dataNum => $data) {
      if (!empty($data)) {
        $result[$dataNum] = $data;
      }
    }
    $result['click-id-list'] = $leads['click-id-list'];
    $result['product'] = $newProduct['new-product'];

    return $result;
  }

  public function resendFixedLeads(array $payload)
  {
    $response = Http::post('https://quantum.nncleads.com/leads/datafix', [
      'payload' => $payload,
    ]);
    return $response->body();
  }
}
