<?php

use App\Http\Controllers\IngredientPurchaseController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RevenueController;
use App\Models\IngredientPurchase;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthUserController;
use App\Http\Controllers\LaporanController;

Route::get('/', function () {
    return view('loginpage');
});
Route::get('/login', [AuthUserController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthUserController::class, 'login']);

Route::get('/dashboardOwner', [AuthUserController::class, 'showDashboardOwner'])->name('dashboardOwner');
Route::middleware( ['auth:sanctum', 'web'])->get('/api/dashboard-overview', [LaporanController::class, 'dashboardOverview']);
Route::middleware( ['auth:sanctum', 'web'])->get('/api/products', [ProductController::class, 'viewProducts']);
Route::middleware( ['auth:sanctum', 'web'])->post('/api/products', [ProductController::class, 'addProducts']);
Route::middleware( ['auth:sanctum', 'web'])->get('/api/saldo/add', [RevenueController::class, 'add']);
Route::middleware( ['auth:sanctum', 'web'])->get('/api/purchases', [IngredientPurchaseController::class, 'store']);
Route::middleware( ['auth:sanctum', 'web'])->get('/api/purchases', [IngredientPurchaseController::class, 'index']);