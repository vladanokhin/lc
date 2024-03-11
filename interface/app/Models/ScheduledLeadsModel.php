<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduledLeadsModel extends Model
{
    use HasFactory;

    protected $connection = 'mysql_lc';
    protected $table = 'scheduled_leads';
}
