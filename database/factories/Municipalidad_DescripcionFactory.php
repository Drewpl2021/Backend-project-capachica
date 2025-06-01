<?php

namespace Database\Factories;

use App\Models\Municipalidad_Descripcion;
use Illuminate\Database\Eloquent\Factories\Factory;

class Municipalidad_DescripcionFactory extends Factory
{
    protected $model = Municipalidad_Descripcion::class;

    public function definition()
    {
        return [
            'municipalidad_id' => \App\Models\Municipalidad::factory(), // crea o asocia una municipalidad
            'logo' => $this->faker->imageUrl(200, 200, 'business'),
            'direccion' => $this->faker->address(),
            'descripcion' => $this->faker->paragraph(),
            'ruc' => $this->faker->numerify('###########'),
            'correo' => $this->faker->unique()->safeEmail(),
            'nombre_alcalde' => $this->faker->name(),
            'anio_gestion' => $this->faker->year(),
        ];
    }
}
