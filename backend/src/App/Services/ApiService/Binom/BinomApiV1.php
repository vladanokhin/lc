<?php

namespace src\App\Services\ApiService\Binom;

use GuzzleHttp\Exception\GuzzleException;
use src\App\Services\ApiService\ApiClient;
use src\interfaces\BinomApi;

class BinomApiV1 implements BinomApi
{
    use SharedApiRequest;

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
        $trackerUrl = "https://$this->trackerUrl/arm.php";

        try {
            $response = ApiClient::get($trackerUrl, [
                'api_key' => $this->apiKey,
                'action'  => 'clickinfo@get',
                'clickid' => $clickId,
            ]);
        } catch (GuzzleException $e) {
            return [];
        }

        $data = json_decode($response->getBody()->getContents(), true);

        return isset($data['status']) && $data['status'] === 'error'
            ? []
            : $data;
    }

    /**
     * @inheritDoc
     * @return bool
     */
    public function updateLead(array $data): bool
    {
       return $this->sharedUpdateLead($data);
    }
}
