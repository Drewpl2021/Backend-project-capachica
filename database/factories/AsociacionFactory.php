<?php

namespace Database\Factories;

use App\Models\Asociacion;
use App\Models\Municipalidad;
use Illuminate\Database\Eloquent\Factories\Factory;

class asociacionFactory extends Factory
{
    protected $model = Asociacion::class;

    public function definition()
    {
        return [
            'municipalidad_id' => Municipalidad::factory(),
            'nombre' => $this->faker->company(),
            'descripcion' => $this->faker->paragraph(),
            'lugar' => $this->faker->city(),
            'url' => $this->faker->url(),
            'estado' => $this->faker->boolean(80), // 80% chances true
        ];
    }
}
