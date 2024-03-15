<?php

namespace App\Http\Controllers;

use App\Models\TrackersSettingsModel;
use App\Services\BinomApiVersion;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

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
        $apiVersions = BinomApiVersion::getVersions();
        return view(
            'lead-collector.settings.tracker-settings.add-tracker',
            compact('apiVersions')
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $username = Auth::user()->name;
        $data = $request->validate([
            't_id'        => 'bail|required|max:2|unique:mysql_lc.trackers_settings_models,t_id',
            't_url'       => 'bail|required|min:10|max:40|unique:mysql_lc.trackers_settings_models,t_url',
            't_api_key'   => 'bail|required|min:10|max:100|unique:mysql_lc.trackers_settings_models,t_api_key',
            'api_version' => ['bail', 'required', Rule::in(BinomApiVersion::getVersions())],
        ]);

        TrackersSettingsModel::create($data);
//        $request->session()->flash('status', "Tracker {$request->endpoint_url} successfully added!");

        (new LeadCollectorMessages())->commitMessage([
            'title'   => "Tracker added by <b>{$username}</b>",
            'content' => "Tracker id: {$request->t_id}<br>URL: {$request->t_url}",
        ]);

        return redirect()->route('trackers.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Application|Factory|View
     */
    public function edit($id)
    {
        $apiVersions = BinomApiVersion::getVersions();
        $tracker = TrackersSettingsModel::where('t_id', $id)->first()->toArray();

        return view(
            'lead-collector.settings.tracker-settings.edit-tracker',
            compact('tracker', 'apiVersions')
        );
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
        $username = Auth::user()->name;
        $tracker = TrackersSettingsModel::findOrFail($id);

        $data = $request->validate([
            't_id'        => ['bail', 'required', 'max:2', Rule::unique('mysql_lc.trackers_settings_models', 't_id')->ignoreModel($tracker)],
            't_url'       => ['bail', 'required', 'min:10', 'max:40', Rule::unique('mysql_lc.trackers_settings_models', 't_url')->ignoreModel($tracker)],
            't_api_key'   => ['bail', 'required', 'min:10', 'max:100', Rule::unique('mysql_lc.trackers_settings_models', 't_api_key')->ignoreModel($tracker)],
            'api_version' => ['bail', 'required', Rule::in(BinomApiVersion::getVersions())],
        ]);

        $tracker->update($data);

        (new LeadCollectorMessages())->commitMessage([
            'title'   => "Update by <b>{$username}</b>",
            'content' => "Tracker id: {$request->t_id}<br>URL: {$request->t_url}"
        ]);

        return redirect()->route('trackers.index');
    }
}
