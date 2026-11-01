<?php

use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LeadController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Middleware\EnsureAdmin;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->middleware('throttle:6,1')->name('login.attempt');

    Route::middleware(EnsureAdmin::class)->group(function () {
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('/', DashboardController::class)->name('dashboard');

        Route::prefix('analytics')->name('analytics.')->group(function () {
            Route::get('/', [AnalyticsController::class, 'index'])->name('index');
            Route::get('live', [AnalyticsController::class, 'live'])->name('live');
            Route::get('visitors', [AnalyticsController::class, 'visitors'])->name('visitors');
            Route::get('visitors/data', [AnalyticsController::class, 'visitorData'])->name('visitors.data');
        });

        Route::resource('products', ProductController::class)->except('show');

        Route::prefix('leads/{type}')->whereIn('type', ['demos', 'quotes'])->group(function () {
            Route::get('/', [LeadController::class, 'index'])->name('leads.index');
            Route::get('{id}', [LeadController::class, 'show'])->where('id', '[A-Za-z0-9_-]+')->name('leads.show');
            Route::put('{id}', [LeadController::class, 'update'])->where('id', '[A-Za-z0-9_-]+')->name('leads.update');
            Route::delete('{id}', [LeadController::class, 'destroy'])->where('id', '[A-Za-z0-9_-]+')->name('leads.destroy');
        });
    });
});
