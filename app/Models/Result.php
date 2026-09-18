<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Represents a polling-unit election result recorded by VeriVote NG.
 *
 * Stores vote totals and ballot-accounting figures used by the verification
 * engine, together with cryptographic integrity data and verification status.
 */
class Result extends Model
{
    protected $fillable = [
        'election_id',
        'polling_unit_id',
        'submission_channel',
        'accredited_voters',
        'ballots_issued',
        'unused_ballots',
        'spoiled_ballots',
        'rejected_votes',
        'total_valid_votes',
        'payload_hash',
        'status',
    ];

    protected $casts = [
        'accredited_voters' => 'integer',
        'ballots_issued' => 'integer',
        'unused_ballots' => 'integer',
        'spoiled_ballots' => 'integer',
        'total_valid_votes' => 'integer',
        'rejected_votes' => 'integer',
    ];

    public function election(): BelongsTo
    {
        return $this->belongsTo(Election::class);
    }

    public function pollingUnit(): BelongsTo
    {
        return $this->belongsTo(PollingUnit::class);
    }

    public function resultEntries(): HasMany
    {
        return $this->hasMany(ResultEntry::class);
    }

    public function evidence(): HasMany
    {
        return $this->hasMany(Evidence::class);
    }

    public function signature(): HasOne
    {
        return $this->hasOne(Signature::class);
    }

    public function verificationChecks(): HasMany
    {
        return $this->hasMany(VerificationCheck::class);
    }

    public function discrepancies(): HasMany
    {
        return $this->hasMany(Discrepancy::class);
    }

    /**
     * Get audit events associated with this result.
     *
     * Audit logs use a polymorphic-style entity name and identifier rather
     * than a database foreign key, allowing the audit trail to cover
     * multiple entity types while retaining result-specific history here.
     */
    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class, 'entity_id')
            ->where('entity_name', 'result');
    }
}