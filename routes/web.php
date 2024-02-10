<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\User\CheckoutController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\User\DashboardController as UserDashboard;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\CheckoutController as AdminCheckout;
use App\Http\Controllers\Admin\DiscountController as AdminDiscount;
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

// Socialite Routes
Route::get('/login/google', [UserController::class, 'google'])->name('login.google');
Route::get('/auth/google/callback', [UserController::class, 'googleCallback'])->name('login.google.callback');

// Midtrans Route
Route::get('/payment/success', [CheckoutController::class, 'midtransCallback']);
Route::post('/payment/success', [CheckoutController::class, 'midtransCallback']);

Route::middleware(['auth'])->group(function () {
    // Checkout Routes
    Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success')->middleware('ensureUserRole:user');
    Route::get('/checkout/{camp:slug}', [CheckoutController::class, 'create'])->name('checkout.create')->middleware('ensureUserRole:user');
    Route::post('/checkout/{camp}', [CheckoutController::class, 'store'])->name('checkout.create')->middleware('ensureUserRole:user');

    // User Dahsboard
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');

    // User Dashboard
    Route::prefix('/user/dashboard')->name('user.')->middleware('ensureUserRole:user')->group(function () {
        Route::get('/', [UserDashboard::class, 'index'])->name('dashboard');
    });

    // Admin Dashboard
    Route::prefix('/admin/dashboard')->name('admin.')->middleware('ensureUserRole:admin')->group(function () {
        Route::get('/', [AdminDashboard::class, 'index'])->name('dashboard');

        // admin checkout
        Route::post('checkout/{checkout}', [AdminCheckout::class, 'update'])->name('checkout.update');

        // admin discount
        Route::resource('discount', AdminDiscount::class);
    });

});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/auth.php';
