<?php

return [
    // Database connection driver. You can set custom driver in {app-path}/config/database.php
    'dbdriver'          => 'mysql',

    // Table in database for all leads
    'table_for_leads'   => 'leads',

    // Tables for status scheme
    'income_status_frontend' => 'lead_collector_statuses',
    'status_category_frontend' => 'ad2lynx_statuses',
    'lead_responses_frontend' => 'lead_responses',

    // Table for leads in queue to partner (sending by cron)
    'table_for_scheduled_leads' => 'scheduled_leads',

    // Table for country codes
    'table_for_country_codes' => 'country_codes',

    // All data that can be processed by lead collector
    'neededLeadData'    => [
        't_id',
        'name',
        'phone',
        'product',
        'click_id',
        'offer_id',
        'unique_id',
        'offer_name',
        'country_code',
        'aff_network_name',
        'conversion_status',
        'data_1',
        'data_2',
        'data_3',
        'second_phone',
        'second_number',
    ],

    'lc_url' => 'https://lead-collector-url.com',
];