<?php

namespace App\Services;

use App\Models\LeadCollector;
use App\Models\LeadCollectorHistory;
use Illuminate\Http\Request;

final class MultipleLeadEditor
{
    /**
     * @var LeadCollector
     */
    protected $lc;
    /**
     * @var Request
     */
    protected $request;

    public function __construct(LeadCollector $leadCollector, Request $request)
    {
        $this->lc = $leadCollector;
        $this->request = $request;
    }

    public function editLeads($click_id_list, $new_data): bool
    {
        dd($click_id_list, $new_data);
    }

    public function resendEdited($click_id_list): bool
    {
        dd($click_id_list);
    }


    /**
     * @param Request $request
     * @return array
     */
    public function search(Request $request)
    {
        $perPage = $request->only(['pp']);
        $strongSearch = (!empty($request->only(['strong_search'])) &&
            $request->only(['strong_search'])['strong_search'] == 'on');

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
            't_id'
        ]);
        foreach ($request as $item => $value) {
            if (!empty($value)) {
                $payload[$item] = $value;
            }
        }

        return $this->getNeededLeads($request, $strongSearch, $perPage);
    }

    /**
     * @param $needed
     * @param null $strongSearch
     * @param null $pp
     * @return array
     */
    protected function getNeededLeads($needed, $strongSearch = null, $pp = null): array
    {
        $search = $this->lc->newQuery();
        foreach ($needed as $item => $value) {
            if (empty($value)) continue;
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
            $search->where(function ($query) use ($terms, $item, $strongSearch) {
                foreach ($terms as $term) {
                    if (true !== $strongSearch) {
                        $query->where($item, 'like', trim("%$term%"));
                    } else {
                        $query->where($item, '=', trim("$term"));
                    }
                }
            });
        }
        $search->where('is_deleted', '=', 0);
        $count = $search->count();

        $search = $search->orderByRaw('id DESC')->paginate($pp['pp'] ?? 50);

        return [
            'leads' => $search,
            'count' => $count
        ];
    }

    public function getHistory($clickid)
    {
        $data = LeadCollectorHistory::where('click_id', $clickid)->orderByRaw('id DESC')->get()->toArray();

        return [$data];
    }
}
