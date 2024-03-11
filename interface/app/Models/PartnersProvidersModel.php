<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartnersProvidersModel extends Model
{
    use HasFactory;

    protected $connection = 'mysql_lc';
    protected $table = 'lead_collector_partners_settings';
    protected $fillable = [
        'partner_name',
        'partner_provider',
        'provider_class',
        'api_key',
        'endpoint'
    ];
}
