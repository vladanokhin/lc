<?php

namespace App\Services;

use App\Models\Ad2lynx_statuses;
use App\Models\LeadCollector;
use App\Models\LeadCollectorHistory;
use App\Services\StatusSchemeService\AddStatusCategoryDataToLead;
use Illuminate\Http\Request;
use Illuminate\Database\Query\Builder;

final class SearchService
{
  /**
   * @var LeadCollector
   */
  protected $lc;

  public function __construct(LeadCollector $leadCollector, Request $request)
  {
    $this->lc = $leadCollector;
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
      't_id',
      'with_email',
    ]);

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
          if ($item == 'click_id') {
            foreach ($terms as &$v) {
              $v = trim($v, ' ');
            }
            $query->whereIn('click_id', $terms);
            continue;
          }

          if ($item == 'with_email') {
            if ($term == 'on') {
              $query->whereNotNull('user_email');
            }
            continue;
          }

          if (true !== $strongSearch) {
            $query->where($item, 'like', trim("%$term%"));
          } else {
            $query->where($item, '=', trim("$term"));
          }
        }
      });
    }
    $count = $search->count();
    $search = $search->orderByRaw('id DESC')->paginate($pp['pp'] ?? 30);
    $statuses = new Ad2lynx_statuses();
    $addedData = new AddStatusCategoryDataToLead();
    $addedData->addData($search);

    return [
      'status_scheme' => $statuses,
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
