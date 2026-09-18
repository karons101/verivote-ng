<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreResultRequest;
use App\Models\Election;
use App\Models\Result;
use App\Services\ResultService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * Handles polling-unit result submission and review requests for VeriVote NG.
 *
 * Coordinates HTTP requests for recording results and retrieving their
 * persisted verification, discrepancy, evidence, and audit information.
 */
class ResultController extends Controller
{
    /**
     * Display the polling-unit result submission form.
     */
    public function create(): View
    {
        $elections = Election::where('status', 'active')
            ->latest('voting_date')
            ->get();

        $result = Result::with('verificationChecks')
            ->latest()
            ->first();

        return view('results.create', compact('elections', 'result'));
    }

    /**
     * Store a validated polling-unit result and its source evidence.
     */
    public function store(
        StoreResultRequest $request,
        ResultService $resultService
    ): RedirectResponse {
        $validated = $request->validated();

        $result = $resultService->create(
            $validated,
            $request->file('evidence')
        );

        return redirect()
            ->route('results.create')
            ->with('success', "Result #{$result->id} recorded successfully.");
    }

    /**
     * Display the complete integrity record for a result.
     *
     * Laravel route model binding resolves the requested result before this
     * method executes, ensuring the controller works with the persisted
     * Result model rather than manually resolving its identifier.
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

        return view('results.show', [
            'result' => $result,
        ]);
    }
}