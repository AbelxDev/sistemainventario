<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@example.com'], // para evitar duplicados
            [
                'name' => 'Administrador',
                'password' => Hash::make('123456789'),
                'email_verified_at' => now(),
            ]
        );
    }
}
