<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadResponse extends Model
{
    use HasFactory;

    protected $connection = 'mysql_lc';
    protected $table = 'lead_responses';
}
