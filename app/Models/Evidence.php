<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Represents source evidence attached to a VeriVote result.
 *
 * Stores evidence file metadata and its SHA-256 fingerprint so the
 * integrity of the original evidence can be checked during verification.
 */
class Evidence extends Model
{
    protected $fillable = [
        'result_id',
        'file_type',
        'storage_path',
        'file_hash',
        'captured_at',
    ];

    protected $casts = [
        'captured_at' => 'datetime',
    ];

    public function result(): BelongsTo
    {
        return $this->belongsTo(Result::class);
    }
}