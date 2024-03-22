<?php

namespace src\App\Services\ApiService\Binom;

use GuzzleHttp\Exception\GuzzleException;
use src\App\Services\ApiService\ApiClient;
use src\interfaces\BinomApi;

class BinomApiV2 implements BinomApi
{
    use SharedApiRequest;

    /**
     * Uri for the api.
     * The prefix v1 is correct for this class
     */
    const API_URI = 'public/api/v1';

    private string $trackerUrl;

    private string $apiKey;

    public function __construct(string $trackerUrl, string $apiKey)
    {
        $this->trackerUrl = $trackerUrl;
        $this->apiKey = $apiKey;
    }

    /**
     * @inheritDoc
     * @return array
     */
    public function getLead(string $clickId): array
    {
        $trackerUrl = $this->createUrl($this->trackerUrl, "click/info/$clickId");

        try {
            $response = ApiClient::withHeaders(['Api-Key' => $this->apiKey])->get($trackerUrl);
        } catch (GuzzleException $e) {
            return [];
        }

        $data = json_decode($response->getBody()->getContents(), true);

        return $response->getStatusCode() === 200
            ? $data
            : [];
    }

    /**
     * @inheritDoc
     * @return bool
     */
    public function updateLead(array $data): bool
    {
        return $this->sharedUpdateLead($data);
    }

    /**
     * Create a api url
     *
     * @param string $trackerUrl
     * @param string $path
     * @return string
     */
    private function createUrl(string $trackerUrl, string $path): string
    {
        return "https://$trackerUrl/" . self::API_URI . "/$path";
    }
}
