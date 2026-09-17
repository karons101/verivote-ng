<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Represents a polling unit tracked by VeriVote NG.
 *
 * Stores polling-unit identification and registered voter information,
 * and associates the unit with its election and recorded results.
 */
class PollingUnit extends Model
{
    protected $fillable = [
        'election_id',
        'pu_code',
        'state',
        'lga',
        'ward',
        'name',
        'registered_voters',
    ];

    protected $casts = [
        'registered_voters' => 'integer',
    ];

    public function election(): BelongsTo
    {
        return $this->belongsTo(Election::class);
    }

    public function results(): HasMany
    {
        return $this->hasMany(Result::class);
    }
}