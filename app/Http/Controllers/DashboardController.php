<?php

namespace App\Http\Controllers;

use App\Models\Election;

/**
 * Handles the VeriVote NG observer dashboard.
 *
 * Retrieves election data from the database and passes it to the
 * dashboard view for presentation.
 */
class DashboardController extends Controller
{
    public function index()
    {
        $elections = Election::latest('voting_date')->get();

        return view('dashboard', compact('elections'));
    }
}