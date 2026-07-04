<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\CmsPageController;
use App\Http\Controllers\SSLCommerzController;
use App\Http\Controllers\Admin;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/projects', [ProjectController::class, 'index']);
Route::get('/projects/{slug}', [ProjectController::class, 'show']);
Route::get('/gallery', [GalleryController::class, 'index']);
Route::get('/videos', [VideoController::class, 'index']);
Route::get('/pages/{slug}', [CmsPageController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/donations', [DonationController::class, 'index']);
    Route::post('/donations/initiate', [DonationController::class, 'initiate']);
    Route::get('/donations/stats', [DonationController::class, 'stats']);
    Route::get('/donations/yearly-stats', [DonationController::class, 'yearlyStats']);
});

Route::prefix('sslcommerz')->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class])->group(function () {
    Route::post('/success', [SSLCommerzController::class, 'success']);
    Route::post('/cancel', [SSLCommerzController::class, 'cancel']);
    Route::post('/fail', [SSLCommerzController::class, 'fail']);
    Route::post('/ipn', [SSLCommerzController::class, 'ipn']);
});

Route::prefix('admin')->group(function () {
    Route::post('/login', [Admin\AuthController::class, 'login']);

    Route::middleware(['auth:sanctum', 'admin'])->group(function () {
        Route::get('/dashboard', [Admin\DashboardController::class, 'index']);

        Route::apiResource('projects', Admin\ProjectController::class);
        Route::get('donations', [Admin\DonationController::class, 'index']);
        Route::get('donations/{id}', [Admin\DonationController::class, 'show']);

        Route::get('gallery', [Admin\GalleryController::class, 'index']);
        Route::post('gallery', [Admin\GalleryController::class, 'store']);
        Route::put('gallery/{id}', [Admin\GalleryController::class, 'update']);
        Route::delete('gallery/{id}', [Admin\GalleryController::class, 'destroy']);

        Route::get('videos', [Admin\VideoController::class, 'index']);
        Route::post('videos', [Admin\VideoController::class, 'store']);
        Route::put('videos/{id}', [Admin\VideoController::class, 'update']);
        Route::delete('videos/{id}', [Admin\VideoController::class, 'destroy']);

        Route::get('cms-pages', [Admin\CmsPageController::class, 'index']);
        Route::post('cms-pages', [Admin\CmsPageController::class, 'store']);
        Route::get('cms-pages/{id}', [Admin\CmsPageController::class, 'show']);
        Route::put('cms-pages/{id}', [Admin\CmsPageController::class, 'update']);
        Route::delete('cms-pages/{id}', [Admin\CmsPageController::class, 'destroy']);

        Route::get('users', [Admin\UserController::class, 'index']);
        Route::post('users', [Admin\UserController::class, 'store']);
        Route::get('users/{id}', [Admin\UserController::class, 'show']);
        Route::put('users/{id}', [Admin\UserController::class, 'update']);
        Route::delete('users/{id}', [Admin\UserController::class, 'destroy']);

        Route::get('roles', [Admin\RoleController::class, 'index']);
        Route::post('roles', [Admin\RoleController::class, 'store']);
        Route::get('roles/permissions', [Admin\RoleController::class, 'permissions']);
        Route::get('roles/{role}', [Admin\RoleController::class, 'show']);
        Route::put('roles/{role}', [Admin\RoleController::class, 'update']);
        Route::delete('roles/{role}', [Admin\RoleController::class, 'destroy']);
    });
});
