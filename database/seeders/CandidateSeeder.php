<?php

namespace Database\Seeders;

use App\Models\Candidate;
use Illuminate\Database\Seeder;

/**
 * Seeds representative candidate records for VeriVote NG development.
 *
 * Provides controlled demo candidates linked to the seeded election for
 * testing result capture and deterministic verification workflows.
 */
class CandidateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Candidate::create([
            'election_id' => 1,
            'party_code' => 'PDA',
            'candidate_name' => 'Demo Candidate A',
        ]);

        Candidate::create([
            'election_id' => 1,
            'party_code' => 'PDB',
            'candidate_name' => 'Demo Candidate B',
        ]);

        Candidate::create([
            'election_id' => 1,
            'party_code' => 'PDC',
            'candidate_name' => 'Demo Candidate C',
        ]);
    }
}