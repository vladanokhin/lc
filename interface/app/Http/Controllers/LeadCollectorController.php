<?php

namespace App\Http\Controllers;

use App\Models\LeadCollector;
use App\Services\ReorderService;
use App\Services\ResendFixedLeads;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

class LeadCollectorController extends Controller
{
  protected const API = 'https://quantum.nncleads.com';
  protected $lead;

  public function __construct(LeadCollector $leadCollector)
  {
    $this->lead = $leadCollector;
  }

  /**
   * @param Request $request
   * @return Application|Factory|\Illuminate\Contracts\View\View
   */
  public function index(Request $request)
  {
    $search = new DataSearchController();
    $leads = $search->search($request);
    $history = $search->getHistory('34d0awf3zslc8i4faa');

    return view('lead-collector.leads', [
      'history' => $history,
      'leads' => $leads['leads']->withPath('/leads'),
      'count' => $leads['count'],
    ]);
  }

  /**
   * Full info about lead, on new page
   * @param $id
   * @return Application|Factory
   */
  public function show($id)
  {
    $data = LeadCollector::findOrFail($id)->first();
    $lead = json_decode($data, true);

    return view('lead-collector.lead', [
      'lead' => $lead
    ]);
  }


  /**
   * Opportunity to download leads. Returns csv with needed leads.
   * @param Request $request
   * @return Response
   */
  public function download(Request $request)
  {

    return null;
    $search = new DataSearchController();
    $payload = $search->downloaderSearch($request);

    dd($payload);
    return null;
  }


  /**
   * Refresh data
   * @param string $click_id
   * @return bool
   */
  public function refresh(string $click_id): bool
  {
    $server = self::API . "/refresh/{$click_id}";
    $response = Http::get($server);

    return ($response->ok() && $response->body() != '');
  }

  /**
   * Send lead to partner one more time
   * @param string $click_id
   * @return bool
   */
  public function reorder(string $click_id): bool
  {
    $server = self::API . "/reorder/{$click_id}";
    $response = Http::get($server);

    return ($response->ok() && $response->body() != '');
  }

  /**
   * Delete lead using unique_id
   * @param $unique
   * @return void
   */
  public function delete($unique): void
  {
    $server = self::API . "/delete/{$unique}";
    $response = Http::delete($server);

    if ($response->ok()) {
      echo json_encode(['status' => 'success']);
    } else {
      echo json_encode(['status' => 'fail']);
    }
    exit();
  }

  public function reorderEdited(Request $request)
  {
    $server = self::API . "/reorder-edited";
    $reorder = new ReorderService();
    $response = $reorder->reorderEdited($request->only(['clickid', 'new_phone', 'new_name']), $server);

    return $response->json();
  }

  public function editMultipleLeads($leads, $new_data)
  {
    dump($leads);
  }

  public function backfixConfigurator()
  {
    return view('app.create-backfix.backfix');
  }

  public function leadCollectorSettings()
  {
    return view('lead-collector.settings.control-panel');
  }

  public function massAssign(Request $request)
  {
    $data = $request->only(['action', 'data']);
    $leads = $data['data'];
    foreach ($leads as $id => $lead) {
      if (method_exists($this, $data['action'])) {
        $method = $data['action'];
        if (false === $this->$method($lead)) {
          echo json_encode(["{$lead}" => 'error']);
        }
      }
      time_nanosleep(0, 300);
    }
    echo json_encode(['status' => 'complete']);
  }

  public function leadsDataFix(Request $request)
  {
    $leads = $request->only('click-id-list');
    $newProduct = $request->only('new-product');
    $datas = $request->only(['data_1', 'data_2', 'data_3']);
    $resend = new ResendFixedLeads();
    $payload = $resend->leadsDataFix($leads, $newProduct, $datas);

    return $resend->resendFixedLeads($payload);
  }
}
