<?php

namespace src\App\Services\DatabaseService;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use \PDO;
use Prophecy\Exception\Exception;
use src\App\Container\AppContainer;
use src\App\Services\FileJobService\FileJobManager;

final class QueryOperations
{
  /**
   * @var PDO
   */
  protected $pdo;

  /**
   * @var $table
   */
  protected $table;

  /**
   * @var string
   */
  private $archiveTable;

  /**
   * Add connection driver and needed table name.
   * DB constructor.
   *
   * @param PDO $pdo
   * @param $table
   */
  public function __construct(PDO $pdo, $table)
  {
    $this->table = $table;
    $this->pdo = $pdo;
    $this->archiveTable = "{$this->table}_archive";
  }

  /**
   * Inserting data to database. [DEV]
   *
   * @param array $data
   * @return bool
   */
  public function insert(array $data): bool
  {
    $keys = implode(', ', array_keys($data));
    $values = ':' . implode(', :', array_keys($data));
    $sql = "INSERT INTO {$this->table} ({$keys}) VALUES ({$values});";
    try {
      $statement = $this->pdo->prepare($sql);

      return $statement->execute($data);
    } catch (\Exception $exception) {
      $data['reason'] = $exception->getMessage();

      return false;
    } finally {
      $logDate = "insert-week-" . date('W-Y') . "-new-leads";
      (new FileJobManager())->write($data, "{$logDate}");
    }
  }

  /**
   * Updating data by id.
   * Lines 86, 87 for php 8+ str_replace() deprecated
   * @param array $data
   * @param array $condition
   * @return bool
   */
  public function update(array $data, array $condition): bool
  {
    $newForm = [];
    foreach ($condition as $item => $value) {
      if (!empty($item)) {
        $item = str_replace('\'', '', $item);
      }
      if (!empty($value)) {
        $value = str_replace('\'', '', $value);
      }
      $newForm[] = "{$item}='{$value}'";
    }
    $newForm = implode(' AND ', $newForm);
    $newData = [];
    foreach ($data as $key => $value) {
      if (!empty($key)) {
        $key = str_replace('\'', '', $key);
      }
      if (!empty($value)) {
        $value = str_replace('\'', '\`', $value);
      }
      $newData[] = "{$key}='{$value}'";
    }
    $newData = implode(', ', $newData);
    $sql = "UPDATE {$this->table} SET {$newData} WHERE {$newForm};";
    try {
      $statement = $this->pdo->prepare($sql);
      $statement->execute();

      return true;
    } catch (\Exception $exception) {
      $data['reason'] = $exception->getMessage();
      $logDate = "insert-week-" . date('W-Y') . "-trouble-leads";
      (new FileJobManager())->write($data, "{$logDate}");

      return false;
    }
  }

  /**
   * Selecting ALL data from table.
   * @param array|null $rows
   * @param string $orderBy
   * @return array|null
   */
  public function get(array $rows = null, string $orderBy = 'id'): ?array
  {
    $selection = (null == $rows) ? '*' : implode(', ', $rows);
    $stmt = $this->pdo->prepare("SELECT {$selection} FROM {$this->table} ORDER BY {$orderBy} DESC;");
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $result = $stmt->fetchAll();

    return ($result != false) ? $result : null;
  }

  /**
   * Selecting ONE OR MORE records with needed $id.
   * @param array|null $id
   * @param array|null $rows
   * @return array|null
   */
  public function getById(array $id = null, array $rows = null): ?array
  {
    $id = implode(', ', $id);
    $statement = (null == $rows) ? '*' : implode(', ', $rows);
    $stmt = $this->pdo->prepare("SELECT {$statement} FROM {$this->table} WHERE ID IN ({$id});");
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $result = $stmt->fetch();

    return ($result !== false) ? $result : null;
  }

  /**
   * Selecting record by needed row
   * @param array $byWhat
   * @param array|null $rows
   * @return mixed
   */
  public function getBy(array $byWhat, array $rows = null): ?array
  {
    $conditions = [];
    foreach ($byWhat as $id => $item) {
      $conditions[] = "{$id} LIKE '{$item}'";
    }
    $requiredCondition = implode(' AND ', $conditions);
    $statement = (null == $rows) ? '*' : implode(', ', $rows);
    $stmt = $this->pdo->prepare("SELECT {$statement} FROM {$this->table} WHERE {$requiredCondition};");
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $stmt->execute();
    $result = $stmt->fetch();

    return ($result !== false) ? $result : null;
  }

  public function customQuery(string $query, string $select): ?array
  {
    $query = "SELECT {$select} FROM {$this->table} WHERE {$query};";
    $stmt = $this->pdo->prepare($query);
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $stmt->execute();

    return $stmt->fetchAll();
  }


  /**
   * Setting is_deleted value to 1. (after this action only admin can access data)
   * @param array $item
   * @return bool
   */
  public function delete(array $item): bool
  {
    return $this->update(['is_deleted' => 0], $item);
  }

  /**
   * Removing data from table by id.
   * @param string $condition
   * @return bool
   * @throws \Exception
   */
  public function hardDelete(string $condition): bool
  {
    $query = "DELETE FROM {$this->table} WHERE {$condition};";
    $statement = $this->pdo->prepare($query);
    try {
      $statement->execute();

      return true;
    } catch (\Exception $e) {
      $logger = new Logger('Deleting');
      $logDate = date('Y-m-d');
      $l = AppContainer::get('path_to_logger');
      $logger->pushHandler(new StreamHandler("{$l}/log-{$logDate}.log", Logger::ERROR));
      $logger->error("\n" . $condition . $e->getMessage(), [$condition]);

      return false;
    }
  }

  /**
   * @param $clickId
   * @return bool
   */
  public function transferData($clickId): bool
  {
    $pdo = $this->pdo;
    $sql = "INSERT INTO {$this->archiveTable} (name, phone, conversion_status, lead_action_timestamp, click_id)
                SELECT name , phone , conversion_status , updated_at, click_id
                FROM {$this->table} WHERE click_id='{$clickId}';";
    $statement = $pdo->prepare($sql);

    return $statement->execute();
  }

  /**
   * Получаем данные трекера, которому принадлежит лид.
   * @param $trackerId
   * @return array|null
   */
  public function findTracker($trackerId): ?array
  {
    $sql = "SELECT t_id, t_url, t_api_key FROM trackers_settings_models WHERE t_id=\"{$trackerId}\"";
    $statement = $this->pdo->prepare($sql);
    $statement->execute();

    return $statement->fetchAll();
  }

  /**
   * Получаем данные партнёров, для отправки лидов.
   * @return array|null
   */
  public function getPartnerDataFromDB(): ?array
  {
    $sql = "SELECT partner_name,partner_provider,provider_class,api_key,endpoint FROM lead_collector_partners_settings;";
    $statement = $this->pdo->prepare($sql);
    $statement->execute();

    return $statement->fetchAll();
  }

  public function getPartnersData(): ?array
  {
    $sql = "SELECT t_id,t_url,t_api_key FROM trackers_settings_models;";
    $statement = $this->pdo->prepare($sql);
    $statement->execute();

    return $statement->fetchAll();
  }

  public function query(string $query)
  {
    $statement = $this->pdo->prepare($query);
    $statement->execute();
    return $statement->fetchAll();
  }
}