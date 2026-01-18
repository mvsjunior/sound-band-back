<?php

use App\Domains\Musical\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;

Route::prefix('musical')->group(function () {
    Route::get('/tags', [TagController::class, 'index']);
    Route::post('/tags/store', [TagController::class, 'store']);
    Route::put('/tags/update', [TagController::class, 'update']);
    Route::delete('/tags/delete', [TagController::class, 'delete']);
    // Route::post('/register', [AuthController::class, 'register']);
});