<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Represents one candidate's vote entry within a polling-unit result.
 *
 * Stores the candidate reference and recorded vote count used by VeriVote
 * to validate that candidate totals reconcile with the result's valid votes.
 */
class ResultEntry extends Model
{
    protected $fillable = [
        'result_id',
        'candidate_id',
        'vote_count',
    ];

    protected $casts = [
        'vote_count' => 'integer',
    ];

    public function result(): BelongsTo
    {
        return $this->belongsTo(Result::class);
    }

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }
}