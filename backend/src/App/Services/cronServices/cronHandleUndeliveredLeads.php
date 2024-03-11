<?php

namespace src\App\Services\cronServices;

use GuzzleHttp\Client;
use src\App\Container\AppContainer;
use src\App\Services\DatabaseService\Connection;
use src\App\Services\DatabaseService\DataBaseManager;
use src\App\Services\FileJobService\FileJobManager;
use src\App\Services\LeadCollectorService\LeadProcesses;
use src\App\Services\ScheduledLeads\ScheduledLeadsManager;

final class cronHandleUndeliveredLeads
{
    /**
     * @var DataBaseManager
     */
    private $db;

    /**
     * @var ScheduledLeadsManager
     */
    private $scheduledLeads;

    /**
     * @throws \Exception
     */
    public function __construct()
    {
        date_default_timezone_set('Europe/Moscow');
        $this->db = new DataBaseManager((new Connection())->make(), AppContainer::get('table_for_leads'));
        $this->scheduledLeads = new ScheduledLeadsManager();
    }

    public function launcher(): void
    {
        $this->initializeJob(86400);
    }

    private function initializeJob(int $timeFrom = 14400, int $timeTo = 7200): void
    {
        $timeTo = date('Y-m-d H:i:s', time() - $timeTo);
        $timeFrom = date('Y-m-d H:i:s', time() - $timeFrom);
        $leads = $this->db->customQuery(
            "conversion_status LIKE '%Collected%' AND created_at >= '{$timeFrom}' AND created_at <= '{$timeTo}'"
        );
        if (null === $leads) {
            echo 'Everything is fine. Any missed lead found.';
            exit();
        }
        $this->refreshQueue($leads);
        $leads = $this->db->customQuery(
            "conversion_status LIKE '%Collected%' AND created_at >= '{$timeFrom}' AND created_at <= '{$timeTo}'"
        );
        $this->handleQueue($leads);
    }

    /**
     * @param $leads
     * @return void
     */
    private function handleQueue($leads): void
    {
        foreach ($leads as $id => $lead) {
            $logDate = "insert-week-" . date('W-Y') . "-undelivered-leads";
            (new FileJobManager())->write($lead, "{$logDate}");
            $this->scheduleResending($lead);
            $this->setStatus($lead['click_id']);
        }
    }

    private function refreshQueue(array $leads): bool
    {
        $client = new Client();
        foreach ($leads as $key => $lead) {
            $client->get(AppContainer::get('app_url') . "/refresh/{$lead['click_id']}");
            time_nanosleep(1, 0);
        }
        return true;
    }

    /**
     * @param string $clickId
     * @return void
     */
    private function setStatus(string $clickId): void
    {
        $process = new LeadProcesses();
        $process->commitPostbackToHistory($clickId);
        $this->db->update([
            'conversion_status' => 'auto-resend'
        ], [
            'click_id' => $clickId
        ]);
    }

    /**
     * Adding undelivered lead to schedule
     * @param array $lead
     */
    private function scheduleResending(array $lead): void
    {
        $this->scheduledLeads->addToScheduled($lead);
    }
}