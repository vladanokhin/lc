<?php

namespace src\App\Services\cronServices;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use src\App\Container\AppContainer;
use src\App\Services\DatabaseService\Connection;
use src\App\Services\DatabaseService\DataBaseManager;
use src\App\Services\LeadCollectorRequestService\LeadCollectorRequestManager;
use src\App\Services\ScheduledLeads\ScheduledLeadsManager;

final class cronSenderManager
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
     * @var LeadCollectorRequestManager
     */
    private $requestManager;

    /**
     * cronSenderManager constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->db = new DataBaseManager((new Connection())->make(), AppContainer::get('table_for_leads'));
        $this->scheduledLeads = new ScheduledLeadsManager();
        $this->requestManager = new LeadCollectorRequestManager();
    }

    /**
     * @throws \Exception
     */
    public function launcher(): void
    {
        $leads = $this->getLeadsFromDatabase(30);

        if (null !== $leads) {
            $this->sendDataToPartner($leads);
        }
    }

    /**
     * @param int $minutes
     * @return array|null
     */
    public function getLeadsFromDatabase(int $minutes = 16): ?array
    {
        $time = date('Y-m-d H:i:s', strtotime("-{$minutes} minutes"));

        return $this->scheduledLeads->customQuery("created_at > '{$time}' ORDER BY id DESC", '*');
    }

    /**
     * @param array $data
     * @return void
     * @throws \Exception
     */
    public function sendDataToPartner(array $data): void
    {
        foreach ($data as $leadsQueue => $lead) {
            if(
                $lead['aff_network_name'] == 'Without Affiliate Network Name' ||
                strtotime($lead['created_at']) > strtotime("-6 minutes")
            ) continue;

            try {
                $this->scheduledLeads->fillTheQueue($lead['id']);
                $this->db->update(['is_sent' => 1], ['click_id' => $lead['click_id']]);
                $this->requestManager->sendLeadToPartner($lead);
            } catch (\Exception $e) {
                $this->logError($e, $lead);
                continue;
            }
        }
        $this->scheduledLeads->removeFromScheduled();
        $this->scheduledLeads->clearQueue();
    }

    /**
     * В случае , когда лид не отправлен - логируем причину и сам лид.
     * @param \Exception $error
     * @param $lead
     * @throws \Exception
     */
    protected function logError(\Exception $error, $lead): void
    {
        $logger = new Logger('Cron Auto Sender Process');
        $logDate = date('Y-m-d');
        $path_to_logger = '/var/www/quantum/log';
        $logger->pushHandler(new StreamHandler("{$path_to_logger}/log-{$logDate}.log", Logger::ERROR));
        $logger->error("\n".$error->getMessage(), $lead);
    }
}