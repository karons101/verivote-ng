<?php

namespace Tests\Feature;

use App\Models\Candidate;
use App\Models\Election;
use App\Models\PollingUnit;
use App\Models\Result;
use App\Models\ResultEntry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Verifies the public verification report workflow for VeriVote NG.
 *
 * Ensures recorded results produce readable verification reports and that
 * unknown result identifiers correctly return a not-found response.
 */
class VerificationReportTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Verify that a recorded result produces a public verification report.
     */
    public function test_recorded_result_has_public_verification_report(): void
    {
        $result = $this->createResult();

        $response = $this->get(
            route('public.verify.report', ['result' => $result->id])
        );

        $response->assertOk();
        $response->assertSee('Verification Report');
        $response->assertSee($result->election->title);
        $response->assertSee($result->pollingUnit->pu_code);
        $response->assertSee('Test Candidate A');
        $response->assertSee('Test Candidate B');
        $response->assertSee('VERIFIED');
    }

    /**
     * Verify that an unknown result identifier returns HTTP 404.
     */
    public function test_unknown_result_report_returns_not_found(): void
    {
        $response = $this->get(
            route('public.verify.report', ['result' => 999999])
        );

        $response->assertNotFound();
    }

    /**
     * Create a minimal verified result graph for report tests.
     */
    private function createResult(): Result
    {
        $election = Election::create([
            'code' => 'REPORT-TEST-ELECTION',
            'title' => 'Verification Report Test Election',
            'election_type' => 'general',
            'voting_date' => '2026-09-21',
            'status' => 'active',
        ]);

        $pollingUnit = PollingUnit::create([
            'election_id' => $election->id,
            'pu_code' => 'REPORT-PU-001',
            'state' => 'Delta',
            'lga' => 'Uvwie',
            'ward' => 'Ward 01',
            'name' => 'Verification Report Test Polling Unit',
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
            'status' => 'verified',
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

        return $result;
    }
}