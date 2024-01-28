<?php

use App\Http\Controllers\ProfileController;
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

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::get('/checkout', function () {
    return view('checkout');
})->name('checkout');


Route::get('/success-checkout', function () {
    return view('success_checkout');
})->name('success-checkout');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


// Socialite Routes
Route::get('/login/google', [UserController::class, 'google'])->name('login.google');
Route::get('/auth/google/callback', [UserController::class, 'googleCallback'])->name('login.google.callback');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
