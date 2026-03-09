<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LostItemApiController;

Route::get('/lost-items', [LostItemApiController::class, 'index']);
Route::post('/lost-items', [LostItemApiController::class, 'store']);
Route::get('/lost-items/search', [LostItemApiController::class, 'search']);
Route::get('/lost-items/{id}', [LostItemApiController::class, 'show']);