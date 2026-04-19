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
<<<<<<< HEAD
use App\Http\Controllers\JobController;
use App\Http\Controllers\JobConfirmationController;
use App\Http\Controllers\EnlistingController;
=======
use App\Http\Controllers\PostController;
use App\Http\Controllers\EmergencyController;
use App\Http\Controllers\LocationController;
>>>>>>> c7877293008d94ef876af4351321ca18697edb73
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
<<<<<<< HEAD
    Route::get('/home', function () {
        return view('home');
    })->name('home');
=======
    //Route::get('/dashboard', function () {
      //  return view('dashboard');
    //})->name('dashboard');
>>>>>>> c7877293008d94ef876af4351321ca18697edb73

    Route::get('/home', function () {
        return view('home');
    })->name('home');

    Route::get('/set-hub', function () {
        session(['ui_mode' => 'hub']);
        return redirect('/home');
    })->name('set.hub');

    Route::get('/set-app', function () {
        session(['ui_mode' => 'app']);
// <<<<<<< HEAD

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

    Route::get('/community', function () {
        return view('posts.hub');
    })->name('posts.hub');

    Route::get('/community/all', [PostController::class, 'index'])->name('posts.index');
    Route::get('/community/create', function () {
           return view('posts.create');
    })->name('posts.create');
    
    Route::post('/community', [PostController::class, 'store'])->name('posts.store');

    Route::get('/news', [NewsController::class, 'index'])
        ->name('news.index');

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
     
    Route::post('/emergency', [EmergencyController::class, 'trigger']);

    Route::get('/notifications', function () {
         return view('notifications');
    })->name('notifications');



    Route::post('/update-location', [LocationController::class, 'update']);



    // Service provider self-registration and account actions
    Route::get('/join-provider', [ServiceProviderController::class, 'create'])->name('providers.create');
    Route::post('/join-provider', [ServiceProviderController::class, 'store'])->name('providers.store');
    Route::get('/provider/application', [ServiceProviderController::class, 'application'])->name('providers.application');
    Route::post('/provider/{serviceProvider}/verify-phone', [ServiceProviderController::class, 'verifyPhone'])->name('providers.verify-phone');
    Route::patch('/provider/{serviceProvider}/availability', [ServiceProviderController::class, 'updateAvailability'])->name('providers.update-availability');

    // Ratings and reviews for verified providers
    Route::post('/services/{serviceProvider}/reviews', [ServiceProviderReviewController::class, 'store'])->name('services.reviews.store');


    /*
    |------------------------------------------
    | Admin-only routes
    |------------------------------------------
    */

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

        // Job deletion for admin
        Route::delete('/admin/jobs/{job}', [JobController::class, 'destroy']);
        Route::delete('/admin/enlistings/{enlisting}', [EnlistingController::class, 'destroy']);
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


     /*
    |------------------------------------------
    | Microjobs
    |------------------------------------------
    */
    // Enlistings
    Route::resource('enlistings', EnlistingController::class)->only(['index', 'create', 'store', 'show', 'destroy']);
    Route::get('/enlistings', [EnlistingController::class, 'index'])->name('enlistings.index');
    Route::get('/enlistings/create', [EnlistingController::class, 'create'])->name('enlistings.create');
    Route::post('/enlistings', [EnlistingController::class, 'store'])->name('enlistings.store');
    Route::get('/enlistings/{enlisting}', [EnlistingController::class, 'show'])->name('enlistings.show');
    Route::delete('/enlistings/{enlisting}', [EnlistingController::class, 'destroy'])->name('enlistings.destroy');


    // Jobs
    Route::resource('jobs', JobController::class)->only(['index', 'create', 'store', 'show', 'destroy']);
    Route::get('/jobs', [JobController::class, 'index'])->name('jobs.index');
    Route::get('/jobs/create', [JobController::class, 'create'])->name('jobs.create');
    Route::post('/jobs', [JobController::class, 'store'])->name('jobs.store');
    Route::get('/jobs/{job}', [JobController::class, 'show'])->name('jobs.show');
    Route::delete('/jobs/{job}', [JobController::class, 'destroy'])->name('jobs.destroy');
    Route::post('/jobs/{job}/select-worker', [JobController::class, 'selectWorker'])->name('jobs.selectWorker');


    // Confirm job
    Route::post('/jobs/{job}/confirm', [JobConfirmationController::class, 'confirm'])->name('jobs.confirm');

});

require __DIR__.'/auth.php';