<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PublicVerificationController;
use App\Http\Controllers\QrVerificationController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\VerificationReportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| VeriVote NG Web Routes
|--------------------------------------------------------------------------
|
| This file defines the HTTP entry points for the VeriVote NG web
| application. Each route maps an incoming browser request to the controller
| responsible for that application workflow.
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
| - QR Verification: generates a scannable public verification reference.
| - Verification Report: presents a concise, shareable integrity report.
|
| This separation keeps the application's HTTP boundary predictable and
| allows the underlying verification and persistence logic to be tested
| independently of the web interface.
|
*/

/*
|--------------------------------------------------------------------------
| Public Home
|--------------------------------------------------------------------------
|
| GET /
| Displays the public VeriVote NG landing page.
|
| HomeController resolves the actual verified and flagged demo records
| from the database before rendering the landing page. This prevents
| hard-coded result IDs from becoming stale when database records change.
|
*/

Route::get('/', [HomeController::class, 'index'])
    ->name('home');

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
| Result model. ResultController loads the associated election,
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

/*
|--------------------------------------------------------------------------
| QR Verification
|--------------------------------------------------------------------------
|
| GET /verify/{result}/qr
| Generates a QR code containing the public verification URL for a result.
|
| Scanning the generated code directs the user to the read-only public
| verification page for that recorded result.
|
*/

Route::get('/verify/{result}/qr', [QrVerificationController::class, 'show'])
    ->name('public.verify.qr');

/*
|--------------------------------------------------------------------------
| Verification Report
|--------------------------------------------------------------------------
|
| GET /verify/{result}/report
| Presents a concise, read-only verification report for a recorded result.
|
| The report summarizes the result identity, verification status,
| cryptographic integrity, deterministic checks, discrepancies, and
| audit references without exposing observer submission controls.
|
*/

Route::get('/verify/{result}/report', [VerificationReportController::class, 'show'])
    ->name('public.verify.report');