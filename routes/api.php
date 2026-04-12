<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LostItemApiController;
use App\Http\Controllers\AnnouncementApiController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\MapController;

Route::get('/lost-items', [LostItemApiController::class, 'index']);
Route::post('/lost-items', [LostItemApiController::class, 'store']);
Route::get('/lost-items/search', [LostItemApiController::class, 'search']);
Route::get('/lost-items/{id}', [LostItemApiController::class, 'show']);

Route::get('/announcements', [AnnouncementApiController::class, 'index']);
Route::post('/announcements', [AnnouncementApiController::class, 'store']);
Route::get('/announcements/{id}', [AnnouncementApiController::class, 'show']);

Route::get('/news', [NewsController::class, 'apiIndex']);

// Route::middleware(['auth', 'admin'])->group(function () {
//     Route::post('/announcements', [AnnouncementApiController::class, 'store']);
// });

Route::get('/home-radius', [MapController::class, 'homeRadius']);   
