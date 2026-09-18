<?php

namespace App\Http\Controllers;

use App\Models\Result;
use App\Services\VerificationCheckService;
use Illuminate\Http\RedirectResponse;

/**
 * Handles result verification requests for VeriVote NG.
 *
 * Delegates deterministic verification to the verification service and
 * redirects the observer back to the verified result's integrity record.
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
            ->route('results.show', ['result' => $result->id])
            ->with('success', "Verification completed for Result #{$result->id}.");
    }
}