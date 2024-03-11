<?php

namespace App\Http\Controllers;

use App\Models\TrackersSettingsModel;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeadCollectorTrackersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View|\Illuminate\Http\Response
     */
    public function index()
    {
        return view('lead-collector.settings.tracker-settings.trackers-dashboard',
            ['data' => TrackersSettingsModel::all()->sortBy('t_id', 0, false)->toArray()]
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View|\Illuminate\Http\Response
     */
    public function create()
    {
        return view('lead-collector.settings.tracker-settings.add-tracker');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            't_id'      => 'bail|required|max:2',
            't_url'     => 'bail|required|min:10|max:40',
            't_api_key' => 'bail|required|min:10|max:100',
        ]);
        $tracker = new TrackersSettingsModel();
        $tracker->t_id      = $request->t_id;
        $tracker->t_url     = $request->t_url;
        $tracker->t_api_key = $request->t_api_key;
        $tracker->save();
        $request->session()->flash('status', "Tracker {$request->endpoint_url} successfully added!");

        $username = Auth::user()->name;
        (new LeadCollectorMessages())->commitMessage([
            'title'   => "Tracker added by <b>{$username}</b>",
            'content' => "Tracker id: {$request->t_id}<br>URL: {$request->t_url}",
        ]);

        return redirect()->route('trackers.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Application|Factory|View
     */
    public function edit($id)
    {
        $tracker = TrackersSettingsModel::where('t_id', $id)->first()->toArray();

        return view('lead-collector.settings.tracker-settings.edit-tracker', ['tracker' => $tracker]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            't_id'      => 'bail|required|max:2',
            't_url'     => 'bail|required|min:10|max:40',
            't_api_key' => 'bail|required|min:10|max:100',
        ]);
        $tracker = TrackersSettingsModel::findOrFail($id);
        $tracker->t_id      = $request->t_id;
        $tracker->t_url     = $request->t_url;
        $tracker->t_api_key = $request->t_api_key;
        $tracker->save();
        $request->session()->flash('status', "Tracker {$request->endpoint_url} successfully updated!");

        $username = Auth::user()->name;
        (new LeadCollectorMessages())->commitMessage([
            'title'   => "Update by <b>{$username}</b>",
            'content' => "Tracker id: {$request->t_id}<br>URL: {$request->t_url}"
        ]);

        return redirect()->route('trackers.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
