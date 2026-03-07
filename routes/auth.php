<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::middleware('guest')->group(function () {
    Route::get('/dang-nhap', function () {
        return view('auth.login');
    })->name('login');

    Route::post('/dang-nhap', [AuthController::class, 'login']);

    Route::get('/dang-ky', function () {
        return view('auth.register');
    })->name('register');

    Route::post('/dang-ky', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/dang-xuat', [AuthController::class, 'logout'])->name('logout');
});
