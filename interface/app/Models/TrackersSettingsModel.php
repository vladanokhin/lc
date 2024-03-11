<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrackersSettingsModel extends Model
{
    use HasFactory;

    protected $connection = 'mysql_lc';
    protected $table = 'trackers_settings_models';
    protected $fillable = [
        't_id',
        't_url',
        't_api_key',
    ];
}
