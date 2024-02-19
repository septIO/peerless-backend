<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('v1')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/raids', [App\Http\Controllers\RouteController::class, 'WoWAuditRaids'])->name('import.raids');



        Route::post('/keys', [App\Http\Controllers\RouteController::class, 'storeKey'])->name('keys.store');
        Route::get('/keys', [App\Http\Controllers\RouteController::class, 'getKeys'])->name('keys.get');
    });
});
