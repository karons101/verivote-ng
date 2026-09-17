<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ResultController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');

Route::get('/results/create', [ResultController::class, 'create'])
    ->name('results.create');