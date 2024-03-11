<?php

namespace App\Http\Controllers;

use App\Models\LeadCollector;
use App\Models\LeadCollectorHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DataSearchController extends Controller
{
    public function search(Request $request)
    {
        $request = $request->only([
            'click_id',
            'aff_network_name',
            'conversion_status',
            'country_code',
            'offer_id',
            'offer_name',
            'created_at_from',
            'created_at_to',
            'per_page',
        ]);

        $payload = [];
        foreach ($request as $item => $value) {
            if (!empty($value)) {
                $payload[$item] = $value;
            }
        }
        if (empty($payload)) {
            return $this->getLast();
        }

        return $this->getNeededLeads($request);
    }

    protected function getLast()
    {
        $lc = new LeadCollector();
        $search = $lc->newQuery();
        $leads = $search->where('is_deleted', '=', 0)
            ->orderByDesc('id')
            ->paginate(50);

        return [
            'leads' => $leads,

            'count' => DB::connection('mysql_lc')->table('leads')
                ->where('is_deleted', '=', 0)
                ->count(),
        ];
    }

    protected function getNeededLeads($needed)
    {
        $lc = new LeadCollector();
        $search = $lc->newQuery();

        foreach ($needed as $item => $value) {
            if (empty($value)) {
                continue;
            }
            if ($item == 'created_at_from') {
                $date = date('Y-m-d H:i:s', strtotime($value));
                $search->where('created_at', '>', $date);
                continue;
            }
            if ($item == 'created_at_to') {
                $date = date('Y-m-d H:i:s', strtotime('+23 hour +59 minutes +59 seconds', strtotime($value)));
                $search->where('created_at', '<=', $date);
                continue;
            }
            $terms = explode(',', $value);
            $search->where(function ($query) use ($terms, $item) {
                foreach ($terms as $term) {
                    $query->orWhere($item, 'like', trim("%$term%"));
                }
            });
        }
        $search->where('is_deleted', '=', 0);
        $count = $search->count();

        $search = $search->orderByRaw('id DESC')->paginate($needed['per_page'] ?? 38);

        return [
            'leads' => $search,
            'count' => $count,
        ];
    }

    public function downloadAllLeads()
    {
        $lc = new LeadCollector();
        $search = $lc->newQuery();
        $result = $search->where('is_deleted', '=', 0)
            ->orderByDesc('id')
            ->get()
            ->toArray();

        return ['leads' => $result];
    }

    public function downloaderSearch(Request $request)
    {
        $request = $request->only([
            'click_id',
            'aff_network_name',
            'conversion_status',
            'country_code',
            'offer_id',
            'offer_name',
            'created_at_from',
            'created_at_to',
        ]);
        $payload = [];
        foreach ($request as $item => $value) {
            if (!empty($value)) {
                $payload[$item] = $value;
            }
        }
        if (empty($payload)) {
            return $this->downloadAllLeads();
        }

        return $this->downloadNeededLeads($request);
    }

    protected function downloadNeededLeads($needed)
    {
        $lc = new LeadCollector();
        $search = $lc->newQuery();

        foreach ($needed as $item => $value) {
            if (empty($value)) {
                continue;
            }
            if ($item == 'created_at_from') {
                $date = date('Y-m-d H:i:s', strtotime($value));
                $search->where('created_at', '>', $date);
                continue;
            }
            if ($item == 'created_at_to') {
                $date = date('Y-m-d H:i:s', strtotime('+23 hour +59 minutes +59 seconds', strtotime($value)));
                $search->where('created_at', '<=', $date);
                continue;
            }
            $terms = explode(',', $value);
            $search->where(function ($query) use ($terms, $item) {
                foreach ($terms as $term) {
                    $query->orWhere($item, 'like', trim("%$term%"));
                }
            });
        }
        $search->where('is_deleted', '=', 0);

        $search = $search->orderByRaw('id DESC')->get();

        return [
            'leads' => $search
        ];
    }
}
