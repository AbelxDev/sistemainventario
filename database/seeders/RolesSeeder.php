<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    public function run()
    {
        // Crear roles si no existen
        Role::firstOrCreate(['name' => 'Administrador']);
        Role::firstOrCreate(['name' => 'Encargado de Almacén']);
        Role::firstOrCreate(['name' => 'Usuario Solicitante']);
    }
}
