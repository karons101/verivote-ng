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
 * persisted verification outcomes while keeping business logic in services.
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
     * Store a validated polling-unit result.
     */
    public function store(
        StoreResultRequest $request,
        ResultService $resultService
    ): RedirectResponse {
        $result = $resultService->create($request->validated());

        return redirect()
            ->route('results.create')
            ->with('success', "Result #{$result->id} recorded successfully.");
    }
}