<?php

use App\Http\Controllers\AdminServiceProviderController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\IssueController;
use App\Http\Controllers\LostItemController;
use App\Http\Controllers\MarketplaceController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServiceProviderController;
use App\Http\Controllers\ServiceProviderReviewController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| PUBLIC ENTRY POINT
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect('/login');
});

// Public service provider directory (only verified profiles are shown)
Route::get('/services', [ServiceProviderController::class, 'index'])->name('services.index');
Route::get('/services/{serviceProvider}', [ServiceProviderController::class, 'show'])->name('services.show');

/*
|--------------------------------------------------------------------------
| AUTHENTICATED ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/home', function () {
        return view('home');
    })->name('home');

    Route::get('/set-hub', function () {
        session(['ui_mode' => 'hub']);
        return redirect('/home');
    })->name('set.hub');

    Route::get('/set-app', function () {
        session(['ui_mode' => 'app']);
        return back();
    })->name('set.app');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/lost-items-hub', function () {
        return view('lost-items.hub');
    })->name('lost-items.hub');

    Route::get('/lost-items/search', [LostItemController::class, 'search'])
        ->name('lost-items.search');

    Route::resource('lost-items', LostItemController::class);

    Route::get('/news', [NewsController::class, 'index'])
        ->name('news.index');

    Route::get('/announcements', [AnnouncementController::class, 'index'])
        ->name('announcements.index');

    Route::get('/announcements/{announcement}', [AnnouncementController::class, 'show'])
        ->name('announcements.show');

    Route::resource('issues', IssueController::class);
    Route::post('/issues/{issue}/vote', [IssueController::class, 'vote'])->name('issues.vote');
    Route::post('/issues/{issue}/report-fake', [IssueController::class, 'reportFake'])->name('issues.report-fake');
    Route::resource('marketplace', MarketplaceController::class);

    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/marketplace', [MessageController::class, 'marketplace'])
        ->name('messages.marketplace');
    Route::get('/messages/direct/{user}', [MessageController::class, 'direct'])
        ->name('messages.direct.create');
    Route::post('/messages/direct/{user}', [MessageController::class, 'storeDirect'])
        ->name('messages.direct.store');
    Route::get('/messages/providers/{serviceProvider}', [MessageController::class, 'provider'])
        ->name('messages.provider.create');
    Route::post('/messages/providers/{serviceProvider}', [MessageController::class, 'storeProvider'])
        ->name('messages.provider.store');
    Route::get('/messages/create/{marketplace}', [MessageController::class, 'create'])
        ->name('messages.create');
    Route::post('/messages/{marketplace}', [MessageController::class, 'store'])
        ->name('messages.store');

    Route::get('/map', function () {
        return view('map.map');
    })->name('map');

    // Service provider self-registration and account actions
    Route::get('/join-provider', [ServiceProviderController::class, 'create'])->name('providers.create');
    Route::post('/join-provider', [ServiceProviderController::class, 'store'])->name('providers.store');
    Route::get('/provider/application', [ServiceProviderController::class, 'application'])->name('providers.application');
    Route::post('/provider/{serviceProvider}/verify-phone', [ServiceProviderController::class, 'verifyPhone'])->name('providers.verify-phone');
    Route::patch('/provider/{serviceProvider}/availability', [ServiceProviderController::class, 'updateAvailability'])->name('providers.update-availability');

    // Ratings and reviews for verified providers
    Route::post('/services/{serviceProvider}/reviews', [ServiceProviderReviewController::class, 'store'])->name('services.reviews.store');

    Route::middleware(['admin'])->group(function () {
        Route::post('/issues/{issue}/verify', [IssueController::class, 'verify'])->name('issues.verify');

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

        // Provider verification queue
        Route::get('/admin/providers', [AdminServiceProviderController::class, 'index'])->name('admin.providers.index');
        Route::post('/admin/providers/{serviceProvider}/approve', [AdminServiceProviderController::class, 'approve'])->name('admin.providers.approve');
        Route::post('/admin/providers/{serviceProvider}/reject', [AdminServiceProviderController::class, 'reject'])->name('admin.providers.reject');
    });
});

require __DIR__.'/auth.php';