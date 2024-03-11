<?php

namespace src\App\Services\LeadCollectorService;

use src\App\Container\AppContainer;
use src\App\Services\DatabaseService\DataBaseManager;

class StatusScheme
{

  const DEFAULT_SCHEME = [
    'status_locked' => 0, 'accept_event_2' => 1, 'accept_payment' => 1, 'weight' => -1,
  ];

  /**
   * @var DataBaseManager
   */
  private $connection;

  public function __construct(DataBaseManager $connection)
  {
    $this->connection = $connection;
  }

  /**
   * @param string $partner
   * @param string $desiredStatus
   * @return array|null
   * @throws \Exception
   */
  public function getStatusScheme(string $partner, string $desiredStatus): ?array
  {
    $innerStatuses = AppContainer::get('status_category_frontend');
    $incomeStatuses = AppContainer::get('income_status_frontend');
    $incomeJoinInnerQuery = "SELECT * FROM {$incomeStatuses} LEFT JOIN {$innerStatuses} ON
        {$innerStatuses}.ad2lynx_statuses_id={$incomeStatuses}.status_id
         WHERE {$incomeStatuses}.partner_name='{$partner}';";
    $queryResult = $this->connection->query($incomeJoinInnerQuery);
    foreach ($queryResult as $_ => $statusScheme) {
      if (in_array($desiredStatus, $statusScheme)) {
        return $queryResult;
      }
    }
    return null;
  }

  public function handlePostbackAccordingToStatusScheme($statusScheme, $postback, $currentLead): ?array
  {
    $currentLeadStatus = $currentLead['conversion_status'];
    $incomeLeadStatus = $postback['cnv_status'];
    $availableStatusRegisteredInStatusScheme = false;
    $incomeStatusRegisteredInStatusScheme = false;
    $availableLeadScheme = null;
    $incomeLeadScheme = null;
    foreach ($statusScheme as $_ => $scheme) {
      if (in_array($currentLeadStatus, $scheme)) {
        $availableStatusRegisteredInStatusScheme = true;
        $availableLeadScheme = $currentLead;
        $availableLeadScheme['scheme'] = $scheme;
      }
      if (in_array($incomeLeadStatus, $scheme)) {
        $incomeStatusRegisteredInStatusScheme = true;
        $incomeLeadScheme = $postback;
        $incomeLeadScheme['scheme'] = $scheme;
      }
    }
    if (!$incomeStatusRegisteredInStatusScheme || !$availableStatusRegisteredInStatusScheme) {
      if (!isset($availableLeadScheme['scheme'])) {
        $availableLeadScheme['scheme'] = self::DEFAULT_SCHEME;
      }
      if (!isset($incomeLeadScheme['scheme'])) {
        $incomeLeadScheme['scheme'] = self::DEFAULT_SCHEME;
      }
    }
    $incomeLeadScheme = $this->filterAvailableActions($incomeLeadScheme);
    if ($this->compareAvailabilityToChangeStatus($availableLeadScheme, $incomeLeadScheme)) {
      return $incomeLeadScheme;
    }
    return null;
  }

  private function compareAvailabilityToChangeStatus(array $availableStatus, array $incomeStatus): bool
  {
    if ($availableStatus['scheme']['status_locked'] === 0) {
      if ($availableStatus['scheme']['weight'] <= $incomeStatus['scheme']['weight']) {
        return true;
      }
    }
    return false;
  }

  private function filterAvailableActions(array $postback): array
  {
    $postback['cnv_status2'] = $postback['scheme']['status_category'];
    if ($postback['scheme']['add_event_2'] === 1) {
      $postback['event2'] = 1;
    }
    if (isset($postback['payout'])) {
      $postback['payout'] = ($postback['scheme']['accept_payment'] === 1) ? $postback['payout'] : null;
    }
    return $postback;
  }

}