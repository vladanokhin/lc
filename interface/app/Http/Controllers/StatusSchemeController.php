<?php

namespace App\Http\Controllers;

use App\Models\Ad2lynx_statuses;
use App\Models\StatusSchemeModel;
use App\Services\StatusSchemeService\StatusSchemeList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatusSchemeController extends Controller
{
  public function loadScheme(Request $request)
  {
    $payload = $request->only([
      'partner_name',
      'ad2lynx_status',
      'new_status',
      'accept_payment_for_status',
      'add_event_2',
      'lock_lead_status',
    ]);
    $payload['new_status'] = strtolower($payload['new_status']);
    $payload['accept_payment_for_status'] = ($payload['accept_payment_for_status'] === true) ? 1 : 0;
    $payload['add_event_2'] = ($payload['add_event_2'] === true) ? 1 : 0;
    $payload['lock_lead_status'] = ($payload['lock_lead_status'] === true) ? 1 : 0;

    $statusScheme = new StatusSchemeModel();
    $statusScheme->partner_name = $payload['partner_name'];
    $statusScheme->status_id = $payload['ad2lynx_status'];
    $statusScheme->incoming_status_name = $payload['new_status'];
    $statusScheme->accept_payment = $payload['accept_payment_for_status'];
    $statusScheme->add_event_2 = $payload['add_event_2'];
    $statusScheme->status_locked = $payload['lock_lead_status'];
    $statusScheme->save();
    $status = 'ok';

    return response()->json([
      'status' => $status
    ]);
  }

  public function deleteRelatedStatus($id)
  {
    $relatedStatus = StatusSchemeModel::find($id);
    if (null == $relatedStatus) {
      $status = 'Status not found';
      $code = 500;
    } else {
      $relatedStatus->delete();
      $status = 'Status removed';
      $code = 200;
    }
    return response()->json([
      'message' => $status
    ], $code);
  }

  public function addAd2LynxStatus()
  {
    $data = Ad2lynx_statuses::all();
    $data = collect($data)->sortBy('weight');

    return view('lead-collector.settings.status-scheme.add-ad2lynx-category', ['data' => $data]);
  }

  public function commitAd2LynxStatus(Request $request)
  {
    $validatedData = $request->validate([
      'status_category' => 'required|string|max:40|unique:mysql_lc.ad2lynx_statuses,status_category',
      'weight' => 'required|integer',
    ]);
    $validatedData['status_category'] = strtolower($validatedData['status_category']);
    $result = Ad2lynx_statuses::create($validatedData);
    if (isset($result->ad2lynx_statuses_id)) {
      return redirect()->route('add-new-ad2lynx-status-category');
    }
    return response()->json([
      'message' => 'Something went wrong...'
    ], 500);
  }

  public function deleteAd2LynxStatus($id)
  {
    $category = Ad2lynx_statuses::find($id);
    if ($category == null) {
      return response()->json([
        'message' => "Category not found."
      ], 500);
    }
    $categoryName = $category->status_category;
    $category->delete();

    return response()->json([
      'message' => "Category \"{$categoryName}\" removed."
    ]);
  }


  public function advertiserStatusScheme(string $partnerName)
  {
    $statuses = DB::connection('mysql_lc')
      ->table('lead_collector_statuses')
      ->where('partner_name', $partnerName)
      ->join('ad2lynx_statuses', 'lead_collector_statuses.status_id', '=', 'ad2lynx_statuses.ad2lynx_statuses_id')
      ->get()
      ->toArray();
    $statuses = collect($statuses)->sortBy('weight');
    $ad2lynxCategories = new StatusSchemeList();
    $ad2lynxCategories = $ad2lynxCategories->getAd2LynxCategories();
    $scheme = [];
    foreach ($statuses as $_ => $value) {
      $scheme[$value->status_category][] = $value;
    }
    return view('lead-collector.settings.status-scheme.individual-status-scheme-board',
      [
        'ad2lynxCategories' => $ad2lynxCategories,
        'scheme' => $scheme,
        'partnerName' => $partnerName
      ]
    );
  }

}
