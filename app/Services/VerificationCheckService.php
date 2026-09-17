<?php

namespace App\Services;

use App\Models\Result;
use App\Models\VerificationCheck;
use Illuminate\Support\Facades\DB;

/**
 * Persists deterministic verification outcomes for VeriVote NG results.
 *
 * Stores one verification record for each executed rule so verification
 * results remain available for audit, reporting, and later review.
 */
class VerificationCheckService
{
    /**
     * Execute verification and persist every rule outcome.
     *
     * Existing checks for the result are removed before the new verification
     * run is stored, ensuring the result reflects the latest execution.
     */
    public function run(Result $result): array
    {
        $verificationService = app(VerificationService::class);

        $results = $verificationService->verify($result);

        DB::transaction(function () use ($result, $results): void {
            $result->verificationChecks()->delete();

            foreach ($results as $check) {
                VerificationCheck::create([
                    'result_id' => $result->id,
                    'rule_name' => $check['rule'],
                    'passed' => $check['passed'],
                    'details' => $check['details'],
                    'executed_at' => now(),
                ]);
            }
        });

        return $results;
    }
}