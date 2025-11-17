<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Página principal → redirige al login
Route::get('/', function () {
    return redirect()->route('login');
});

// Login / Register (AdminLTE)
Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login', [\App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [\App\Http\Controllers\Auth\LoginController::class, 'login']);

    // Register
    Route::get('/register', [\App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [\App\Http\Controllers\Auth\RegisterController::class, 'register']);
});

// Logout
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->middleware('auth')->name('logout');

// Dashboard protegido
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard'); // Vista basada en AdminLTE
    })->name('dashboard');
});
