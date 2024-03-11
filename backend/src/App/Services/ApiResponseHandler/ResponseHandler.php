<?php

namespace src\App\Services\ApiResponseHandler;

use src\App\Container\AppContainer;
use src\App\Services\DatabaseService\Connection;
use src\App\Services\DatabaseService\DataBaseManager;

class ResponseHandler
{
  // Create DB connection: table - AppContainer-lead_responses_frontend
  /**
   * @var string
   */
  private $connection;

  public function __construct()
  {
    $this->connection = new DataBaseManager(
      (new Connection())->make(),
      AppContainer::get('lead_responses_frontend')
    );
  }

  public function prepare(string $payload): string
  {
    $decoded = json_decode($payload, true);
    if (false === $decoded) {
      $result = $payload;
    } else {
      $result = $this->handleArrayResponse(json_decode($payload, true));
    }
    return $result;
  }

  public function commitResponseToDatabase(string $response, string $click_id): bool
  {
    $payload = [];
    $payload['click_id'] = $click_id;
    $invalid_characters = ["$", "%", "#", "<", ">", "|", "\"", "'"];
    $payload['response_text'] = str_replace($invalid_characters, "", $response);

    return $this->connection->insert($payload);
  }

  private function handleArrayResponse(array $payload): string
  {
    $result = '';
    foreach ($payload as $key => $value) {
      if (is_array($value)) {
        $value = $this->convertArrayToString($value);
      }
      $result .= "$key - $value\n\r";
    }
    return $result;
  }

  private function convertArrayToString(array $array): string
  {
    $result = '';
    foreach ($array as $k => $v) {
      if (!is_array($v)) {
        $result .= " $k: $v; ";
        continue;
      }
      $result .= "\n\t$k =>" . $this->convertArrayToString($v);
    }
    return $result;
  }
}
