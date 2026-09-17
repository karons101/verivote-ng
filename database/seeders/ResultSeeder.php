<?php

namespace Database\Seeders;

use App\Models\Result;
use Illuminate\Database\Seeder;

/**
 * Seeds a representative polling-unit result for VeriVote NG development.
 *
 * Provides controlled demo result data linked to the seeded election,
 * polling unit, and candidates for testing verification workflows.
 */
class ResultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $result = Result::create([
            'election_id' => 1,
            'polling_unit_id' => 1,
            'submission_channel' => 'observer',
            'accredited_voters' => 100,
            'ballots_issued' => 100,
            'unused_ballots' => 10,
            'spoiled_ballots' => 0,
            'rejected_votes' => 5,
            'total_valid_votes' => 85,
            'status' => 'submitted',
        ]);

        $result->resultEntries()->createMany([
            [
                'candidate_id' => 1,
                'vote_count' => 50,
            ],
            [
                'candidate_id' => 2,
                'vote_count' => 25,
            ],
            [
                'candidate_id' => 3,
                'vote_count' => 10,
            ],
        ]);
    }
}