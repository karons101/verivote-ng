<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Represents a cryptographic signature attached to a VeriVote result.
 *
 * Stores the signer's role, public key, Ed25519 signature, and signing
 * timestamp used to verify the integrity and provenance of the result.
 */
class Signature extends Model
{
    protected $fillable = [
        'result_id',
        'signer_role',
        'public_key',
        'signature',
        'signing_timestamp',
    ];

    protected $casts = [
        'signing_timestamp' => 'datetime',
    ];

    public function result(): BelongsTo
    {
        return $this->belongsTo(Result::class);
    }
}