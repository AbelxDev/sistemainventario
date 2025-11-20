<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // ============================
        // USUARIO ADMIN PRINCIPAL
        // ============================
        $user = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('123456789'),
                'email_verified_at' => now(),
            ]
        );

        $user->assignRole('Administrador');

        // ============================
        // CREAR 10 USUARIOS ALEATORIOS
        // ============================

        $roles = ['Administrador', 'Encargado de Almacén', 'Usuario Solicitante'];

        for ($i = 1; $i <= 10; $i++) {

            $randomPassword = ($i % 9) + 1;
            // 1 → 9, vuelve a 1

            $newUser = User::create([
                'name' => "Usuario {$i}",
                'email' => "usuario{$i}@example.com",
                'password' => Hash::make($randomPassword),
                'email_verified_at' => now(),
            ]);

            // Asignar rol aleatorio
            $newUser->assignRole($roles[array_rand($roles)]);
        }
    }
}
