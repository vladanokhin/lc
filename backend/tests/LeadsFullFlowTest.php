<?php

namespace Tests;

use Exception;
use src\App\Services\cronServices\cronSenderManager;
use src\App\Services\ScheduledLeads\ScheduledLeadsManager;
use Tests\TestApp\Bootstrap;

class LeadsFullFlowTest extends Bootstrap
{

    /**
     * @throws Exception
     */
    public function testLeadsExisted()
    {
        $affbay = $this->app->getLeadByClickId('31824b4c8uqvc6o904');
        $longForm = $this->app->getLeadByClickId('d4fb3irg63vj63ye11');
        $affscale = $this->app->getLeadByClickId('87a84b4c8qqa4022d');
        $everad = $this->app->getLeadByClickId('304c6b4c81zuq013b');
        $adcombo = $this->app->getLeadByClickId('1b880b4syk2gxdz94b');
        $aff1 = $this->app->getLeadByClickId('70309heb7qe3y395');
        $leadbit = $this->app->getLeadByClickId('13ff7u3e26j0967');
        $barracuda = $this->app->getLeadByClickId('6fbf1ci3vktfee30');

        $this->assertIsArray($affbay);
        $this->assertArrayHasKey('click_id', $affbay);
        print("\naffbay:\t {$affbay['click_id']}\n");

        $this->assertIsArray($longForm);
        $this->assertArrayHasKey('click_id', $longForm);
        print("\naffbay long form:\t {$longForm['click_id']}\n");

        $this->assertIsArray($affscale);
        $this->assertArrayHasKey('click_id', $affscale);
        print("\naffscale:\t {$affscale['click_id']}\n");

        $this->assertIsArray($everad);
        $this->assertArrayHasKey('click_id', $everad);
        print("\neverad:\t {$everad['click_id']}\n");

        $this->assertIsArray($adcombo);
        $this->assertArrayHasKey('click_id', $adcombo);
        print("\nadcombo:\t {$adcombo['click_id']}\n");

        $this->assertIsArray($aff1);
        $this->assertArrayHasKey('click_id', $aff1);
        print("\naff1:\t {$aff1['click_id']}\n");

        $this->assertIsArray($leadbit);
        $this->assertArrayHasKey('click_id', $leadbit);
        print("\nleadbit:\t {$leadbit['click_id']}\n");

        $this->assertIsArray($barracuda);
        $this->assertArrayHasKey('click_id', $barracuda);
        print("\nbarracuda:\t {$barracuda['click_id']}\n");

        $this->assertEmpty($this->app->getLeadByClickId('0'));
    }

    public function testAddingSecondPhoneForAff1AndAffbay()
    {
        // aff1
        $this->assertTrue(
            $this->app->additionalDataFromThankYouPage('70309heb7qe3y395', '0964441122')
        );
        //affbay
        $this->assertTrue(
            $this->app->additionalDataFromThankYouPage('31824b4c8uqvc6o904', '0964442233')
        );
        // barracuda
        $this->assertTrue(
            $this->app->additionalDataFromThankYouPage('6fbf1ci3vktfee30', '0964452221')
        );

        $aff1 = $this->app->getLeadByClickId('70309heb7qe3y395');
        $this->assertEquals($aff1['second_phone'], '0964441122');

        $affbay = $this->app->getLeadByClickId('31824b4c8uqvc6o904');
        $this->assertEquals($affbay['second_phone'], '0964442233');

        $barracuda = $this->app->getLeadByClickId('6fbf1ci3vktfee30');
        $this->assertEquals($barracuda['second_phone'], '0964452221');
    }

    public function testScheduledLeads()
    {
        $cron = new cronSenderManager();
        $leads = $cron->getLeadsFromDatabase(400);
        $this->assertNotEmpty($leads);
        foreach ($leads as $index => $lead) {
            $this->assertArrayHasKey('click_id', $lead);
            $this->assertArrayHasKey('aff_network_name', $lead);
        }
    }

    /**
     * Лиды для этого теста НЕ отправляются партнёру.
     * В этом тесте они сразу удаляются из очереди, как после отправки.
     * Тестируемая функция: очистка очереди.
     */
    public function testClearingQueueAfterSendingLeadsToPartner()
    {
        $cron = new cronSenderManager();
        $leads = $cron->getLeadsFromDatabase(200);
        $scheduledManager = new ScheduledLeadsManager();
        foreach ($leads as $index => $lead) {
            $scheduledManager->fillTheQueue($lead['id']);
        }
        $scheduledManager->removeFromScheduled();
        $scheduledManager->clearQueue();
        $this->assertNull($cron->getLeadsFromDatabase(100));
    }

    public function testHandlingPostback()
    {
        $lcm = new \src\App\Services\LeadCollectorService\LeadCollectorManager();
        $this->app->handlePostback([
            'cnv_id' => '31824b4c8uqvc6o904',
            'cnv_status' => 'trash',
        ]);
        $this->app->handlePostback([
            'cnv_id' => 'd4fb3irg63vj63ye11',
            'cnv_status' => 'trash',
        ]);
        $this->app->handlePostback([
            'cnv_id' => '87a84b4c8qqa4022d',
            'cnv_status' => 'trash',
        ]);
        $this->app->handlePostback([
            'cnv_id' => '304c6b4c81zuq013b',
            'cnv_status' => 'trash',
        ]);
        $this->app->handlePostback([
            'cnv_id' => '1b880b4syk2gxdz94b',
            'cnv_status' => 'trash',
        ]);
        $this->app->handlePostback([
            'cnv_id' => '70309heb7qe3y395',
            'cnv_status' => 'trash',
        ]);
        $this->app->handlePostback([
            'cnv_id' => '13ff7u3e26j0967',
            'cnv_status' => 'trash',
        ]);
        $this->app->handlePostback([
            'cnv_id' => '6fbf1ci3vktfee30',
            'cnv_status' => 'trash',
        ]);

        $lead = $this->app->getLeadByClickId('31824b4c8uqvc6o904');
        $this->assertEquals($lead['conversion_status'], 'trash');
//        $lcm->handlePostback([
//            'cnv_id' => '31824b4c8uqvc6o904',
//            'cnv_status' => 'trash'
//        ]);

        $lead = $this->app->getLeadByClickId('87a84b4c8qqa4022d');
        $this->assertEquals($lead['conversion_status'], 'trash');
//        $lcm->handlePostback([
//            'cnv_id' => '87a84b4c8qqa4022d',
//            'cnv_status' => 'trash'
//        ]);

        $lead = $this->app->getLeadByClickId('304c6b4c81zuq013b');
        $this->assertEquals($lead['conversion_status'], 'trash');
//        $lcm->handlePostback([
//            'cnv_id' => '304c6b4c81zuq013b',
//            'cnv_status' => 'trash'
//        ]);

        $lead = $this->app->getLeadByClickId('1b880b4syk2gxdz94b');
        $this->assertEquals($lead['conversion_status'], 'trash');
//        $lcm->handlePostback([
//            'cnv_id' => '1b880b4syk2gxdz94b',
//            'cnv_status' => 'trash'
//        ]);

        $lead = $this->app->getLeadByClickId('70309heb7qe3y395');
        $this->assertEquals($lead['conversion_status'], 'trash');
//        $lcm->handlePostback([
//            'cnv_id' => '70309heb7qe3y395',
//            'cnv_status' => 'trash'
//        ]);

        $lead = $this->app->getLeadByClickId('13ff7u3e26j0967');
        $this->assertEquals($lead['conversion_status'], 'trash');
//        $lcm->handlePostback([
//            'cnv_id' => '13ff7u3e26j0967',
//            'cnv_status' => 'trash'
//        ]);

        $lead = $this->app->getLeadByClickId('6fbf1ci3vktfee30');
        $this->assertEquals($lead['conversion_status'], 'trash');
//        $lcm->handlePostback([
//            'cnv_id' => '6fbf1ci3vktfee30',
//            'cnv_status' => 'trash'
//        ]);

        /**
         * Long form lead requires
         */
        $this->assertNotEmpty($this->app->getLeadByClickId('87a84b4c8qqa4022d'));
        $this->assertIsArray($this->app->getLeadByClickId('87a84b4c8qqa4022d'));
    }

    public function testRefreshLead()
    {
        $lead = $this->app->getLeadByClickId('31824b4c8uqvc6o904');
        $this->assertEquals('2133', $lead['offer_id']);

        $this->app->updateLeadInfo(['offer_id' => '100100100'], ['click_id' => '31824b4c8uqvc6o904']);
        $lead = $this->app->getLeadByClickId('31824b4c8uqvc6o904');
        $this->assertEquals('100100100', $lead['offer_id']);

        $lead = ['click' => $lead['click_id']];
        $this->app->refreshLead($lead);

        $lead = $this->app->getLeadByClickId('31824b4c8uqvc6o904');
        $this->assertEquals('2133', $lead['offer_id']);
    }

    public function testRemoveLead()
    {
        $lead = $this->app->getLeadByClickId('70309heb7qe3y395');
        $this->app->deleteLead($lead['unique_id']);

        $lead = $this->app->getLeadByClickId('70309heb7qe3y395');
        $this->assertEquals('1', (string)$lead['is_deleted']);
    }

    public function testPrepareMissedLeads()
    {
        $clickId = 'd4fb3irg63vj63ye11';
        $this->app->updateLeadInfo(['conversion_status' => 'added_to_tracker'], ['click_id' => $clickId]);
        sleep(1);


        $lead = $this->app->getLeadByClickId($clickId);
        $this->assertTrue($lead['conversion_status'] === 'added_to_tracker');
    }

//    public function testRestoringMissedLeads()
//    {
//        $file = __FILE__; $line = __LINE__;
//        print_r("\n\nEdit $file AFTER {$line} line");
//        $this->assertTrue(true);
//        exit();
//
//        $clickId = 'd4fb3irg63vj63ye11';
//        $restore = new cronHandleUndeliveredLeads();
//        $restore->launcher();
//        sleep(1);
//        $lead = $this->app->getLeadByClickId($clickId);
//        print_r("\nRestored status -> \t{$lead['conversion_status']}\n");
//        $this->assertTrue($lead['conversion_status'] !== 'added_to_tracker');
//        $this->assertTrue($lead['conversion_status'] === 'Resended');
//    }
}