<?php

namespace App\Http\Controllers;

use App\Models\Result;
use App\Services\VerificationCheckService;
use Illuminate\Http\RedirectResponse;

/**
 * Handles result verification requests for VeriVote NG.
 *
 * Delegates deterministic verification to the verification service and
 * redirects the observer back with the latest verification outcome.
 */
class VerificationController extends Controller
{
    /**
     * Run deterministic verification for a result.
     */
    public function verify(
        Result $result,
        VerificationCheckService $verificationCheckService
    ): RedirectResponse {
        $verificationCheckService->run($result);

        return redirect()
            ->route('results.create')
            ->with('success', "Verification completed for Result #{$result->id}.");
    }
}