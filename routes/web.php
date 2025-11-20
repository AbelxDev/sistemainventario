<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Livewire\Ambientes;


// Página principal → redirige al login
Route::get('/', function () {
    return redirect()->route('login');

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

    Route::get('/proveedores', function () {
    return view('proveedores.index');
    });
    Route::get('/tipos-producto', \App\Livewire\TiposProducto\Index::class)->name('tipos-producto.index');
    Route::get('/productos', \App\Livewire\Productos\Index::class)->name('productos.index');
    // CRUD Ambientes
    Route::get('/ambientes', Ambientes::class)->name('ambientes.index');

});
Route::middleware(['auth'])->group(function () {
    Route::get('/ambientes', function () {
        return view('ambientes.index');
    })->name('ambientes.index');
});

Route::middleware(['auth', 'role:Administrador'])->get('/admin/users', function () {
    return view('admin.users');
})->name('admin.users');
