<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InputDataController;
use App\Http\Controllers\UpdateController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Public API documentation page
Route::get('/api-docs', function () {
    return view('public.api-docs');
})->name('api-docs');

// Logout (GET) untuk handle link dari user menu / external
Route::get('/logout', [InputDataController::class, 'logout'])->name('logout');

// Update (edit/hapus) — gated by SHA1 hash, tidak butuh login user biasa
Route::prefix('update')->name('update.')->group(function () {
    Route::get('/', [UpdateController::class, 'gate'])->name('gate');
    Route::post('/', [UpdateController::class, 'verifyGate'])->name('gate.submit');
    Route::post('/lock', [UpdateController::class, 'lock'])->name('lock');

    Route::middleware('update.session')->group(function () {
        Route::get('/list', [UpdateController::class, 'index'])->name('index');
        Route::get('/{table}/{id}/edit', [UpdateController::class, 'edit'])->name('edit');
        Route::put('/{table}/{id}', [UpdateController::class, 'update'])->name('update');
        Route::delete('/{table}/{id}', [UpdateController::class, 'destroy'])->name('destroy');
    });
});

Route::prefix('input')->name('input.')->group(function () {
    Route::get('/login', [InputDataController::class, 'showLogin'])->name('login');
    Route::post('/login', [InputDataController::class, 'login'])->name('login.submit');
    Route::post('/logout', [InputDataController::class, 'logout'])->name('logout');

    Route::middleware('auth')->group(function () {
        Route::get('/', [InputDataController::class, 'index'])->name('index');

        Route::get('/analisa', [InputDataController::class, 'createAnalisa'])->name('analisa');
        Route::post('/analisa', [InputDataController::class, 'storeAnalisa'])->name('analisa.store');

        Route::get('/situasi', [InputDataController::class, 'createSituasi'])->name('situasi');
        Route::post('/situasi', [InputDataController::class, 'storeSituasi'])->name('situasi.store');

        Route::get('/rs', [InputDataController::class, 'createRs'])->name('rs');
        Route::post('/rs', [InputDataController::class, 'storeRs'])->name('rs.store');

        Route::get('/puskesmas', [InputDataController::class, 'createPuskesmas'])->name('puskesmas');
        Route::post('/puskesmas', [InputDataController::class, 'storePuskesmas'])->name('puskesmas.store');
    });
});

Route::prefix('users')->name('users.')->middleware('auth')->group(function () {
    Route::get('/gate', [UserController::class, 'gate'])->name('gate');
    Route::post('/gate', [UserController::class, 'verifyGate'])->name('gate.submit');
    Route::get('/', [UserController::class, 'index'])->name('index');
    Route::get('/create', [UserController::class, 'create'])->name('create');
    Route::post('/', [UserController::class, 'store'])->name('store');
    Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
    Route::post('/lock', [UserController::class, 'lock'])->name('lock');

    // API key management
    Route::post('/api-keys', [UserController::class, 'storeApiKey'])->name('api-keys.store');
    Route::delete('/api-keys/{apiKey}', [UserController::class, 'destroyApiKey'])->name('api-keys.destroy');
});

// Public read-only API (auth via X-API-Key)
Route::prefix('api/v1')->middleware('api.key')->group(function () {
    Route::get('/', [ApiController::class, 'meta']);
    Route::get('/kabupaten', [ApiController::class, 'kabupaten']);
    Route::get('/analisa', [ApiController::class, 'analisa']);
    Route::get('/situasi', [ApiController::class, 'situasi']);
    Route::get('/rs', [ApiController::class, 'rs']);
    Route::get('/puskesmas', [ApiController::class, 'puskesmas']);
});
