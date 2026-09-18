<?php

namespace Tests\Unit;

use App\Models\Candidate;
use App\Models\Election;
use App\Models\PollingUnit;
use App\Models\Result;
use App\Models\ResultEntry;
use App\Services\VerificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VerificationServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Verify that valid result accounting passes deterministic rules C2-C5.
     *
     * C1 is expected to fail because this isolated test does not attach
     * cryptographic evidence or an Ed25519 signature.
     */
    public function test_valid_result_passes_deterministic_verification_rules(): void
    {
        $election = Election::create([
            'code' => 'TEST-ELECTION',
            'title' => 'Verification Test Election',
            'election_type' => 'general',
            'voting_date' => '2026-09-21',
            'status' => 'active',
        ]);

        $pollingUnit = PollingUnit::create([
            'election_id' => $election->id,
            'pu_code' => 'TEST-PU-001',
            'state' => 'Delta',
            'lga' => 'Uvwie',
            'ward' => 'Ward 01',
            'name' => 'Verification Test Polling Unit',
            'registered_voters' => 500,
        ]);

        $candidateA = Candidate::create([
            'election_id' => $election->id,
            'party_code' => 'PDA',
            'candidate_name' => 'Test Candidate A',
        ]);

        $candidateB = Candidate::create([
            'election_id' => $election->id,
            'party_code' => 'PDB',
            'candidate_name' => 'Test Candidate B',
        ]);

        $result = Result::create([
            'election_id' => $election->id,
            'polling_unit_id' => $pollingUnit->id,
            'submission_channel' => 'observer',
            'accredited_voters' => 100,
            'ballots_issued' => 100,
            'unused_ballots' => 10,
            'spoiled_ballots' => 0,
            'rejected_votes' => 5,
            'total_valid_votes' => 85,
            'status' => 'submitted',
        ]);

        ResultEntry::create([
            'result_id' => $result->id,
            'candidate_id' => $candidateA->id,
            'vote_count' => 50,
        ]);

        ResultEntry::create([
            'result_id' => $result->id,
            'candidate_id' => $candidateB->id,
            'vote_count' => 35,
        ]);

        $checks = app(VerificationService::class)->verify($result);

        $this->assertCount(5, $checks);

        $this->assertFalse($checks['C1']['passed']);

        $this->assertTrue($checks['C2']['passed']);
        $this->assertTrue($checks['C3']['passed']);
        $this->assertTrue($checks['C4']['passed']);
        $this->assertTrue($checks['C5']['passed']);
    }

    /**
     * Verify that C5 detects a mismatch between candidate totals and
     * the recorded total of valid votes.
     */
    public function test_c5_fails_when_candidate_totals_do_not_match_valid_votes(): void
    {
        $election = Election::create([
            'code' => 'TEST-ELECTION-C5',
            'title' => 'C5 Verification Test Election',
            'election_type' => 'general',
            'voting_date' => '2026-09-21',
            'status' => 'active',
        ]);

        $pollingUnit = PollingUnit::create([
            'election_id' => $election->id,
            'pu_code' => 'TEST-PU-C5',
            'state' => 'Delta',
            'lga' => 'Uvwie',
            'ward' => 'Ward 01',
            'name' => 'C5 Verification Test Polling Unit',
            'registered_voters' => 500,
        ]);

        $candidateA = Candidate::create([
            'election_id' => $election->id,
            'party_code' => 'PDA',
            'candidate_name' => 'Test Candidate A',
        ]);

        $candidateB = Candidate::create([
            'election_id' => $election->id,
            'party_code' => 'PDB',
            'candidate_name' => 'Test Candidate B',
        ]);

        $result = Result::create([
            'election_id' => $election->id,
            'polling_unit_id' => $pollingUnit->id,
            'submission_channel' => 'observer',
            'accredited_voters' => 100,
            'ballots_issued' => 100,
            'unused_ballots' => 10,
            'spoiled_ballots' => 0,
            'rejected_votes' => 5,
            'total_valid_votes' => 85,
            'status' => 'submitted',
        ]);

        ResultEntry::create([
            'result_id' => $result->id,
            'candidate_id' => $candidateA->id,
            'vote_count' => 50,
        ]);

        ResultEntry::create([
            'result_id' => $result->id,
            'candidate_id' => $candidateB->id,
            'vote_count' => 34,
        ]);

        $checks = app(VerificationService::class)->verify($result);

        $this->assertFalse($checks['C1']['passed']);
        $this->assertTrue($checks['C2']['passed']);
        $this->assertTrue($checks['C3']['passed']);
        $this->assertTrue($checks['C4']['passed']);

        $this->assertFalse($checks['C5']['passed']);
        $this->assertSame(
            '84 candidate votes against 85 total valid votes.',
            $checks['C5']['details']
        );
    }
}