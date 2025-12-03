<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
// ADD THIS LINE: Import the Admin Controller Namespace
use App\Http\Controllers\Admin; 
use App\Http\Controllers\Admin\AdminMenuItemController;

// Public Routes
Route::get('/', fn() => redirect()->route('login'));

// ←←← FIX: Only the GET has name('login'), POST has no name
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
// ———————————————————————
// PROTECTED ROUTES
// ———————————————————————
Route::middleware('auth')->group(function () {

    // USER PAGES (Static View Routes)
    Route::view('/user/dashboard', 'UserDashboard')->name('user.dashboard');
    Route::view('/favorites', 'favorites')->name('favorites');
    Route::view('/cart', 'cart')->name('cart.index');
    Route::view('/orders/history', 'order-history')->name('orders.history');
    Route::view('/settings', 'settings')->name('settings');
    Route::view('/payment', 'payment')->name('payment.sheet');
    Route::get('/menu/{id}', function ($id) {
        return view('menu-details', ['id' => $id]);
    })->name('menu.details');


    // ADMIN PAGES
    
    // Legacy dashboard route (still useful for redirects/middleware)
    Route::get('/admin/dashboard', [AuthController::class, 'adminDashboard'])
             ->name('admin.dashboard');

    Route::prefix('admin')->name('admin.')->group(function () {
        
        // FIX: This loads the content view 'admin.index'
        Route::view('/dashboard', 'admin.index')->name('dashboard');
        
        // Static View Routes
        Route::view('/orders', 'admin.orders')->name('orders');
        Route::view('/users', 'admin.users')->name('users');
        Route::view('/sales', 'admin.sales')->name('sales');
        Route::view('/profile', 'admin.profile')->name('profile');

        // CRUD Resource Route for Menu Items
        // This generates: index (admin.menu.index), store, update, destroy
        // BLADE PAGE
    Route::get('/menu', [AdminMenuItemController::class, 'index'])->name('menu.index');

    // API ENDPOINTS — THESE ARE THE ONLY ONES THAT MATTER
    Route::get('/menu-items/json', [AdminMenuItemController::class, 'apiIndex']);

    // CREATE & UPDATE (both via POST, Laravel will handle _method)
    Route::post('/menu', [AdminMenuItemController::class, 'store']);
    Route::post('/menu/{menuItem}', [AdminMenuItemController::class, 'update']);  // ← ACCEPTS POST

    Route::delete('/menu/{menuItem}', [AdminMenuItemController::class, 'destroy']);

        Route::get('/menu-items/json', [Admin\AdminMenuItemController::class, 'apiIndex'])
        ->name('menu.json');
    });
});

// Fallback
Route::fallback(fn() => redirect()->route('login'));