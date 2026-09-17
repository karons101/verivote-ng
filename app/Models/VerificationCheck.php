<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Represents one deterministic verification check performed on a result.
 *
 * Stores the verification rule, whether it passed, supporting details,
 * and the timestamp at which the check was executed.
 */
class VerificationCheck extends Model
{
    protected $fillable = [
        'result_id',
        'rule_name',
        'passed',
        'details',
        'executed_at',
    ];

    protected $casts = [
        'passed' => 'boolean',
        'executed_at' => 'datetime',
    ];

    public function result(): BelongsTo
    {
        return $this->belongsTo(Result::class);
    }
}