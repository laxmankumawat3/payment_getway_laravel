<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// routes/web.php

use App\Http\Controllers\PaymentController;

Route::get('checkout', [PaymentController::class, 'checkout'])->name('checkout');
Route::post('checkout', [PaymentController::class, 'processPayment'])->name('checkout.process');
Route::get('payment/success', [PaymentController::class, 'paymentSuccess'])->name('payment.success');
Route::get('payment/failure', [PaymentController::class, 'paymentFailure'])->name('payment.failure');

