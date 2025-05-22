<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\EmprendedorService;
use App\Models\Emprendedor;
use App\Models\Service;

class EmprendedorServiceFactory extends Factory
{
    protected $model = EmprendedorService::class;

    public function definition()
    {
        return [
            'id' => (string) Str::uuid(),
            'emprendedor_id' => Emprendedor::factory(),
            'service_id' => Service::factory(),
            'code' => 'CODE-' . strtoupper(Str::random(8)),
            'status' => $this->faker->boolean(90),
            'cantidad' => $this->faker->numberBetween(1, 20),
            'costo' => $this->faker->randomFloat(2, 10, 1000),
            'costo_unidad' => $this->faker->randomFloat(2, 1, 100),
            'name' => $this->faker->word,
            'description' => $this->faker->sentence(6),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
