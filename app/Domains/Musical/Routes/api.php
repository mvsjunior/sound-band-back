<?php

use App\Domains\Musical\Http\Controllers\CategoryController;
use App\Domains\Musical\Http\Controllers\LyricsController;
use App\Domains\Musical\Http\Controllers\MusicController;
use App\Domains\Musical\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;

Route::prefix('musical')->group(function () {
    Route::get('/tags', [TagController::class, 'index']);
    Route::post('/tags/store', [TagController::class, 'store']);
    Route::put('/tags/update', [TagController::class, 'update']);
    Route::delete('/tags/delete', [TagController::class, 'delete']);
    // Route::post('/register', [AuthController::class, 'register']);

    Route::get('/lyrics', [LyricsController::class, 'index']);
    Route::post('/lyrics/store', [LyricsController::class, 'store']);
    Route::put('/lyrics/update', [LyricsController::class, 'update']);
    Route::delete('/lyrics/delete', [LyricsController::class, 'delete']);

    Route::get('/category', [CategoryController::class, 'index']);
    Route::post('/category/store', [CategoryController::class, 'store']);
    Route::put('/category/update', [CategoryController::class, 'update']);
    Route::delete('/category/delete', [CategoryController::class, 'delete']);

    Route::get('/music', [MusicController::class, 'index']);
    Route::post('/music/store', [MusicController::class, 'store']);
    Route::put('/music/update', [MusicController::class, 'update']);
    Route::delete('/music/delete', [MusicController::class, 'delete']);
});