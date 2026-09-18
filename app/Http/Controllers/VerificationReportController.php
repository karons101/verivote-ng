<?php

namespace App\Http\Controllers;

use App\Models\Result;
use Illuminate\View\View;

/**
 * Handles verification report requests for VeriVote NG.
 *
 * Presents a concise, read-only report containing the recorded result,
 * cryptographic integrity state, deterministic verification outcomes,
 * discrepancies, and audit reference information.
 */
class VerificationReportController extends Controller
{
    /**
     * Display the verification report for a recorded result.
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

        return view('reports.verification', [
            'result' => $result,
        ]);
    }
}