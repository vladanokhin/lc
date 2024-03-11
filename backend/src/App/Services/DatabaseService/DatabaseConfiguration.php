<?php

namespace src\App\Services\DatabaseService;

use \PDO;

final class DatabaseConfiguration
{
  private $pdo;
  private $table;
  private $preparation_table;

  /**
   * DatabaseConfiguration constructor.
   * @param PDO $pdo
   * @param $table_leads
   * @param $table_preparation_table
   */
  public function __construct(PDO $pdo, $table_leads, $table_preparation_table)
  {
    $this->pdo = $pdo;
    $this->table = $table_leads;
    $this->preparation_table = $table_preparation_table;
  }

  /**
   * @return bool
   */
  public function configure(): bool
  {
    echo '<pre>';
    if (false === $this->setup($this->table)) {
      return false;
    }
    if (false === $this->update($this->table)) {
      return false;
    }
    if (false === $this->createArchiveTable($this->table)) {
      return false;
    }
    if (false === $this->createPreparationTable($this->preparation_table)) {
      return false;
    }
    echo '</pre>';

    return true;
  }

  /**
   * @param $table
   * @return false|mixed
   */
  public function setup($table)
  {
    $query = "CREATE TABLE IF NOT EXISTS {$this->table} (
                id                  int auto_increment primary key,
                aff_network_name    varchar(150) DEFAULT '(>_<) Ancient goblin',
                click_id            varchar(250) not null,
                name                varchar(200) not null,
                phone               varchar(25) not null,
                conversion_status   varchar(150),
                unique_id           varchar(30) not null,
                country_code        varchar(50),
                offer_id            varchar(100),
                offer_name          varchar(250),
                user_email          varchar(230),
                second_phone        varchar(50),
                t_id                int,
                product             text,
                data_1              text,
                data_2              text,
                data_3              text,
                is_sent             int DEFAULT '0',
                updated_at          DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                created_at          TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                is_deleted          varchar(1) DEFAULT 0,
                inner_lead_status   varchar(1) DEFAULT 0
                ) ENGINE InnoDB;";

    return $this->rollout(
      $this->pdo->prepare($query), "CREATE TABLE IF NOT EXISTS `{$table}`", [
      'method' => __METHOD__,
      'file' => __FILE__,
      'line' => __LINE__
    ]);
  }

  /**
   * Update!
   * Because of new status system we must change available quantity of chars.
   *
   * @param $table
   * @return bool
   */
  public function update($table): bool
  {
    $query = "ALTER TABLE {$table}
    modify column aff_network_name    varchar(230) DEFAULT '(>_<) Ancient goblin',
    modify column click_id            varchar(230) unique not null,
    modify column name                varchar(230) not null,
    modify column phone               varchar(230) not null,
    modify column conversion_status   varchar(230),
    modify column unique_id           varchar(230) not null,
    modify column country_code        varchar(230),
    modify column offer_id            varchar(230),
    modify column offer_name          varchar(230),
    modify column t_id                int,
    modify column product             text,
    modify column data_1              text,
    modify column data_2              text,
    modify column data_3              text,
    modify column updated_at          DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    modify column created_at          TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    modify column is_deleted          tinyint DEFAULT 0
    ";

    return $this->rollout($this->pdo->prepare($query), "ALTER TABLE `{$table}`", [
      'method' => __METHOD__,
      'file' => __FILE__,
      'line' => __LINE__
    ]);
  }

  /**
   * @param $table
   * @return bool
   */
  public function createArchiveTable($table): bool
  {
    $archiveTableName = "{$table}_archive";
    $query = "CREATE TABLE IF
        NOT EXISTS {$archiveTableName} (
            id                      INT auto_increment PRIMARY KEY,
            name                    VARCHAR (250),
            phone                   VARCHAR (250),
            click_id                VARCHAR (250) NOT NULL,
            lead_action_timestamp   TIMESTAMP,
            conversion_status       VARCHAR (150),
            created_at              TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at              DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE InnoDB;";

    return $this->rollout(
      $this->pdo->prepare($query), "CREATE TABLE IF NOT EXISTS `{$archiveTableName}`", [
      'method' => __METHOD__,
      'file' => __FILE__,
      'line' => __LINE__
    ]);
  }

  public function createPreparationTable(string $table): bool
  {
    $query = "CREATE TABLE IF
        NOT EXISTS {$table} (
            id                  int auto_increment primary key,
            aff_network_name    varchar(250),
            click_id            varchar(250) unique,
            name                varchar(250),
            phone               varchar(250),
            second_phone        varchar(250),
            country_code        varchar(250),
            offer_id            varchar(250),
            offer_name          varchar(250),
            t_id                varchar(250),
            product             text,
            data_1              text,
            data_2              text,
            data_3              text,
            created_at          TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE InnoDB;";

    return $this->rollout($this->pdo->prepare($query), "CREATE TABLE IF NOT EXISTS `{$table}`",
      [
        'method' => __METHOD__,
        'file' => __FILE__,
        'line' => __LINE__
      ]);
  }

  public function createStatusSchemeTable($table): bool
  {
    $query = "CREATE TABLE IF
        NOT EXISTS {$table} (
            id                      int auto_increment PRIMARY KEY,
            partner_name            varchar (250),
            status_schema           text,
            created_at              TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at              DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE InnoDB;";

    return $this->rollout(
      $this->pdo->prepare($query), "CREATE TABLE IF NOT EXISTS `{$table}`", [
      'method' => __METHOD__,
      'file' => __FILE__,
      'line' => __LINE__
    ]);
  }

  private function rollout($statement, string $action, array $fileInfo)
  {
    try {
      echo "\n {$action} \n";
      return $statement->execute();
    } catch (\Exception $e) {
      echo "<b><hr><center>" . $e->getMessage() . "</center><hr></b>" . PHP_EOL;
      echo PHP_EOL . "<b>In method: </b> " . $fileInfo['method'] . PHP_EOL . "<b>file:</b> {$fileInfo['file']}, " .
        PHP_EOL . "<b>line:</b> {$fileInfo['line']}" . PHP_EOL
        . "<b>ERROR -> " . $e->getMessage() . "</b>"
        . PHP_EOL;
      echo $e->getTraceAsString();

      return false;
    }
  }
}