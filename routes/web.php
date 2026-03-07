<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LostItemController;
use Illuminate\Support\Facades\Route;

// Public welcome page
Route::get('/', function () {
    return view('welcome');
});

// Authenticated routes
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Lost Items routes
    Route::resource('lost-items', LostItemController::class);

    // Search route for lost items
    Route::get('/lost-items/search', [LostItemController::class, 'search'])->name('lost-items.search');
});

// Include Breeze auth routes
require __DIR__.'/auth.php';