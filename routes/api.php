<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ChapterController;
use App\Http\Controllers\Api\MangaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

/*
Route::apiResource('mangas', MangaController::class);
Route::apiResource('chapters', ChapterController::class);
*/

Route::post('/login', [AuthController::class, 'login']);
Route::get('mangas', [MangaController::class, 'index']);
Route::get('mangas/{manga}', [MangaController::class, 'show']);

Route::get('/chapters', [ChapterController::class, 'index']);
Route::get('/chapters/{chapter}', [ChapterController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/chapters', [ChapterController::class, 'store']);
});
