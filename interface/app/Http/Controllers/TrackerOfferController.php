<?php

namespace App\Http\Controllers;

use App\Http\Requests\OfferRequest;
use App\Models\Offer;
use App\Models\TrackersSettingsModel;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TrackerOfferController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $trackers = TrackersSettingsModel::where('api_version', 'v2')->get(['id', 't_url']);
        $offers = Offer::paginate();

        return view('lead-collector.settings.offers.index', compact('trackers', 'offers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param OfferRequest $request
     * @return RedirectResponse
     */
    public function store(OfferRequest $request)
    {
        $offer = Offer::create($request->validated());
        $offer->trackers()->sync($request->get('trackers'));

        $request->session()->flash('message', "The offer \"$offer->name\" has been successfully added!");

        return response()->redirectToRoute('offers.index');
    }

    /**
     * Display the specified resource.
     *
     * @param Offer $offer
     * @return Application|Factory|View
     */
    public function show(Offer $offer)
    {
        $trackers = TrackersSettingsModel::where('api_version', 'v2')->get(['id', 't_url']);
        $offers = Offer::paginate();

        return view('lead-collector.settings.offers.edit', compact('trackers', 'offers', 'offer'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param OfferRequest $request
     * @param Offer $offer
     * @return RedirectResponse
     */
    public function update(OfferRequest $request, Offer $offer)
    {
        $offer->update($request->validated());
        $offer->trackers()->sync($request->get('trackers'));

        $request->session()->flash('message', "The offer \"$offer->name\" has been successfully updated!");

        return response()->redirectToRoute('offers.show', $offer->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param Offer $offer
     * @return RedirectResponse
     */
    public function destroy(Request $request, Offer $offer)
    {
        $request->session()->flash('message', "The offer \"$offer->name\" has been successfully deleted!");
        $offer->delete();

        return response()->redirectToRoute('offers.index');
    }
}
