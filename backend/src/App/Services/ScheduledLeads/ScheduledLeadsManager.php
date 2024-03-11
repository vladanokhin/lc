<?php

namespace src\App\Services\ScheduledLeads;

use src\App\Container\AppContainer;
use src\App\Services\DatabaseService\Connection;
use src\App\Services\DatabaseService\DataBaseManager;

final class ScheduledLeadsManager
{
    private $scheduledLeads;

    private $queue = [];

    public function __construct()
    {
        $this->scheduledLeads = new DataBaseManager(
            (new Connection())->make(), AppContainer::get('table_for_scheduled_leads'));
    }

    /**
     * Shaping data to send
     *
     * @param array $data
     * @return bool
     */
    public function addToScheduled(array $data): bool
    {
        $data = $this->detachData($data);
        $lead = $this->scheduledLeads->getBy(['click_id' => $data['click_id']]);
        if (null === $lead) {
            return $this->scheduledLeads->insert($data);
        }
        return $this->scheduledLeads->update($data, ['click_id' => $data['click_id']]);
    }

    /**
     * @param $id
     */
    public function fillTheQueue($id): void
    {
        array_push($this->queue, $id);
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function removeFromScheduled(): ?bool
    {
        return (!empty($this->queue)) ? $this->scheduledLeads->hardDelete(['id' => $this->queue]) : null;
    }

    /**
     * avoid array
     */
    public function clearQueue(): void
    {
        $this->queue = [];
    }

    /**
     * @param array $newData
     * @return bool
     */
    public function updateScheduledLead(array $newData): bool
    {
        $newData = $this->detachData($newData);

        return $this->scheduledLeads->update($newData, ['click_id' => $newData['click_id']]);
    }

    /**
     * @param array $payload
     * @return array
     */
    private function detachData(array $payload): array
    {
        $requireKeys = [
            'aff_network_name',
            'click_id',
            'name',
            'phone',
            'second_phone',
            'country_code',
            'offer_id',
            'offer_name',
            't_id',
            'product',
            'data_1',
            'data_2',
            'data_3',
            'second_phone',
            'second_number',
        ];
        $result = [];
        foreach ($requireKeys as $i => $require) {
            if (array_key_exists($require, $payload)) {
                $result[$require] = $payload[$require];
            }
        }

        return $result;
    }

    /**
     * @param string $query
     * @param string $select
     * @return array|null
     */
    public function customQuery(string $query, string $select): ?array
    {
        return $this->scheduledLeads->customQuery($query, $select);
    }

    /**
     * @param string $clickid
     * @throws \Exception
     */
    public function removeReorderedFromScheduled(string $clickid): void
    {
      $this->scheduledLeads->hardDelete(['click_id' => [$clickid]]);
    }

    /**
     * @param string $clickId
     * @throws \Exception
     */
    public function hardRemoveFromQueue(string $clickId): void
    {
        $this->scheduledLeads->hardDelete(['click_id' => $clickId]);
    }
}