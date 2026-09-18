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
 * Verifies the public result verification workflow for VeriVote NG.
 *
 * Ensures recorded results are publicly readable, while unknown result
 * identifiers correctly return a not-found response.
 */
class PublicVerificationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Verify that a recorded result is publicly accessible.
     */
    public function test_recorded_result_is_publicly_verifiable(): void
    {
        $result = $this->createResult();

        $response = $this->get(
            route('public.verify', ['result' => $result->id])
        );

        $response->assertOk();
        $response->assertSee('Public Verification');
        $response->assertSee($result->election->title);
        $response->assertSee($result->pollingUnit->pu_code);
        $response->assertSee('Test Candidate A');
        $response->assertSee('Test Candidate B');
    }

    /**
     * Verify that an unknown result identifier returns HTTP 404.
     */
    public function test_unknown_result_returns_not_found(): void
    {
        $response = $this->get(
            route('public.verify', ['result' => 999999])
        );

        $response->assertNotFound();
    }

    /**
     * Create a minimal valid result graph for public verification tests.
     */
    private function createResult(): Result
    {
        $election = Election::create([
            'code' => 'PUBLIC-VERIFY-TEST',
            'title' => 'Public Verification Test Election',
            'election_type' => 'general',
            'voting_date' => '2026-09-21',
            'status' => 'active',
        ]);

        $pollingUnit = PollingUnit::create([
            'election_id' => $election->id,
            'pu_code' => 'PUBLIC-PU-001',
            'state' => 'Delta',
            'lga' => 'Uvwie',
            'ward' => 'Ward 01',
            'name' => 'Public Verification Test Polling Unit',
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