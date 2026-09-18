<?php

namespace App\Http\Controllers;

use App\Models\Result;
use Illuminate\View\View;

/**
 * Handles public, read-only verification requests for VeriVote NG.
 *
 * Exposes the integrity status of a recorded result without providing
 * observer submission or verification controls.
 */
class PublicVerificationController extends Controller
{
    /**
     * Display the public integrity record for a result.
     */
    public function show(Result $result): View
    {
        $result->load([
            'election',
            'pollingUnit',
            'resultEntries.candidate',
            'evidence',
            'signature',
            'verificationChecks',
            'discrepancies',
            'auditLogs',
        ]);

        return view('public.verify', [
            'result' => $result,
        ]);
    }
}