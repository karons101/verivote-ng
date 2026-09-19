<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Represents an election tracked by VeriVote NG.
 *
 * Stores election identity and lifecycle information and acts as the
 * parent model for its polling units and candidates.
 */
class Election extends Model
{
    protected $fillable = [
        'code',
        'title',
        'election_type',
        'voting_date',
        'status',
    ];

    protected $casts = [
        'voting_date' => 'date',
    ];

    public function pollingUnits(): HasMany
    {
        return $this->hasMany(PollingUnit::class);
    }

    public function candidates(): HasMany
    {
        return $this->hasMany(Candidate::class);
    }
}