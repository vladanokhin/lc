<?php

namespace src\App\Services\CallerService;

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use src\App\Services\cronServices\cronHandleLostLeads;
use src\App\Services\cronServices\cronHandleUndeliveredLeads;
use src\App\Services\cronServices\cronSenderManager;
use src\App\Services\LeadCollectorService\LeadCollectorManager;
use src\App\Services\LeadCollectorService\LeadProcesses;

class ServiceManager extends LeadCollectorManager
{
    /**
     * Обработка поступившего лида
     *
     * @param array $request
     * @return bool
     * @throws Exception|GuzzleException
     */
    public function orderLead(array $request): bool
    {
        return $this->leadProcessing($request);
    }

    /**
     * Обновление лида:
     * Статус синхронизируется по потоку LC -> Binom
     * Вся остальная информация - ноборот, Binom -> LC
     *
     * @param array $lead
     * @return void
     * @throws GuzzleException
     */
    public function refreshLead(array $lead): void
    {
        $this->refreshLeadData($lead['click']);
    }

    /**
     * Запрос лида в json по click_id
     *
     * @param string $clickId
     * @return string
     * @throws Exception
     */
    public function getOwnLead(string $clickId): string
    {
        return json_encode($this->getLeadByClickId($clickId));
    }

    /**
     * Присвоение лиду статуса "удалён"
     * @param $id
     * @return bool
     * @throws Exception
     * @throws GuzzleException
     */
    public function deleteLead($id): bool
    {
        return $this->disableLead(['unique_id' => $id]);
    }

    /**
     * Переотправка лида (инициация из ЛК)
     * @param array $lead
     * @return bool
     * @throws Exception
     */
    public function reorderLead(array $lead): bool
    {
        return $this->resendLeadToPartner($lead['unique']);
    }

    /**
     * Обработка данных, полученных с thank you page (Варианты: второй номер, емеил и тд)
     *
     * @param string $clickId
     * @param string $number
     * @param string $email
     * @return void
     */
    public function additionalDataFromThankYouPage(string $clickId, string $number, string $email): void
    {
        $this->handleAdditionalData($clickId, $number, $email);
    }

    /**
     *  Инициализация отправки запланированных лидов
     * @throws Exception
     */
    public function initializeSendingLeads(): void
    {
        $sender = new cronSenderManager();
        $sender->launcher();
    }

    /**
     * @param $data
     */
    public function reorderEdited($data)
    {
        $process = new LeadProcesses();
        $result = $process->processingReorderedLead($data);
        if ($result == null) {
            http_response_code(500);
            echo json_encode(['status' => 'same_data']);
            exit();
        }

        if ($result === true) {
            http_response_code(200);
            echo json_encode(['status' => 'data_updated']);
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'data_not_updated']);
        }
        exit();
    }

    public function handleLostLeads()
    {
        $handler = new cronHandleLostLeads();
        $handler->launcher();
    }

    public function handleMissedLeads()
    {
        $handler = new cronHandleUndeliveredLeads();
        $handler->launcher();
    }

    /**
     * @param $lead
     * @return bool
     * @throws GuzzleException
     */
    public function longFormLeads($lead): bool
    {
        return (new LeadCollectorManager())->writeLeadToDatabase($lead);
    }

    public function findPhoneInPostRequest(array $post, array $existing_lead): string
    {
        if (!isset($post['second_phone'])) {
            if (null !== $existing_lead['second_phone']) {
                $phone = $existing_lead['second_phone'];
            } else {
                $phone = '';
            }
        } else {
            if (strtolower($post['second_phone']) == strtolower($existing_lead['second_phone'])) {
                $phone = $existing_lead['second_phone'];
            } else {
                $phone = $post['second_phone'];
            }
        }
        return $phone;
    }

    public function findEmailInPostRequest(array $post, array $existing_lead): string
    {
        if (!isset($post['user_email'])) {
            if (null !== $existing_lead['user_email']) {
                $email = $existing_lead['user_email'];
            } else {
                $email = '';
            }
        } else {
            if (
                !empty($post['user_email']) &&
                strtolower($post['user_email']) == strtolower($existing_lead['user_email'])
            ) {
                $email = $existing_lead['user_email'];
            } else {
                $email = $post['user_email'];
            }
        }
        return $email;
    }
}