<?php

namespace App\Http\Controllers;

use App\Models\Result;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Display the public VeriVote NG landing page.
     *
     * The landing page uses real database records for its demo links.
     * This prevents hard-coded result IDs from becoming stale when the
     * production database is seeded or new verification records are created.
     */
    public function index(): View
    {
        $verifiedDemo = Result::query()
            ->where('status', 'verified')
            ->latest('id')
            ->first();

        $flaggedDemo = Result::query()
            ->where('status', 'flagged')
            ->latest('id')
            ->first();

        return view('welcome', [
            'verifiedDemo' => $verifiedDemo,
            'flaggedDemo' => $flaggedDemo,
        ]);
    }
}