<?php

namespace App\Http\Controllers;

use App\Models\Election;

/**
 * Handles polling-unit result submission requests for VeriVote NG.
 *
 * Coordinates HTTP requests for recording election results while keeping
 * validation, cryptographic processing, and verification logic in dedicated
 * application services.
 */
class ResultController extends Controller
{
    /**
     * Display the polling-unit result submission form.
     */
    public function create()
    {
        $elections = Election::where('status', 'active')
            ->latest('voting_date')
            ->get();

        return view('results.create', compact('elections'));
    }
}