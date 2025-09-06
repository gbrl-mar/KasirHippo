<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthUserController;

Route::get('/', function () {
    return view('landingpage');
});
Route::get('/login', [AuthUserController::class, 'showLoginForm'])->name('login');
Route::get('/dashboardOwner', [AuthUserController::class, 'showDashboardOwner'])->name('dashboardOwner');