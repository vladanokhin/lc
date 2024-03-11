<?php

use src\App\Http\Controller\AppController;
use src\App\Services\DatabaseService\Connection;
use TestApp\Bootstrap;

class PreparationToTest extends Bootstrap
{
    private array $click_id_hub = [
        'affbay'    => '31824b4c8uqvc6o904',
        'longForm'  => 'd4fb3irg63vj63ye11',
        'affscale'  => '87a84b4c8qqa4022d',
        'everad'    => '304c6b4c81zuq013b',
        'adcombo'   => '1b880b4syk2gxdz94b',
        'aff1'      => '70309heb7qe3y395',
        'leadbit'   => '13ff7u3e26j0967',
        'barracuda' => '6fbf1ci3vktfee30',
    ];

    public function testAppDestroyer()
    {
        $db = Connection::make();

        $stmt = $db->prepare('DROP TABLE IF EXISTS leads;');
        $this->assertTrue($stmt->execute());

        $stmt = $db->prepare('DROP TABLE IF EXISTS leads_archive;');
        $this->assertTrue($stmt->execute());

        $stmt = $db->prepare('DROP TABLE IF EXISTS scheduled_leads;');
        $this->assertTrue($stmt->execute());
    }

    public function testAppConstructor()
    {
        (new AppController())->installer();
        $this->assertTrue(true);
    }

    public function testOrderingLeads()
    {
        $affbay = $this->app->orderLead([
            't_id'      => '1',
            'click_id'  => $this->click_id_hub['affbay'],
            'product'   => '0e3f2dee-de8b-49c1-a01e-b4ce3a5b99d7',
            'name'      => 'อืมิสดๆับาึวว้ิงะฺกั',
            'phone'     => '80158594706',
        ]);

        $longForm = $this->app->longFormLeads([
            't_id'      => '1',
            'click_id'  => $this->click_id_hub['longForm'],
            'product'   => '0d4e4625-4fb8-4160-8cf1-2c908c332715',
            'name'      => 'Long Form',
            'phone'     => '0987654321',
        ]);

        $affscale = $this->app->orderLead([
            't_id'      => '1',
            'click_id'  => $this->click_id_hub['affscale'],
            'product'   => '37',
            'name'      => 'เดช กำภู',
            'phone'     => '0633730100',
        ]);

        $everad = $this->app->orderLead([
            't_id'      => '1',
            'click_id'  => $this->click_id_hub['everad'],
            'product'   => '948421',
            'name'      => 'สั่งซื้อแล้วค่ะ',
            'phone'     => '0637865430',
            'data_1'    => '58.8.234.212',
        ]);

        $adcombo = $this->app->orderLead([
            't_id'          => '1',
            'click_id'      => $this->click_id_hub['adcombo'],
            'product'       => '15523',
            'name'          => 'Peter Cortel',
            'phone'         => '87740847',
            'data_1'        => '1790',
            'data_2'        => '175.176.24.106',
            'country_code'  => 'PH',
        ]);

        $aff1 = $this->app->orderLead([
            't_id'     => '2',
            'click_id' => $this->click_id_hub['aff1'],
            'product'  => 'VQOznqwa',
            'name'     => 'James Bond',
            'phone'    => '0554223077',
            'data_1'   => 'SA'
        ]);

        $leadbit = $this->app->orderLead([
            't_id'     => '3',
            'click_id' => $this->click_id_hub['leadbit'],
            'product'  => '0ejk',
            'name'     => 'Yorlady muñoz',
            'phone'    => '3022569878',
            'data_1'   => '190.90.233.182',
            'data_2'   => 'co',
        ]);

        $barracuda = $this->app->orderLead([
            't_id'     => '2',
            'click_id' => $this->click_id_hub['barracuda'],
            'product'  => '170044',
            'name'     => 'do_not_call',
            'phone'    => '0551423047',
            'data_1'   => '11',         //
            'data_2'   => '8u0bbg',      // utm_term
            'data_3'   => json_encode(['payout' => '11', 'price' => '17990']),       // utm_term
        ]);

        print("\n\nterraleads в тестировании не учавствует\n\n");
        print("\numgid в тестировании не учавствует\n\n");

        $this->assertTrue($affbay);
        $this->assertTrue($longForm);
        $this->assertTrue($affscale);
        $this->assertTrue($everad);
        $this->assertTrue($adcombo);
        $this->assertTrue($aff1);
        $this->assertTrue($leadbit);
        $this->assertTrue($barracuda);
    }
}