<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Represents a candidate participating in a VeriVote NG election.
 *
 * Stores the candidate's identity and party code and associates the
 * candidate with the election whose results contain their vote count.
 */
class Candidate extends Model
{
    protected $fillable = [
        'election_id',
        'party_code',
        'candidate_name',
    ];

    public function election(): BelongsTo
    {
        return $this->belongsTo(Election::class);
    }
}