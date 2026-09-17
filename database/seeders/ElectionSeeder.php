<?php

namespace Database\Seeders;

use App\Models\Election;
use Illuminate\Database\Seeder;

/**
 * Seeds the database with a representative election record for development.
 *
 * Provides controlled demo data for testing the observer dashboard and
 * subsequent result-verification workflows.
 */
class ElectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Election::create([
            'code' => 'VVNG-2026-GEN',
            'title' => 'VeriVote NG General Election Demo',
            'election_type' => 'general',
            'voting_date' => '2026-09-21',
            'status' => 'active',
        ]);
    }
}