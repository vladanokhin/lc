<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ad2lynx_statuses extends Model
{
    use HasFactory;

    protected $connection = 'mysql_lc';
    protected $table = 'ad2lynx_statuses';
    protected $fillable = [
        'status_category', 'weight'
    ];
    protected $primaryKey = 'ad2lynx_statuses_id';

    public function StatusSchemeModel()
    {
        return $this->hasOne('App\Models\StatusSchemeModel');
    }
}
