<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Livewire\Ambientes;


// Página principal → redirige al login
Route::get('/', function () {
    return redirect()->route('login');
Route::get('/ambientes', Ambientes::class)->name('ambientes.index');

});
Route::get('ambientes', \App\Livewire\Ambientes::class)->name('ambientes.index');




// Login (AdminLTE)
Route::middleware('guest')->group(function () {
    Route::get('/login', [\App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [\App\Http\Controllers\Auth\LoginController::class, 'login']);
});

// Logout
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->middleware('auth')->name('logout');

// ============================
// RUTAS PROTEGIDAS (AUTH)
// ============================
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard'); // Vista basada en AdminLTE
    })->name('dashboard');

    // CRUD Ambientes
    Route::get('/ambientes', Ambientes::class)->name('ambientes.index');

});
Route::middleware(['auth'])->group(function () {
    Route::get('/ambientes', function () {
        return view('ambientes.index');
    })->name('ambientes.index');
});
