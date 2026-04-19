<?php

use App\Http\Controllers\Payments\DonationPaymentController;
use Illuminate\Support\Facades\Route;

Route::get('/donate', [DonationPaymentController::class, 'create'])
    ->name('payments.donations.create');

Route::post('/donate', [DonationPaymentController::class, 'store'])
    ->name('payments.donations.store');

Route::get('/donate/success', [DonationPaymentController::class, 'success'])
    ->name('payments.donations.success');

Route::get('/donate/fail', [DonationPaymentController::class, 'fail'])
    ->name('payments.donations.fail');

Route::get('/donate/cancel', [DonationPaymentController::class, 'cancel'])
    ->name('payments.donations.cancel');

Route::post('/donate/ipn', [DonationPaymentController::class, 'ipn'])
    ->name('payments.donations.ipn');

Route::get('/donate/{orderId}', [DonationPaymentController::class, 'show'])
    ->name('payments.donations.show');