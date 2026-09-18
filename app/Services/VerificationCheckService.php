<?php

namespace App\Services;

use App\Models\Discrepancy;
use App\Models\Result;
use App\Models\VerificationCheck;
use Illuminate\Support\Facades\DB;

/**
 * Persists deterministic verification outcomes for VeriVote NG results.
 *
 * Stores one verification record for each executed rule, creates structured
 * discrepancies for failed rules, updates the parent result status, and
 * records the resulting state transition in the tamper-evident audit trail.
 */
class VerificationCheckService
{
    /**
     * Execute verification, persist every rule outcome, create discrepancies,
     * update the result status, and record the verification audit event.
     *
     * A result is marked as verified only when every deterministic check
     * passes. Any failed check marks the result as flagged and creates an
     * open discrepancy requiring human review.
     */
    public function run(Result $result): array
    {
        $verificationService = app(VerificationService::class);

        $results = $verificationService->verify($result);

        DB::transaction(function () use ($result, $results): void {
            $previousState = [
                'status' => $result->status,
            ];

            $result->verificationChecks()->delete();
            $result->discrepancies()->delete();

            foreach ($results as $check) {
                VerificationCheck::create([
                    'result_id' => $result->id,
                    'rule_name' => $check['rule'],
                    'passed' => $check['passed'],
                    'details' => $check['details'],
                    'executed_at' => now(),
                ]);

                if (!$check['passed']) {
                    Discrepancy::create([
                        'result_id' => $result->id,
                        'code' => $check['rule'],
                        'type' => $this->discrepancyType($check['rule']),
                        'severity' => 'warning',
                        'description' => $check['details'],
                        'status' => 'open',
                        'detected_at' => now(),
                    ]);
                }
            }

            $allPassed = collect($results)->every(
                fn (array $check): bool => $check['passed']
            );

            $newStatus = $allPassed ? 'verified' : 'flagged';

            $result->update([
                'status' => $newStatus,
            ]);

            app(AuditLogService::class)->record(
                'result',
                $result->id,
                'verification_completed',
                null,
                $previousState,
                [
                    'status' => $newStatus,
                ]
            );
        });

        return $results;
    }

    /**
     * Map a deterministic verification rule to a structured discrepancy type.
     */
    private function discrepancyType(string $rule): string
    {
        return match ($rule) {
            'C1' => 'cryptographic_integrity',
            'C2' => 'vote_accreditation_mismatch',
            'C3' => 'ballot_accounting_mismatch',
            'C4' => 'registration_limit_mismatch',
            'C5' => 'candidate_total_mismatch',
            default => 'verification_failure',
        };
    }
}