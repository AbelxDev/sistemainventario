<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


// Página principal → redirige al login
Route::get('/', function () {
    return redirect()->route('login');

});

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

    Route::get('/proveedores', function () {
        return view('proveedores.index');
    });

    Route::get('/tipos-producto', function () {
        return view('tipos-producto.index');
    });

    Route::get('/productos', function () {
        return view('productos.index');
    });

    Route::get('/ambientes', function () {
        return view('ambientes.index');
    })->name('ambientes.index');

    Route::get('/facturas', function () {
        return view('facturas.index');
    })->name('facturas.index');

});
Route::middleware(['auth'])->group(function () {

});

Route::middleware(['auth', 'role:Administrador'])->get('/admin/users', function () {
    return view('admin.users');
})->name('admin.users');
