<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadCollectorMessagesModel extends Model
{
    use HasFactory;

    protected $connection = 'mysql_lc';
    protected $table = 'lead_collector_messages';
    protected $fillable = ['title', 'content'];
}
