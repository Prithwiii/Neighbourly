<?php

use App\Http\Controllers\AdminServiceProviderController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\AdminEmergencyAlertController;
use App\Http\Controllers\EmergencyAlertController;
use App\Http\Controllers\IssueController;
use App\Http\Controllers\LostItemController;
use App\Http\Controllers\MarketplaceController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ProfileController;
use App\Models\DonationPost;
use App\Models\EmergencyAlert;
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
        $pendingAlertCount = auth()->user()->isAdmin()
            ? EmergencyAlert::where('status', 'pending')->count()
            : 0;

        return view('dashboard', compact('pendingAlertCount'));
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
        //return back();
        return redirect('/home');
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

    Route::get('/emergency-alerts', [EmergencyAlertController::class, 'index'])
        ->name('emergency-alerts.index');
    Route::get('/emergency-alerts/create', [EmergencyAlertController::class, 'create'])
        ->name('emergency-alerts.create');
    Route::post('/emergency-alerts', [EmergencyAlertController::class, 'store'])
        ->name('emergency-alerts.store');
    Route::get('/emergency-alerts/{emergencyAlert}', [EmergencyAlertController::class, 'show'])
        ->name('emergency-alerts.show');
    Route::delete('/emergency-alerts/{emergencyAlert}', [EmergencyAlertController::class, 'destroy'])
        ->name('emergency-alerts.destroy');
    Route::post('/emergency-alerts/{emergencyAlert}/comments', [EmergencyAlertController::class, 'storeComment'])
        ->name('emergency-alerts.comments.store');

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
        Route::get('/admin/dashboard', function () {
            $pendingAlertCount = EmergencyAlert::where('status', 'pending')->count();
            $pendingDonationCount = DonationPost::where('approval_status', 'pending')->count();

            return view('admin.dashboard', compact('pendingAlertCount', 'pendingDonationCount'));
        })->name('admin.dashboard');

        Route::post('/issues/{issue}/verify', [IssueController::class, 'verify'])->name('issues.verify');

        Route::get('/admin/emergency-alerts', [AdminEmergencyAlertController::class, 'index'])
            ->name('admin.emergency-alerts.index');
        Route::post('/admin/emergency-alerts/{emergencyAlert}/approve', [AdminEmergencyAlertController::class, 'approve'])
            ->name('admin.emergency-alerts.approve');
        Route::post('/admin/emergency-alerts/{emergencyAlert}/reject', [AdminEmergencyAlertController::class, 'reject'])
            ->name('admin.emergency-alerts.reject');

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
    /*
    |------------------------------------------
    | ANNOUNCEMENTS
    |------------------------------------------
    */
    Route::get('/announcements', [AnnouncementController::class, 'index'])
        ->name('announcements.index');

    Route::get('/announcements/{announcement}', [AnnouncementController::class, 'show'])
        ->name('announcements.show');

});

require __DIR__.'/auth.php';
require __DIR__.'/payments.php';
require __DIR__.'/donation-posts.php';