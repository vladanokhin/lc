<?php

namespace src\App\Services\DatabaseService;

use \PDO;

/**
 * Class DataBaseManager
 * @package src\App\Services\DatabaseService
 */
class DataBaseManager
{
    /**
     * @var PDO
     */
    private $pdo;

    /**
     * @var string
     */
    private $table;

    /**
     * @var QueryOperations
     */
    private $query;

    /**
     * @var string
     */
    private $leadBuffer;

    /**
     * DataBaseManager constructor.
     * @param PDO $pdo
     * @param string $table
     * @param string $leadBuffer
     */
    public function __construct(PDO $pdo, string $table, string $leadBuffer = 'scheduled_leads')
    {
        $this->pdo = $pdo;
        $this->table = $table;
        $this->leadBuffer = $leadBuffer;
        $this->query = new QueryOperations($pdo, $table);
    }

    /**
     * Use to configure existing database. Free to customization.
     * @return bool
     */
    public function configure(): bool
    {
        return (new DatabaseConfiguration($this->pdo, $this->table, $this->leadBuffer))->configure();
    }

    /**
     * Insert valid array of data into database. Validation must be done before this action!
     * @param array $data
     * @return bool
     */
    public function insert(array $data): bool
    {
        return $this->query->insert($data);
    }

    /**
     * Get any needed data from database by needed column.
     * @param array $column
     * @param array|null $rows
     * @return array
     */
    public function getBy(array $column, array $rows = null): ?array
    {
        return $this->query->getBy($column, $rows);
    }

    /**
     * @param string $query
     * @param string $select
     * @return array|null
     */
    public function customQuery(string $query, string $select = '*'): ?array
    {
        return $this->query->customQuery($query, $select);
    }

    public function query(string $query)
    {
        return $this->query->query($query);
    }

    /**
     * Ready query for getting data by id
     * @param array $id
     * @param array|null $rows
     * @return array
     */
    public function getById(array $id, array $rows = null): ?array
    {
        return $this->query->getById($id, $rows);
    }

    /**
     * Changing is_deleted to 1.
     * @param array $items
     * @return bool
     */
    public function delete(array $items): bool
    {
        return $this->query->delete($items);
    }

    /**
     * @param string $clickId
     * @return bool
     */
    public function sendLeadToArchive(string $clickId): bool
    {
        return $this->query->transferData($clickId);
    }

    /**
     * Removing record from database
     * @param array $conditions
     * @return bool
     * @throws \Exception
     */
    public function hardDelete(array $conditions = ['id' => [0, 1]]): bool
    {
        $statement = null;
        foreach ($conditions as $column => $condition) {
            $condition = array_map(function ($item) {
                return "'{$item}'";
            }, $condition);
            $condition = implode(', ', $condition);
            $statement = "{$column} IN ({$condition})";
        }

        return $this->query->hardDelete($statement);
    }

    /**
     * Updating needed array of data. First param = [$data], the second param = query conditions.
     * Usage example: $data = [user => new data], $condition = [id => 23]
     * @param array $data
     * @param array $condition
     * @return bool
     */
    public function update(array $data, array $condition): bool
    {
        return $this->query->update($data, $condition);
    }

    /**
     * @param int $trackerId
     * @return array|null
     */
    public function getTracker(int $trackerId): ?array
    {
        return $this->query->findTracker($trackerId);
    }

    /**
     * @return array|null
     */
    public function getPartnersData(): ?array
    {
        return $this->query->getPartnerDataFromDB();
    }

    public function getTrackers()
    {
        return $this->query->getPartnersData();
    }
}