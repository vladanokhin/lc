<?php

namespace App\Http\Controllers;

use App\Models\LeadCollector;
use App\Models\PartnersProvidersModel;
use App\Models\TrackersSettingsModel;
use Illuminate\Http\Request;

class LeadsStatisticsAjax extends Controller
{
    public function index()
    {
        return view('lead-collector.statistics-ajax.index', [
            'data' => [
                'partners' => $this->getPartners(),
                'trackers' => $this->getTrackers(),
                'colors' => $this->boardsColors(),
                'statistics' => $this->totalCounts(),
            ],
        ]);
    }

    public function findByFilter(Request $request)
    {
        $data = $request->only([
            'from',
            'to',
            'partner',
            'tracker',
        ]);
        $result = [];
        $payload = [];
        $filteredUnsortedLeads = LeadCollector::where(function ($query) use ($data) {
            if (null != $data['from']) {
                $query->whereDate('created_at', '>=', "{$data['from']} 00:00:00");
            }
            if (null != $data['to']) {
                $query->whereDate('created_at', '<=', "{$data['to']} 23:59:59");
            }
            if (null != $data['partner']) {
                $query->where('aff_network_name', ($data['partner']));
            }
            if (isset($data['tracker']) && null != $data['tracker']) {
                $query->where('t_id', ($data['tracker']));
            }
        })->select('conversion_status')->get()->toArray();
        foreach ($filteredUnsortedLeads as $key => $lead) {
            if (!isset($payload[$lead['conversion_status']])) {
                $payload[$lead['conversion_status']] = 1;
            } else {
                $payload[$lead['conversion_status']] += 1;
            }
        }
        $result[$data['partner']] = $payload;

        return response()->json($result);
    }

    public function totalCounts()
    {
        $result = [];
        $payload = LeadCollector::whereDate('created_at', '>=', date("Y-m-d H:i:s", strtotime('-24 hours', time())))
            ->select(['conversion_status', 'aff_network_name'])->get()->toArray();
        foreach ($payload as $id => $status_list) {
            if (!isset($result[$status_list['aff_network_name']][$status_list['conversion_status']])) {
                $result[$status_list['aff_network_name']][$status_list['conversion_status']] = 1;
            } else {
                $result[$status_list['aff_network_name']][$status_list['conversion_status']] += 1;
            }
        }
        ksort($result);
        return $result;
    }

    private function getPartners(): array
    {
        $result = [];
        $partners = PartnersProvidersModel::all('partner_name')->toArray();
        foreach ($partners as $k => $partner) {
            $result[] = $partner['partner_name'];
        }
        return $result;
    }

    /**
     * Available colors for boards. (tailwind colors)
     * @return string[]
     */
    private function boardsColors(): array
    {
        return [
            'Gray', 'Red', 'Yellow', 'Green', 'Blue', 'Indigo', 'Purple', 'Pink',
        ];
    }

    private function getTrackers(): array
    {
        $result = [];
        $trackers = TrackersSettingsModel::all()->toArray();
        foreach ($trackers as $k => $tracker) {
            $result[$tracker['t_url']] = [
                't_id' => $tracker['t_id'],
                't_url' => $tracker['t_url']
            ];
        }
        return $result;
    }
}
