<?php

namespace Database\Factories;

use App\Models\Municipalidad;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Municipalidad>
 */


class MunicipalidadFactory extends Factory
{
    protected $model = Municipalidad::class;

    public function definition(): array
    {
        return [
            'distrito'  => $this->faker->city(),
            'provincia' => $this->faker->state(),
            'region' => $this->faker->state(),
            'codigo' => $this->faker->unique()->postcode(),
        ];
    }
}
