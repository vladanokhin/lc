<?php

namespace src\interfaces\PartnerInterface;

interface PartnerInterface {

    public function __construct(string $apiKey, string $endpoint = null);

    public function sendLead(array $lead, string $product): bool;
}