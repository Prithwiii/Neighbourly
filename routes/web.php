<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LostItemController;
use App\Http\Controllers\AnnouncementController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});


Route::middleware(['auth'])->group(function () {

    
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

   
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

  


    Route::get('/lost-items/search', [LostItemController::class, 'search'])->name('lost-items.search');
});


require __DIR__.'/auth.php';