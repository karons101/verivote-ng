<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Represents an immutable audit record for a VeriVote entity.
 *
 * Stores the state transition, actor information, and chained hashes
 * used to preserve a tamper-evident history of system actions.
 */
class AuditLog extends Model
{
    protected $fillable = [
        'entity_name',
        'entity_id',
        'action',
        'actor_id',
        'previous_state',
        'new_state',
        'prev_audit_hash',
        'current_hash',
    ];

    protected $casts = [
        'entity_id' => 'integer',
        'actor_id' => 'integer',
        'previous_state' => 'array',
        'new_state' => 'array',
    ];
}
