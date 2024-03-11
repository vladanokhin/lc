<?php

namespace src\App\Services\LeadCollectorService;

use PHPUnit\Framework\Warning;
use src\App\Services\PartnerWorkerService\PartnerWorkerManager;

class LeadResender
{
    public function sendToPartner($click): bool
    {
        $data = $this->collectDataFromDatabase($click);
        if ($data === null) {
            http_response_code(500);
            throw new Warning("There is no needed lead in database. \nRequest Click id => {$click}\n");
        }

        return $this->sendLeadToPartner($data);
    }

    private function collectDataFromDatabase($click): ?array
    {
        $lead = (new LeadCollectorManager())->getLeadByClickId($click);

        return (!empty($lead)) ? $lead : null;
    }

    private function sendLeadToPartner(array $leadData): bool
    {
        $worker = new PartnerWorkerManager();

        return $worker->deliverLeadToPartner($leadData['aff_network_name'], $leadData);
    }
}