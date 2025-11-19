<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Ambiente;

class AmbienteFactory extends Factory
{
    protected $model = Ambiente::class;

    public function definition()
    {
        return [
            'nombre' => $this->faker->word(),
            'ubicacion' => $this->faker->address(),
        ];
    }
}
