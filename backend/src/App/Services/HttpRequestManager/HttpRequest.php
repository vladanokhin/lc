<?php

namespace src\App\Services\HttpRequestManager;

class HttpRequest
{
  private static $get = [];
  private static $post = [];

  public function __construct()
  {
    $this->get = $this->prepareGetRequest($_GET);
    $this->post = $this->preparePostRequest($_POST);
  }

  public static function getMethodData()
  {
    return self::$get;
  }

  public static function postMethodData()
  {
    return self::$post;
  }

  public function prepareGetRequest($request)
  {
    return $request;
  }

  public function preparePostRequest($request)
  {
    return $request;
  }
}