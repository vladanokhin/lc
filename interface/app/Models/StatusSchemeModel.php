<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusSchemeModel extends Model
{
    use HasFactory;

    protected $connection = 'mysql_lc';
    protected $table = 'lead_collector_statuses';
    protected $fillable = [
        'partner_name',
        'incoming_status_name',
        'status_locked',
        'add_event_2',
        'accept_payment',
        'status_id'
    ];
    public function Ad2lynx_statuses()
    {
        return $this->belongsTo('App\Models\Ad2lynx_statuses');
    }
}
