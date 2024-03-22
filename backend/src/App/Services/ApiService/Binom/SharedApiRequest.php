<?php

namespace src\App\Services\ApiService\Binom;

use GuzzleHttp\Exception\GuzzleException;
use src\App\Services\ApiService\ApiClient;

/**
 * A trait for common requests for multiple API versions
 */
trait SharedApiRequest
{

    private function sharedUpdateLead(array $data): bool
    {
        $trackerUrl = "https://$this->trackerUrl/click.php";

        try {
            $response = ApiClient::get($trackerUrl, $data);
            return $response->getStatusCode() === 200;
        } catch (GuzzleException $e) {
            return false;
        }
    }
}
