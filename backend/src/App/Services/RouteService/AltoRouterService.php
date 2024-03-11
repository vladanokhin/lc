<?php

namespace src\App\Services\RouteService;

use AltoRouter;
use Exception;

class AltoRouterService
{
    private $router;

    public function __construct()
    {
        $this->router = new AltoRouter();
    }

    /**
     * @param $method
     * @param $route
     * @param $target
     * @param null $name
     * @throws Exception
     */
    public function map($method, $route, $target, $name = null): void
    {
        $route = '/' . trim($route, '/');
        $this->router->map($method, $route, $target, $name);
    }

    /**
     * @param string $url
     */
    public function setBaseUrl(string $url): void
    {
        $this->router->setBasePath($url);
    }

    /**
     * @param string|null $requestUrl
     * @param string|null $requestMethod
     * @return array|bool
     */
    public function match(string $requestUrl = null, string $requestMethod = null)
    {
        return $this->router->match($requestUrl, $requestMethod);
    }
}