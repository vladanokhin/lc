<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Offer extends Model
{
    use HasFactory;

    protected $connection = 'mysql_lc';

    protected $fillable = [
        'url',
        'geo',
        'language',
        'type',
        'category',
        'form_factor',
        'lp_numbering',
        'name',
        'aff_network',
        'price',
        'offer_type',
    ];

    /**
     * The roles that belong to the tracker.
     */
    public function trackers(): BelongsToMany
    {
        return $this->belongsToMany(TrackersSettingsModel::class, 'offer_tracker', 'offer_id', 'tracker_id');
    }
}
