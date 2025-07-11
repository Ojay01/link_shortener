<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RedirectController;
use App\Http\Controllers\SiteController;

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::get('/', [SiteController::class, 'index'])->name('url-stats.index');
Route::post('/url-stats', [SiteController::class, 'store'])->name('url-stats.store');
Route::get('/url-stats/{url}', [SiteController::class, 'show'])->name('url-stats.show');
Route::patch('/url-stats/{url}/toggle', [SiteController::class, 'toggle'])->name('url-stats.toggle');

Route::get('/{code}', [RedirectController::class, 'redirect'])
    ->where('code', '[A-Za-z0-9_-]+')
    ->name('redirect');
