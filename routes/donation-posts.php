<?php

use App\Http\Controllers\Donations\AdminDonationPostController;
use App\Http\Controllers\Donations\DonationPostController;
use App\Http\Controllers\Payments\DonationPostPaymentController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/donation-posts', [DonationPostController::class, 'index'])
        ->name('donation-posts.index');

    Route::get('/donation-posts/create', [DonationPostController::class, 'create'])
        ->name('donation-posts.create');

    Route::post('/donation-posts', [DonationPostController::class, 'store'])
        ->name('donation-posts.store');

    Route::get('/donation-posts/{donationPost}', [DonationPostController::class, 'show'])
        ->name('donation-posts.show');

    Route::get('/donation-posts/{donationPost}/proof/{index}', [DonationPostController::class, 'downloadProof'])
        ->name('donation-posts.proof.download');

    Route::post('/donation-posts/{donationPost}/donate', [DonationPostPaymentController::class, 'donate'])
        ->name('payments.posts.donate');

    Route::middleware('admin')->group(function () {
        Route::get('/admin/donation-posts', [AdminDonationPostController::class, 'index'])
            ->name('admin.donation-posts.index');

        Route::post('/admin/donation-posts/{donationPost}/approve', [AdminDonationPostController::class, 'approve'])
            ->name('admin.donation-posts.approve');

        Route::post('/admin/donation-posts/{donationPost}/reject', [AdminDonationPostController::class, 'reject'])
            ->name('admin.donation-posts.reject');
    });
});

Route::get('/donation-posts/payments/success', [DonationPostPaymentController::class, 'success'])
    ->name('payments.posts.success');

Route::get('/donation-posts/payments/fail', [DonationPostPaymentController::class, 'fail'])
    ->name('payments.posts.fail');

Route::get('/donation-posts/payments/cancel', [DonationPostPaymentController::class, 'cancel'])
    ->name('payments.posts.cancel');

Route::post('/donation-posts/payments/ipn', [DonationPostPaymentController::class, 'ipn'])
    ->name('payments.posts.ipn');

Route::get('/donation-posts/payments/receipt/{orderId}', [DonationPostPaymentController::class, 'receipt'])
    ->name('payments.posts.receipt');
