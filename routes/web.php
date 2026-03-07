<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LostItemController;

// Simple page for /lost
Route::get('/lost', function () {
    return 'Lost Item Page';
});

// Search must be BEFORE resource
Route::get('/lost-items/search', [LostItemController::class, 'search'])->name('lost-items.search');

// Resource routes for lost items
Route::resource('lost-items', LostItemController::class);