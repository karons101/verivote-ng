<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PublicVerificationController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\VerificationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| VeriVote NG Web Routes
|--------------------------------------------------------------------------
|
| This file defines the HTTP entry points for the VeriVote NG web
| application. Each route maps an incoming browser request to the
| controller responsible for that application workflow.
|
| The routing layer intentionally remains thin: it defines URLs, HTTP
| methods, route parameters, and controller destinations while keeping
| business logic inside dedicated controllers and services.
|
| Current workflows include:
|
| - Home: public entry point for the application.
| - Observer Dashboard: displays available elections and observer context.
| - Result Capture: presents and accepts polling-unit result submissions.
| - Result Detail: displays the complete integrity record for a result.
| - Result Verification: executes deterministic integrity checks for a
|   specific recorded result.
| - Public Verification: exposes a read-only integrity view for a result.
|
| This separation keeps the application's HTTP boundary predictable and
| allows the underlying verification and persistence logic to be tested
| independently of the web interface.
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');

/*
|--------------------------------------------------------------------------
| Observer Dashboard
|--------------------------------------------------------------------------
|
| GET /dashboard
| Displays election data retrieved by DashboardController. This is the
| primary observer-facing entry point for reviewing election context before
| interacting with result and verification workflows.
|
*/

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');

/*
|--------------------------------------------------------------------------
| Result Capture
|--------------------------------------------------------------------------
|
| GET /results/create
| Presents the result-capture interface.
|
| POST /results
| Accepts a validated polling-unit result submission and delegates
| persistence to ResultController and ResultService.
|
| These routes form the entry point for recording the evidence data that
| VeriVote NG subsequently verifies.
|
*/

Route::get('/results/create', [ResultController::class, 'create'])
    ->name('results.create');

Route::post('/results', [ResultController::class, 'store'])
    ->name('results.store');

/*
|--------------------------------------------------------------------------
| Result Detail
|--------------------------------------------------------------------------
|
| GET /results/{result}
| Displays the complete integrity record for a specific result.
|
| Laravel's route model binding resolves {result} to the corresponding
| Result model identifier. ResultController loads the associated election,
| polling unit, candidate entries, evidence, signature, verification
| checks, discrepancies, and audit events for presentation.
|
| This route provides the primary detail view from which observers can
| understand the cryptographic and deterministic verification state of
| an individual polling-unit result.
|
*/

Route::get('/results/{result}', [ResultController::class, 'show'])
    ->name('results.show');

/*
|--------------------------------------------------------------------------
| Result Verification
|--------------------------------------------------------------------------
|
| POST /results/{result}/verify
| Triggers deterministic verification for a specific recorded result.
|
| Laravel's route model binding resolves {result} to the corresponding
| Result model. VerificationController delegates the verification workflow
| to VerificationCheckService, which executes the verification rules,
| persists their outcomes, creates structured discrepancies for failed
| checks, updates the result status, and records the verification event
| in the tamper-evident audit trail.
|
| Verification is deliberately exposed as a POST operation because it
| triggers an application action rather than merely retrieving a resource.
|
*/

Route::post('/results/{result}/verify', [VerificationController::class, 'verify'])
    ->name('results.verify');

/*
|--------------------------------------------------------------------------
| Public Verification
|--------------------------------------------------------------------------
|
| GET /verify/{result}
| Provides a read-only public integrity view for a recorded result.
|
| The public route deliberately exposes verification information without
| exposing observer submission controls or administrative actions.
|
*/

Route::get('/verify/{result}', [PublicVerificationController::class, 'show'])
    ->name('public.verify');