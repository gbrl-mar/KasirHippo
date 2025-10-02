<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthUserController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\IngredientPurchaseController;
use App\Http\Controllers\IngredientController;
use App\Http\Controllers\RevenueController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthUserController::class, 'register']);
Route::post('/karyawan/{id}', [AuthUserController::class, 'update']);
Route::delete('/karyawan/{id}', [AuthUserController::class, 'destroy']);
Route::post('/user', [AuthUserController::class, 'userList']);
Route::get('/roles', [AuthUserController::class, 'roles']);

Route::post('/login', [AuthUserController::class, 'login']);
Route::post('/loginMobile', [AuthUserController::class, 'loginMobile']);
Route::post('/logout', [AuthUserController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/users', [AuthUserController::class, 'userList'])->middleware('auth:sanctum');
Route::post('/products/{id}/disable', [ProductController::class, 'disableProduct'])->middleware('auth:sanctum');
Route::post('/products/{id}/enable', [ProductController::class, 'enableProduct'])->middleware('auth:sanctum');
Route::put('/products/{product}', [ProductController::class, 'updateProduct'])->middleware('auth:sanctum');
Route::get('/categories', [ProductController::class, 'viewCategories']);
Route::get('/products/{id}', [ProductController::class, 'viewById']);
Route::post('/products/{id}', [ProductController::class, 'deleteProduct'])->middleware('auth:sanctum');
Route::get('/products', [ProductController::class,'viewProducts'])->middleware('auth:sanctum');
Route::get('/productsMobile', [ProductController::class,'viewProductsMobile']);
Route::post('/addProducts', [ProductController::class,'addProduct'])->middleware('auth:sanctum');

Route::post('/transactions', [TransactionController::class, 'store'])->middleware('auth:sanctum');
Route::get('/getPaymentInfo', [TransactionController::class, 'getPaymentInfo']);
Route::get('/transactions/{transaction}/nota', [TransactionController::class, 'nota'])->middleware('auth:sanctum');
Route::get('/dashboard-overview', [LaporanController::class, 'dashboardOverview'])->middleware('auth:sanctum');
Route::post('/reports/daily', [LaporanController::class, 'dailyReport']);
Route::post('/reports/weekly', [LaporanController::class, 'weeklyReport']);
Route::post('/reports/monthly', [LaporanController::class, 'monthlyReport']);
Route::post('/reports/yearly', [LaporanController::class, 'yearlyReport']);

Route::get('/history', [TransactionController::class, 'history']);
Route::post('/transactions/{transaction}/adjust', [TransactionController::class, 'adjustTransaction']);

Route::apiResource('/ingredients', IngredientController::class);
Route::apiResource('/purchases', IngredientPurchaseController::class)->middleware('auth:sanctum');



Route::controller( RevenueController::class)->group(function() {
    Route::get('/saldo', 'show');
    Route::post('/saldo/add', 'add');
    Route::post('/saldo/record', 'recordExpense');
    Route::get('/saldo/history', 'history');
    Route::post('/saldo/withdraw', 'tarikSaldo');
});