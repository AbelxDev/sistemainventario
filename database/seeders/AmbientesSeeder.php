<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ambiente;
class AmbientesSeeder extends Seeder
{
    public function run()
    {
        Ambiente::factory()->count(10)->create();
    }
}
