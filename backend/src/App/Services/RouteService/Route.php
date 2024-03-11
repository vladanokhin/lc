<?php

namespace src\App\Services\RouteService;

use Exception;

class Route
{
    /**
     * @var AltoRouterService
     */
    private $router;

    /**
     * Initializing new AltoRouterService
     * Route constructor.
     */
    public function __construct()
    {
        $this->router = new AltoRouterService();
    }

    /**
     * Registering GET method route
     * @param $url
     * @param $handler
     * @param null $name
     * @throws Exception
     */
    public function get($url, $handler, $name = null)
    {
        $this->router->map('GET', $url , $handler, $name);
    }

    /**
     * Registering POST method route
     * @param $url
     * @param $handler
     * @param null $name
     * @throws Exception
     */
    public function post($url, $handler, $name = null)
    {
        $this->router->map('POST', $url, $handler, $name);
    }

    /**
     * Registering PUT method route
     * @param $url
     * @param $handler
     * @param null $name
     * @throws Exception
     */
    public function put($url, $handler, $name = null)
    {
        $this->router->map('PUT', $url, $handler, $name);
    }

    /**
     * Registering DELETE method route
     * @param $url
     * @param $handler
     * @param null $name
     * @throws Exception
     */
    public function delete($url, $handler, $name = null)
    {
        $this->router->map('DELETE', $url, $handler, $name);
    }

    /**
     * @param string $url
     */
    public function setBaseUrl(string $url): void
    {
        $this->router->setBaseUrl($url);
    }

    /**
     * Checking if link has Controller to be processed
     * @param string|null $requestUrl
     * @param string|null $requestMethod
     * @return array|bool
     */
    public function match(string $requestUrl = null, string $requestMethod = null)
    {
        $matches = $this->router->match($requestUrl, $requestMethod);
        if (!$matches) {
            http_response_code(404);
            exit();
        }

        return $matches;
    }

}