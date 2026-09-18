<?php

namespace App\Services;

use App\Models\AuditLog;

/**
 * Creates tamper-evident audit records for VeriVote NG.
 *
 * Each audit record includes the previous audit hash in the material used
 * to calculate its current hash, creating a sequential hash chain.
 */
class AuditLogService
{
    /**
     * Record an audit event and link it to the previous audit record.
     *
     * The current hash is calculated from the entity state transition and
     * the previous audit hash so later records depend on earlier history.
     */
    public function record(
        string $entityName,
        int $entityId,
        string $action,
        ?int $actorId,
        ?array $previousState,
        ?array $newState
    ): AuditLog {
        $previousAudit = AuditLog::latest('id')->first();

        $previousHash = $previousAudit?->current_hash;

        $auditData = [
            'entity_name' => $entityName,
            'entity_id' => $entityId,
            'action' => $action,
            'actor_id' => $actorId,
            'previous_state' => $previousState,
            'new_state' => $newState,
            'prev_audit_hash' => $previousHash,
        ];

        $currentHash = hash(
            'sha256',
            json_encode(
                $auditData,
                JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
            )
        );

        return AuditLog::create([
            ...$auditData,
            'current_hash' => $currentHash,
        ]);
    }
}