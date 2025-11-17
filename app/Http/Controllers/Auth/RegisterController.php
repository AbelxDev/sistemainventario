<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => [
                'required',
                'string',
                'confirmed', // Verifica que coincida con password_confirmation
                Password::min(8) // mínimo 8 caracteres
                    ->mixedCase() // al menos una mayúscula y una minúscula
                    ->numbers()   // al menos un número
                    ->symbols()   // al menos un símbolo
            ],
        ]);

        // Crear el usuario
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'email_verified_at' => now(),
            'password' => Hash::make($request->password),
        ]);

        // Autenticar al usuario recién creado
        Auth::login($user);

        return redirect('/dashboard');
    }
}
