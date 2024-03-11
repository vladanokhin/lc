<?php

namespace Database\Seeders;

use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LeadCollectorMessagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();
        for ($i = 0; $i < 15; $i++) {

            DB::connection('mysql_lc')->table('lead_collector_messages')->insert([
                'title'     => (string)$faker->paragraph(1),
                'content'   => (string)$faker->paragraph(2)
            ]);

        }
        unset($faker);
    }
}
