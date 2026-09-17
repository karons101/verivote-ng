<?php

namespace Database\Seeders;

use App\Models\PollingUnit;
use Illuminate\Database\Seeder;

/**
 * Seeds a representative polling unit for VeriVote NG development.
 *
 * Provides controlled demo data linked to the seeded election for testing
 * result capture and verification workflows.
 */
class PollingUnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PollingUnit::create([
            'election_id' => 1,
            'pu_code' => 'VVNG-PU-001',
            'state' => 'Delta',
            'lga' => 'Uvwie',
            'ward' => 'Ward 01',
            'name' => 'Demo Polling Unit 001',
            'registered_voters' => 500,
        ]);
    }
}