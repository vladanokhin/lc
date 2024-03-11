<?php

namespace App\Http\Controllers;

use App\Models\LeadCollector;
use App\Models\LeadResponse;
use App\Models\ScheduledLeadsModel;
use App\Services\DownloadService;
use App\Services\SearchService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class LeadCollectorFlow extends Controller
{
  protected $leadCollector;
  const API = 'https://quantum.nncleads.com';

  public function __construct()
  {
    $this->leadCollector = new LeadCollector();
  }

  /**
   * Display a listing of the resource.
   *
   * @param Request $request
   * @return Application|Factory|View
   */
  public function index(Request $request)
  {
    $search = new SearchService($this->leadCollector, $request);
    $leads = $search->search($request);

    return view('app.leads-flow.flow', [
      'leads' => $leads['leads']->withPath('/leads'),
      'count' => $leads['count']
    ]);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return Application|Factory|View|Response
   */
  public function scheduledLeads()
  {
    return view('lead-collector.scheduled-leads.get-queue',
      ['leads' => ScheduledLeadsModel::all()->sortDesc()]);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param Request $request
   * @return BinaryFileResponse
   */
  public function downloadLead(Request $request)
  {
    $file = new DownloadService($this->leadCollector);

    return $file->requestFor($request);
  }

  /**
   * Display the specified resource.
   *
   * @param $clickid
   * @param Request $request
   * @return Application|Factory|View
   */
  public function show($clickid, Request $request)
  {
    $search = new SearchService($this->leadCollector, $request);
    $history = $search->getHistory($clickid);
    $lead = LeadCollector::where('click_id', $clickid)->get()->toArray();
    $response = LeadResponse::where('click_id', $clickid)->get()->toArray();

    return view('app.leads-flow.lead-clickid', [
      'history' => $history,
      'lead' => $lead[0],
      'clickid' => $clickid,
      'response' => $response
    ]);
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param int $id
   * @return Response
   */
//    public function edit($id)
//    {
//        //
//    }

  /**
   * Update the specified resource in storage.
   *
   * @param Request $request
   * @param int $id
   * @return Response
   */
//    public function update(Request $request, $id)
//    {
//        //
//    }

  /**
   * Remove the specified resource from storage.
   *
   * @param int $id
   * @return Response
   */
//    public function destroy($id)
//    {
//        //
//    }
}
