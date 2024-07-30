<?php

use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PaymentController::class,'index'])->name('index');
Route::post('/checkout', [PaymentController::class,'checkout'])->name('checkout');
Route::get('/success', [PaymentController::class,'success'])->name('success');
