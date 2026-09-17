<?php

namespace App\Services;

use App\Models\Result;

/**
 * Executes deterministic integrity checks for VeriVote NG results.
 *
 * Applies the defined verification rules to result data and combines
 * cryptographic integrity verification with deterministic result checks.
 */
class VerificationService
{
    /**
     * Run all deterministic verification rules against a result.
     *
     * Returns one structured outcome per verification rule without making
     * any claim about electoral intent or determining whether manipulation occurred.
     */
    public function verify(Result $result): array
    {
        $result->loadMissing([
            'pollingUnit',
            'resultEntries',
            'signature',
        ]);

        return [
            'C1' => $this->verifySignature($result),
            'C2' => $this->verifyValidAndRejectedVotes($result),
            'C3' => $this->verifyBallotAccounting($result),
            'C4' => $this->verifyAccreditedVoters($result),
            'C5' => $this->verifyCandidateTotals($result),
        ];
    }

    /**
     * C1: The current result payload must match the signed payload hash
     * and the stored Ed25519 signature must verify against that hash.
     */
    private function verifySignature(Result $result): array
    {
        $cryptographicService = app(CryptographicService::class);

        if (!$result->signature || !$result->payload_hash) {
            return [
                'rule' => 'C1',
                'passed' => false,
                'details' => 'The result is missing its cryptographic signature or payload hash.',
            ];
        }

        $currentHash = $cryptographicService->generatePayloadHash($result);

        if (!hash_equals($result->payload_hash, $currentHash)) {
            return [
                'rule' => 'C1',
                'passed' => false,
                'details' => 'The current result payload does not match the signed payload hash.',
            ];
        }

        $passed = $cryptographicService->verifyResultSignature($result);

        return [
            'rule' => 'C1',
            'passed' => $passed,
            'details' => $passed
                ? 'The current result payload matches the signed hash and the stored Ed25519 signature verifies.'
                : 'The stored Ed25519 signature failed cryptographic verification.',
        ];
    }

    /**
     * C2: Valid votes plus rejected votes must not exceed accredited voters.
     */
    private function verifyValidAndRejectedVotes(Result $result): array
    {
        $actual = $result->total_valid_votes + $result->rejected_votes;
        $limit = $result->accredited_voters;
        $passed = $actual <= $limit;

        return [
            'rule' => 'C2',
            'passed' => $passed,
            'details' => "{$actual} valid and rejected votes against {$limit} accredited voters.",
        ];
    }

    /**
     * C3: Ballot accounting must reconcile issued ballots with all categories.
     */
    private function verifyBallotAccounting(Result $result): array
    {
        $accounted = $result->total_valid_votes
            + $result->rejected_votes
            + $result->unused_ballots
            + $result->spoiled_ballots;

        $passed = $result->ballots_issued === $accounted;

        return [
            'rule' => 'C3',
            'passed' => $passed,
            'details' => "{$accounted} ballots accounted for against {$result->ballots_issued} issued.",
        ];
    }

    /**
     * C4: Accredited voters must not exceed registered voters.
     */
    private function verifyAccreditedVoters(Result $result): array
    {
        $registered = $result->pollingUnit?->registered_voters ?? 0;
        $passed = $result->accredited_voters <= $registered;

        return [
            'rule' => 'C4',
            'passed' => $passed,
            'details' => "{$result->accredited_voters} accredited voters against {$registered} registered voters.",
        ];
    }

    /**
     * C5: Candidate vote entries must reconcile with total valid votes.
     */
    private function verifyCandidateTotals(Result $result): array
    {
        $candidateTotal = $result->resultEntries->sum('vote_count');
        $passed = $candidateTotal === $result->total_valid_votes;

        return [
            'rule' => 'C5',
            'passed' => $passed,
            'details' => "{$candidateTotal} candidate votes against {$result->total_valid_votes} total valid votes.",
        ];
    }
}