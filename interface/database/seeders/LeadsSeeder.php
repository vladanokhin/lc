<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LeadsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            "Affbay.asia TH" => [
                'cpl',
                'trash',
                'duplicate',
            ],

            "Everad"         => [
                'pending',
                'approved',
                'rejected',
            ],

            "AffScale"       => [
                'pending',
                'trash',
                'approved',
                'rejected'
            ],
        ];

        $connection = DB::connection('mysql_lc')->table('leads');
        for ($i = 0; $i < 5000; $i++) {
            $partner = array_rand($data);
            $status = $data[$partner][array_rand($data[$partner])];

            $connection->insert([
                'aff_network_name'  => $partner,
                'conversion_status' => $status,

                't_id'              => 1,
                'click_id'          => Str::random(16),
                'name'              => Str::random(16),
                'phone'             => Str::random(16),
                'unique_id'         => uniqid(),
                'country_code'      => Str::random(3),
                'offer_id'          => Str::random(16),
                'offer_name'        => Str::random(16),
                'product'           => Str::random(16),
                'is_sent'           => mt_rand(0,1),
            ]);
        }
    }
}
