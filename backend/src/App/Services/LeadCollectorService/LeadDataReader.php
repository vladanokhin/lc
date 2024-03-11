<?php

namespace src\App\Services\LeadCollectorService;

use src\App\Container\AppContainer;
use src\App\Services\DatabaseService\Connection;
use src\App\Services\DatabaseService\DataBaseManager;

class LeadDataReader
{
    /**
     * @var $query
     */
    protected $query;

    public function __construct($query = null)
    {
        $this->query = (null != $query) ?
            $query : new DataBaseManager(Connection::make(), AppContainer::get('table_for_leads'));
    }

    /**
     * Returns array with only needed lead or returns null
     *
     * @param string $clickId
     * @param array|null $rows
     * @return array
     */
    public function getLeadByClickId(string $clickId, array $rows = null): ?array
    {
        return $this->query->getBy(['click_id' => $clickId], $rows);
    }

    /**
     * Returns array with needed lead or returns null
     *
     * @param string $unique
     * @param array|null $rows
     * @return array
     */
    public function getLeadByUniqueId(string $unique, array $rows = null): ?array
    {
        return $this->query->getBy(['unique_id' => $unique], $rows);
    }
}