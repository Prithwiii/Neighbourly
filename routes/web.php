<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LostItemController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\IssueController;
use App\Http\Controllers\MarketplaceController;
use App\Http\Controllers\MessageController;
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
    

    // Search route for lost items
    Route::get('/lost-items/search', [LostItemController::class, 'search'])->name('lost-items.search');

    // Announcements - viewable by all authenticated users
    Route::get('/announcements', [AnnouncementController::class, 'index'])->name('announcements.index');
    Route::resource('lost-items', LostItemController::class);
    // Admin-only announcement creation (MUST come before parameter route)
    Route::middleware(['admin'])->get('/announcements/create', [AnnouncementController::class, 'create'])->name('announcements.create');
    //map route
    Route::get('/map', function () {
    return view('map.map');
    })->middleware('auth')->name('map');
    // Show specific announcement (all authenticated users)
    Route::get('/announcements/{announcement}', [AnnouncementController::class, 'show'])->name('announcements.show');

    // Community Issues routes
    Route::get('/issues', [IssueController::class, 'index'])->name('issues.index');
    Route::get('/issues/create', [IssueController::class, 'create'])->name('issues.create');
    Route::post('/issues', [IssueController::class, 'store'])->name('issues.store');
    Route::post('/issues/{issue}/vote', [IssueController::class, 'vote'])->name('issues.vote');
    Route::post('/issues/{issue}/report-fake', [IssueController::class, 'reportFake'])->name('issues.report-fake');
    Route::post('/issues/{issue}/verify', [IssueController::class, 'verify'])->name('issues.verify');
    Route::delete('/issues/{issue}', [IssueController::class, 'destroy'])->name('issues.destroy');

    // Marketplace routes
    Route::resource('marketplace', \App\Http\Controllers\MarketplaceController::class);

    // Message routes
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/marketplace', [MessageController::class, 'marketplace'])->name('messages.marketplace');
    Route::get('/messages/create/{marketplace}', [MessageController::class, 'create'])->name('messages.create');
    Route::post('/messages/{marketplace}', [MessageController::class, 'store'])->name('messages.store');

    // Admin-only routes
    Route::middleware(['admin'])->group(function () {
        // Example admin route - you can add more admin features here
        Route::get('/admin', function () {
            return view('admin.dashboard');
        })->name('admin.dashboard');

        // Announcement management for admins
        Route::post('/announcements', [AnnouncementController::class, 'store'])->name('announcements.store');
        Route::get('/announcements/{announcement}/edit', [AnnouncementController::class, 'edit'])->name('announcements.edit');
        Route::patch('/announcements/{announcement}', [AnnouncementController::class, 'update'])->name('announcements.update');
        Route::delete('/announcements/{announcement}', [AnnouncementController::class, 'destroy'])->name('announcements.destroy');
    });
});

// Include Breeze auth routes
require __DIR__.'/auth.php';