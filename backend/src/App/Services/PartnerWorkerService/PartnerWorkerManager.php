<?php

namespace src\App\Services\PartnerWorkerService;

use Exception;
use src\App\Container\LeadCollectorContainer;
use src\interfaces\PartnerInterface\PartnerInterface;

class PartnerWorkerManager
{
    /**
     * @param string $partner
     * @param array $leadData
     * @return bool
     * @throws Exception
     */
    public function deliverLeadToPartner(string $partner, array $leadData): bool
    {
        return $this->sender($this->find($partner), $leadData);
    }

    /**
     * @param string $partner
     * @return mixed
     * @throws Exception
     */
    protected function find(string $partner)
    {
        $worker = ucfirst(strtolower(preg_replace('/[^A-Za-z0-9]/', '', $partner)));
        if (!empty(LeadCollectorContainer::get($worker))) {
            $key = $this->getPartnerApiKey($worker);
            $partnerApi = LeadCollectorContainer::get($worker)['provider_class'];
            $class = "\\src\\App\\Services\\PartnerWorkerService\\$partnerApi";
            $endpoint = LeadCollectorContainer::get($worker)['endpoint'];

            return new $class($key, $endpoint);
        }
        http_response_code(500);
        throw new Exception( "Attention! Partner {$partner} not found!");
    }

    /**
     * @param $partner
     * @return mixed
     * @throws Exception
     */
    private function getPartnerApiKey($partner)
    {
        return LeadCollectorContainer::get($partner)['api_key'];
    }

    /**
     * @param PartnerInterface $partner
     * @param array $data
     * @return bool
     */
    private function sender(PartnerInterface $partner, array $data): bool
    {
        return $partner->sendLead($data, $data['product']);
    }
}