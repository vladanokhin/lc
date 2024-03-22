<?php

namespace src\App\Services\LeadCollectorRequestService;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;
use src\App\Services\PartnerWorkerService\PartnerWorkerManager;

class LeadCollectorRequestManager extends PartnerWorkerManager
{
    /**
     * @param $uri
     * @return ResponseInterface
     * @throws GuzzleException
     */
    public function sendLeadToTracker($uri): ResponseInterface
    {
        $client = new Client();

        return $client->request('GET', $uri);
    }

    /**
     * @param array $lead
     * @return bool
     * @throws Exception
     */
    public function sendLeadToPartner(array $lead): bool
    {
        return $this->deliverLeadToPartner($lead['aff_network_name'], $lead);
    }
}