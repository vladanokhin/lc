<?php

namespace src\App\Services\ApiService;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

class ApiClient
{

    /**
     * Headers for request
     *
     * @var array $headers
     */
    private static array $headers = [];

    /**
     * Send get request
     *
     * @param string $uri
     * @param array $query
     * @return ResponseInterface
     * @throws GuzzleException
     */
    public static function get(string $uri, array $query = []): ResponseInterface
    {
        return self::sendRequest('GET', $uri, [ApiDataType::QUERY => $query]);
    }

    /**
     * Send post request
     *
     * @param string $uri
     * @param array $data
     * @param string $type
     * @return ResponseInterface
     * @throws GuzzleException
     */
    public static function post(string $uri, array $data, string $type = ApiDataType::BODY): ResponseInterface
    {
        return self::sendRequest('POST', $uri, [$type => $data]);
    }

    /**
     * Send put request
     *
     * @param string $uri
     * @param array $data
     * @param string $type
     * @return ResponseInterface
     * @throws GuzzleException
     */
    public static function put(string $uri, array $data, string $type = ApiDataType::BODY): ResponseInterface
    {
        return self::sendRequest('PUT', $uri, [$type => $data]);
    }

    /**
     * Add a headers for the request
     *
     * @param array $headers
     * @return self
     */
    public static function withHeaders(array $headers = []): self
    {
        self::$headers = array_merge(self::$headers, $headers);

        return new self;
    }

    /**
     * Send request
     *
     * @param string $method
     * @param string $uri
     * @param array $options
     * @return ResponseInterface
     * @throws GuzzleException
     */
    private static function sendRequest(string $method, string $uri, array $options = []): ResponseInterface
    {
        $client = new Client();
        $options = array_merge($options, [ApiDataType::HEADERS => self::$headers]);

        return $client->request(
            $method,
            $uri,
            $options
        );
    }
}
