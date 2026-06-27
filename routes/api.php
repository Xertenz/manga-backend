<?php

use App\Http\Controllers\Api\ChapterController;
use App\Http\Controllers\Api\MangaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::apiResource('mangas', MangaController::class);
Route::apiResource('chapters', ChapterController::class);
