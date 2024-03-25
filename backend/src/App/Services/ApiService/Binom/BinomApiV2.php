<?php

namespace src\App\Services\ApiService\Binom;

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use src\App\Container\AppContainer;
use src\App\Services\ApiService\ApiClient;
use src\App\Services\DatabaseService\Connection;
use src\App\Services\DatabaseService\DataBaseManager;
use src\interfaces\Binom\BinomApi;
use src\interfaces\Binom\NewerVersionsApi;

class BinomApiV2 implements BinomApi, NewerVersionsApi
{
    use SharedApiRequest;

    /**
     * Uri for the api.
     * The prefix v1 is correct for this class
     */
    const API_URI = 'public/api/v1';

    /**
     * Tracker url
     *
     * @var string
     */
    private string $trackerUrl;

    /**
     * Tracker api key
     *
     * @var string
     */
    private string $apiKey;

    /**
     * Database manager
     *
     * @var DataBaseManager
     */
    private DataBaseManager $query;

    /**
     * @throws Exception
     */
    public function __construct(string $trackerUrl, string $apiKey)
    {
        $this->trackerUrl = $trackerUrl;
        $this->apiKey = $apiKey;
        $this->query = new DataBaseManager(Connection::make(), AppContainer::get('table_for_country_codes'));
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
            ? $this->convertFieldsToV1($data)
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

    /**
     * @inheritDoc
     * @return array
     */
    public function convertFieldsToV1(array $data): array
    {
        $correctFields = [
            'id'                    => 'click_id',
            'conversion_status_one' => 'conversion_status',
            'conversion_status_two' => 'conversion_status_2',
        ];

        // Convert a fields
        foreach($correctFields as $field => $correctField) {
            if(!isset($data[$field]))
                continue;

            $data[$correctField] = $data[$field];
            unset($data[$field]);
        }

        // Add a new filed country_code
        if(isset($data['country'])) {
            $result = $this->query->getBy(['country' => $data['country']], ['code']);
            $data['country_code'] = $result['code'] ?? '';
        }

        return $data;
    }
}
