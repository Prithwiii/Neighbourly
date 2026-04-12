<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LostItemController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\IssueController;
use App\Http\Controllers\MarketplaceController;
use App\Http\Controllers\MessageController;

/*
|--------------------------------------------------------------------------
| PUBLIC ENTRY POINT
|--------------------------------------------------------------------------
*/

// ALWAYS send users to login first
Route::get('/', function () {
    return redirect('/login');
});

/*
|--------------------------------------------------------------------------
| AUTHENTICATED ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    /*
    |------------------------------------------
    | HOME (AFTER LOGIN)
    |------------------------------------------
    */
    Route::get('/home', function () {
        return view('home');
    })->name('home');

    /*
    |------------------------------------------
    | UI MODE SWITCH
    |------------------------------------------
    */
    Route::get('/set-hub', function () {
        session(['ui_mode' => 'hub']);
        return redirect('/home');
    })->name('set.hub');

    Route::get('/set-app', function () {
        session(['ui_mode' => 'app']);
        return back();
    })->name('set.app');

    /*
    |------------------------------------------
    | PROFILE
    |------------------------------------------
    */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /*
    |------------------------------------------
    | LOST & FOUND
    |------------------------------------------
    */
    Route::get('/lost-items-hub', function () {
        return view('lost-items.hub');
    })->name('lost-items.hub');

    Route::get('/lost-items/search', [LostItemController::class, 'search'])
        ->name('lost-items.search');

    Route::resource('lost-items', LostItemController::class);

    /*
    |------------------------------------------
    | NEWS
    |------------------------------------------
    */
    Route::get('/news', [NewsController::class, 'index'])
        ->name('news.index');

    /*
    |------------------------------------------
    | ANNOUNCEMENTS
    |------------------------------------------
    */
    Route::get('/announcements', [AnnouncementController::class, 'index'])
        ->name('announcements.index');

    Route::get('/announcements/{announcement}', [AnnouncementController::class, 'show'])
        ->name('announcements.show');

    /*
    |------------------------------------------
    | ISSUES
    |------------------------------------------
    */
    Route::resource('issues', IssueController::class);

    /*
    |------------------------------------------
    | MARKETPLACE
    |------------------------------------------
    */
    Route::resource('marketplace', MarketplaceController::class);

    /*
    |------------------------------------------
    | MESSAGES
    |------------------------------------------
    */
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');

    Route::get('/messages/marketplace', [MessageController::class, 'marketplace'])
        ->name('messages.marketplace');

    Route::get('/messages/create/{marketplace}', [MessageController::class, 'create'])
        ->name('messages.create');

    Route::post('/messages/{marketplace}', [MessageController::class, 'store'])
        ->name('messages.store');

    /*
    |------------------------------------------
    | MAP
    |------------------------------------------
    */
    Route::get('/map', function () {
        return view('map.map');
    })->name('map');

    /*
    |------------------------------------------
    | ADMIN ONLY
    |------------------------------------------
    */
    Route::middleware(['admin'])->group(function () {

        Route::get('/announcements/create', [AnnouncementController::class, 'create'])
            ->name('announcements.create');

        Route::post('/announcements', [AnnouncementController::class, 'store'])
            ->name('announcements.store');

        Route::get('/announcements/{announcement}/edit', [AnnouncementController::class, 'edit'])
            ->name('announcements.edit');

        Route::patch('/announcements/{announcement}', [AnnouncementController::class, 'update'])
            ->name('announcements.update');

        Route::delete('/announcements/{announcement}', [AnnouncementController::class, 'destroy'])
            ->name('announcements.destroy');
    });

});

/*
|--------------------------------------------------------------------------
| AUTH ROUTES (Laravel Breeze)
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';