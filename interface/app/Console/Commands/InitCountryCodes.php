<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;

class InitCountryCodes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'codes:init
                            {--d|domain= : Tracker domain (domain.com)}
                            {--k|key= : Api key}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to initialize country codes from version 2 tracker api';

    /**
     * Name of the country code table
     *
     * @var string
     */
    protected string $table = 'country_codes';
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if(!Schema::connection('mysql_lc')->hasTable($this->table)) {
            $this->error("\nCountry codes table not found!");
            $this->comment("Table: mysql_lc.$this->table");
            return 1;
        }

        $domain = $this->option('domain');
        $apiKey = $this->option('key');

        if(is_null($domain))
            $domain = $this->ask('Enter the tracker domain (domain.com)');

        if (is_null($apiKey))
            $apiKey = $this->secret("Enter the tracker's api key");

        $url = "https://$domain/public/api/v1/geo/country";
        $response = Http::withHeaders(['Api-Key' => $apiKey])
            ->acceptJson()
            ->get($url);

        if(!$response->successful()) {
            $code = $response->status();
            $this->error("\nReturned http code: $code");
            $this->line($url);
        }

        $codes = $response->json();
        $countOfCodes = count($codes);

        if(empty($codes)) {
            $this->warn("\nNo country codes found in the tracker.");
            return 0;
        }

        foreach ($codes as &$code) {
            $code['country'] = $code['name'];
            unset($code['name'], $code['fullname']);
        }

        DB::connection('mysql_lc')->table($this->table)->delete();
        $isInsertedNewValues = DB::connection('mysql_lc')->table($this->table)->insert($codes);

        if($isInsertedNewValues)
            $this->info("\nSuccessfully added $countOfCodes country codes.");
        else
            $this->error("\nCan't add new records!");

        return 0;
    }
}
