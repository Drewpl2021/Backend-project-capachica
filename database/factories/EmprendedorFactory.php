<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Emprendedor;
use App\Models\Asociacion;

class EmprendedorFactory extends Factory
{
    protected $model = Emprendedor::class;

    public function definition()
    {
        return [
            'id' => (string) Str::uuid(), // Si usas UUID como llave primaria
            'asociacion_id' => Asociacion::factory(), // Crear una asociación automáticamente
            'razon_social' => $this->faker->company(),
            'name_family' => $this->faker->lastName(),
            'address' => $this->faker->address(),
            'code' => strtoupper(Str::random(8)),
            'description' => $this->faker->sentence(10),
            'ruc' => $this->faker->unique()->numerify('20#########'),
            'lugar' => $this->faker->city(),
            'img_logo' => $this->faker->imageUrl(200, 200, 'business'),
            'status' => $this->faker->boolean(80), // 80% chance true
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
