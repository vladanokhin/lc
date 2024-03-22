<?php

namespace src\interfaces;

interface BinomApi
{

    public function __construct(string $trackerUrl, string $apiKey);

    /**
     * Get a lead from the tracker
     *
     * @param string $clickId
     * @return array
     */
    public function getLead(string $clickId): array;

    /**
     * Update a data for the lead
     *
     * @param array $data
     * @return bool
     */
    public function updateLead(array $data): bool;
}
