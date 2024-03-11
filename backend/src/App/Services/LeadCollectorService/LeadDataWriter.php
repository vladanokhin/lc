<?php

namespace src\App\Services\LeadCollectorService;

use Exception;
use src\App\Services\DatabaseService\DataBaseManager;

class LeadDataWriter
{
    /**
     * @var DataBaseManager
     */
    private $query;

    public function __construct($query)
    {
        $this->query = $query;
    }

    /**
     * Writing data to DB
     *
     * @param array $data
     * @return bool
     * @throws Exception
     */
    public function moveToDatabase(array $data): bool
    {
        $preparedArray = (new LeadDataHandler())->weedOutData($data);
        return $this->query->insert($preparedArray);
    }

    /**
     * @param array $lead
     * @return bool
     */
    public function transferLeadToArchive(array $lead): bool
    {
        return $this->query->sendLeadToArchive($lead['click_id']);
    }

    /**
     * Updating lead
     *
     * @param array $data
     * @param array $clickId
     * @return bool
     */
    public function updateLeadInfo(array $data, array $clickId): bool
    {
        return $this->query->update($data, $clickId);
    }
}