<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

Route::domain('admin.{domain}')->as('admin.')->group(function () {
    Route::get('/', function () {
        return '[aDmiN] Tenant ID: '.tenant('id');
    });

    Route::middleware([
        'auth:sanctum',
        config('jetstream.auth_session'),
        'verified',
    ])->group(function () {
        Route::get('/dashboard', \App\Http\Controllers\Tenant\Admin\DashboardController::class)->name('dashboard');
        Route::resource('/brands', \App\Http\Controllers\Tenant\Admin\BrandController::class);
    });
});

Route::domain('seller.{domain}')->as('seller.')->group(function () {
    Route::get('/', function () {
        return '[SELLER] Tenant ID: '.tenant('id');
    });

    Route::middleware([
        'auth:sanctum',
        config('jetstream.auth_session'),
        'verified',
    ])->group(function () {
        Route::get('/dashboard', \App\Http\Controllers\Tenant\Seller\DashboardController::class)->name('dashboard');
    });
});

Route::get('/', function () {
    return 'This is your multi-tenant application. The id of the current tenant is '.tenant('id');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', \App\Http\Controllers\Tenant\DashboardController::class)->name('dashboard');
});
