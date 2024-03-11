<?php

namespace App\Http\Controllers;

use App\Models\PartnersProvidersModel;
use App\Models\StatusSchemeModel;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PartnerProviderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index()
    {
        return view('lead-collector.settings.partners-providers.partners-dashboard', [
            'data' => PartnersProvidersModel::all()->sort()->toArray()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function create()
    {
        return view('lead-collector.settings.partners-providers.add-partner');
    }

    public function implementStatusScheme()
    {
        return view('lead-collector.settings.partners-providers.add-partner-status-scheme');
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
            'partner_name'     => 'bail|required|min:2|max:100',
            'partner_provider' => 'bail|required|min:2|max:100',
            'provider_class'   => 'bail|required|min:2|max:100',
            'endpoint'         => 'bail|required|min:2|max:100',
            'api_key'          => 'bail|max:120',
        ]);
        $partner = new PartnersProvidersModel();
        $partner->partner_name     = $request->partner_name;
        $partner->partner_provider = $request->partner_provider;
        $partner->provider_class   = $request->provider_class;
        $partner->api_key          = $request->api_key;
        $partner->endpoint         = $request->endpoint;
        $partner->save();
        $request->session()->flash('status', "Partner {$request->provider_name} successfully added!");

        return redirect()->route('partner-providers.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Application|Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function edit($id)
    {
        $partner = PartnersProvidersModel::where('id', $id)->first()->toArray();

        return view('lead-collector.settings.partners-providers.edit-partner', ['partner' => $partner]);
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
            'partner_name'     => 'bail|required|min:2|max:100',
            'partner_provider' => 'bail|required|min:2|max:100',
            'provider_class'   => 'bail|required|min:2|max:100',
            'endpoint'         => 'bail|required|min:2|max:100',
            'api_key'          => 'bail|max:120',
        ]);
        $partner = PartnersProvidersModel::findOrFail($id);

        $before = json_decode($partner, true);
        $startData = "<div class='grid grid-cols-5 gap-3'>";
        foreach ($before as $key => $value) {
            if ($key == 'created_at' || $key == 'updated_at' || $key == 'id') continue;
            $startData .= "<div class='col-span-2'>{$key}</div><div class='col-span-1'>=></div><div class='col-span-2'>{$value}</div>";
        }
        $startData .= "</div>";

        $partner->partner_name     = $request->partner_name;
        $partner->partner_provider = $request->partner_provider;
        $partner->provider_class   = $request->provider_class;
        $partner->api_key          = $request->api_key;
        $partner->endpoint         = $request->endpoint;
        $partner->save();
        $request->session()->flash('status', "Partner {$request->provider_name} successfully updated!");

        $after = PartnersProvidersModel::findOrFail($id);
        $after = json_decode($after, true);
        $string = "<div class='grid grid-cols-5 gap-3'>";
        foreach ($after as $key => $value) {
            if ($key == 'created_at' || $key == 'updated_at' || $key == 'id') continue;
            $string .= "<div class='col-span-2'>{$key}</div><div class='col-span-1'>=></div><div class='col-span-2'>{$value}</div>";
        }
        $string .= "</div>";

        $username = Auth::user()->name;
        (new LeadCollectorMessages())->commitMessage([
            'title'   => "Partner provider settings: <b>{$request->partner_name}</b>",
            'content' => "<span class='text-center text-xl'>Update by <b>{$username}</b></span><br>Before<br>{$startData}<br><span class='text-center'></span><br><br>After<br>{$string}"
        ]);
        return redirect()->route('partner-providers.index');
    }
}
