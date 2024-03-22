<?php

namespace src\App\Services\ApiService;

use src\App\Services\ApiService\Binom\BinomApiV1;
use src\App\Services\ApiService\Binom\BinomApiV2;
use src\interfaces\BinomApi;

class ApiService
{
    /**
     * Get a binom client by the api version.
     *
     * @param string $apiVersion
     * @return BinomApi
     */
    public static function getBinomClientByApiVersion(?string $apiVersion, string $trackerUrl, string $apiKey): BinomApi
    {
        switch ($apiVersion) {
            case 'v1':
                $client = new BinomApiV1($trackerUrl, $apiKey);
                break;
            case 'v2':
                $client = new BinomApiV2($trackerUrl, $apiKey);
                break;
            default:
                $client = new BinomApiV1($trackerUrl, $apiKey);
        }

        return $client;
    }
}
