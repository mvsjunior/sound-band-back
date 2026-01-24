<?php

use App\Domains\Musical\Http\Controllers\CategoryController;
use App\Domains\Musical\Http\Controllers\ChordController;
use App\Domains\Musical\Http\Controllers\LyricsController;
use App\Domains\Musical\Http\Controllers\MusicController;
use App\Domains\Musical\Http\Controllers\MusicianController;
use App\Domains\Musical\Http\Controllers\MusicianSkillController;
use App\Domains\Musical\Http\Controllers\SkillController;
use App\Domains\Musical\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;

Route::prefix('musical')->middleware('auth:api')->group(function () {
    Route::get('/tags', [TagController::class, 'index']);
    Route::get('/tags/show', [TagController::class, 'show']);
    Route::post('/tags/store', [TagController::class, 'store']);
    Route::put('/tags/update', [TagController::class, 'update']);
    Route::delete('/tags/delete', [TagController::class, 'delete']);
    // Route::post('/register', [AuthController::class, 'register']);

    Route::get('/lyrics', [LyricsController::class, 'index']);
    Route::get('/lyrics/show', [LyricsController::class, 'show']);
    Route::post('/lyrics/store', [LyricsController::class, 'store']);
    Route::put('/lyrics/update', [LyricsController::class, 'update']);
    Route::delete('/lyrics/delete', [LyricsController::class, 'delete']);

    Route::get('/category', [CategoryController::class, 'index']);
    Route::get('/category/show', [CategoryController::class, 'show']);
    Route::post('/category/store', [CategoryController::class, 'store']);
    Route::put('/category/update', [CategoryController::class, 'update']);
    Route::delete('/category/delete', [CategoryController::class, 'delete']);

    Route::get('/music', [MusicController::class, 'index']);
    Route::get('/music/show', [MusicController::class, 'show']);
    Route::post('/music/store', [MusicController::class, 'store']);
    Route::put('/music/update', [MusicController::class, 'update']);
    Route::delete('/music/delete', [MusicController::class, 'delete']);

    Route::get('/chords', [ChordController::class, 'index']);
    Route::get('/chords/show', [ChordController::class, 'show']);
    Route::post('/chords/store', [ChordController::class, 'store']);
    Route::put('/chords/update', [ChordController::class, 'update']);
    Route::delete('/chords/delete', [ChordController::class, 'delete']);

    Route::get('/musicians', [MusicianController::class, 'index']);
    Route::get('/musicians/show', [MusicianController::class, 'show']);
    Route::post('/musicians/store', [MusicianController::class, 'store']);
    Route::put('/musicians/update', [MusicianController::class, 'update']);
    Route::delete('/musicians/delete', [MusicianController::class, 'delete']);

    Route::get('/skills', [SkillController::class, 'index']);
    Route::get('/skills/show', [SkillController::class, 'show']);
    Route::post('/skills/store', [SkillController::class, 'store']);
    Route::put('/skills/update', [SkillController::class, 'update']);
    Route::delete('/skills/delete', [SkillController::class, 'delete']);

    Route::get('/musician-skills', [MusicianSkillController::class, 'index']);
    Route::get('/musician-skills/show', [MusicianSkillController::class, 'show']);
    Route::post('/musician-skills/store', [MusicianSkillController::class, 'store']);
    Route::put('/musician-skills/update', [MusicianSkillController::class, 'update']);
    Route::delete('/musician-skills/delete', [MusicianSkillController::class, 'delete']);
});
