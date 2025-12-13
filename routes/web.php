<?php

use Illuminate\Support\Facades\Route;

// --- CONTROLLERS ---
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\AdminMenuItemController;
use App\Http\Controllers\Admin\AdminUsersController;
use App\Http\Controllers\User\UserDashboardController;
use App\Http\Controllers\User\FavoriteController;
use App\Http\Controllers\User\UserSettingsController;
use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\OrderController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\User\NotificationController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminSalesController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\AdminSettingsController;

// ====================================================
//  PUBLIC ROUTES
// ====================================================
Route::get('/', fn() => redirect()->route('login'));

// Authentication
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// ====================================================
//  PROTECTED ROUTES (Login Required)
// ====================================================
Route::middleware('auth')->group(function () {

    // ------------------------------------------------
    //  USER SECTION
    // ------------------------------------------------
    
    // 1. Dashboard & Menu
    Route::get('/user/dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard');
    Route::get('/user/menu/json', [UserDashboardController::class, 'getMenuJson'])->name('user.menu.json');
    Route::post('/user/rate-item', [UserDashboardController::class, 'rateItem'])->middleware('auth');

    // 2. Favorites
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites');
    Route::get('/user/favorites/json', [FavoriteController::class, 'getFavoritesJson'])->name('favorites.json');
    Route::post('/user/favorites/toggle', [FavoriteController::class, 'toggle'])->name('favorites.toggle');

    // 3. Settings (User Profile)
    Route::get('/settings', [UserSettingsController::class, 'index'])->name('settings');
    Route::put('/user/settings/update', [UserSettingsController::class, 'update'])->name('settings.update');
    Route::put('/user/settings/password', [UserSettingsController::class, 'updatePassword'])->name('settings.password');
    Route::delete('/user/settings/delete', [UserSettingsController::class, 'deleteAccount'])->name('settings.delete');

    // 4. Cart System
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index'); 
    Route::get('/user/cart/json', [CartController::class, 'getCartJson']);
    Route::post('/user/cart/add', [CartController::class, 'addToCart']);
    Route::patch('/user/cart/update/{id}', [CartController::class, 'updateQuantity']);
    Route::delete('/user/cart/remove/{id}', [CartController::class, 'removeItem']);
    Route::delete('/user/cart/clear', [CartController::class, 'clearCart']);
    
    // 5. Checkout & Orders
    Route::get('/payment', [OrderController::class, 'showPaymentPage'])->name('payment.sheet');
    Route::post('/user/order/place', [OrderController::class, 'placeOrder'])->name('order.place');
    Route::get('/orders/history', [OrderController::class, 'index'])->name('orders.history');

    // Menu Details Page
    Route::get('/menu/{id}', function ($id) {
        return view('menu-details', ['id' => $id]);
    })->name('menu.details');

    // ------------------------------------------------
    //  NOTIFICATIONS SECTION
    // ------------------------------------------------
    
    // 1. View All Notifications Page
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.all');

    // 2. Mark All as Read (Action)
    Route::get('/notifications/mark-read', [NotificationController::class, 'markAllRead'])->name('notifications.read');


    // ------------------------------------------------
    //  ADMIN SECTION
    // ------------------------------------------------
    Route::prefix('admin')->name('admin.')->group(function () {
        
        // 1. Dashboard & Reports
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        
        // Sales Report
        Route::get('/sales', [AdminSalesController::class, 'index'])->name('sales');
        Route::get('/sales/json', [AdminSalesController::class, 'getSalesData'])->name('sales.json');

        // Admin Profile Management
        Route::get('/profile', [AdminProfileController::class, 'index'])->name('profile');
        Route::put('/profile/update', [AdminProfileController::class, 'update'])->name('profile.update');
        Route::put('/profile/password', [AdminProfileController::class, 'updatePassword'])->name('profile.password');

        // Global Settings
        Route::get('/settings', [AdminSettingsController::class, 'index'])->name('settings');
        Route::put('/settings', [AdminSettingsController::class, 'update'])->name('settings.update');

        // 2. User Management
        Route::get('/users', [AdminUsersController::class, 'index'])->name('users');
        Route::get('/users/json', [AdminUsersController::class, 'apiIndex'])->name('users.json');
        Route::put('/users/{id}', [AdminUsersController::class, 'update'])->name('users.update');
        Route::patch('/users/{id}/toggle-block', [AdminUsersController::class, 'toggleBlock'])->name('users.block');
        Route::delete('/users/{id}', [AdminUsersController::class, 'destroy'])->name('users.destroy');

        // 3. Menu Management
        Route::get('/menu', [AdminMenuItemController::class, 'index'])->name('menu.index');
        Route::get('/menu-items/json', [AdminMenuItemController::class, 'apiIndex'])->name('menu.json');
        Route::post('/menu', [AdminMenuItemController::class, 'store'])->name('menu.store');
        Route::put('/menu/{id}', [AdminMenuItemController::class, 'update'])->name('menu.update');
        Route::delete('/menu/{id}', [AdminMenuItemController::class, 'destroy'])->name('menu.destroy');

        // 4. Orders Management
        Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders');
        Route::get('/orders/json', [AdminOrderController::class, 'getOrdersJson']);
        Route::patch('/orders/{id}/status', [AdminOrderController::class, 'updateStatus']);
        Route::patch('/orders/{id}/payment', [AdminOrderController::class, 'togglePayment']);
    });
});

Route::fallback(fn() => redirect()->route('login'));