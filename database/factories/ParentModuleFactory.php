<?php

namespace Database\Factories;

use App\Models\ParentModule;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ParentModule>
 */
class ParentModuleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = ParentModule::class;

    public function definition()
    {
        return [
            'id' => (string) Str::uuid(),
            'title' => $this->faker->unique()->sentence(2),
            'code' => $this->faker->unique()->bothify('PMOD-####'),
            'subtitle' => $this->faker->sentence(3),
            'type' => $this->faker->word(),
            'icon' => $this->faker->optional()->word(),
            'status' => $this->faker->boolean(),
            'moduleOrder' => $this->faker->numberBetween(1, 100),
            'link' => $this->faker->url(),
        ];
    }
}
