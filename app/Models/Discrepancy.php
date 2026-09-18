<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Represents an integrity discrepancy detected by VeriVote NG.
 *
 * Stores the verification failure classification, severity, description,
 * and review status associated with a recorded polling-unit result.
 */
class Discrepancy extends Model
{
    protected $fillable = [
        'result_id',
        'code',
        'type',
        'severity',
        'description',
        'status',
        'detected_at',
    ];

    protected $casts = [
        'detected_at' => 'datetime',
    ];

    /**
     * Get the result associated with this discrepancy.
     */
    public function result(): BelongsTo
    {
        return $this->belongsTo(Result::class);
    }
}