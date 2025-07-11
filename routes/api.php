<?php

use App\Http\Controllers\UrlController;
use App\Http\Controllers\StatsController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('/shorten', [UrlController::class, 'shorten']);
    Route::post('/bulk-shorten', [UrlController::class, 'bulkShorten']);
    Route::get('/stats/{code}', [StatsController::class, 'show']);
});

// Maintain backward compatibility
Route::post('/shorten', [UrlController::class, 'shorten']);
Route::get('/stats/{code}', [StatsController::class, 'show']);