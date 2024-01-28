<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\CheckoutController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('index');
})->name('home');

Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
Route::get('/checkout/{camp:slug}', [CheckoutController::class, 'create'])->name('checkout.create');
Route::post('/checkout/{camp}', [CheckoutController::class, 'store'])->name('checkout.create');

// Socialite Routes
Route::get('/login/google', [UserController::class, 'google'])->name('login.google');
Route::get('/auth/google/callback', [UserController::class, 'googleCallback'])->name('login.google.callback');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/auth.php';
