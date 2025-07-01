<?php

namespace Database\Factories;

use App\Models\Module;
use App\Models\ParentModule;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Module>
 */
class ModuleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Module::class;

    public function definition()
    {
        return [
            'id' => (string) Str::uuid(),
            'title' => $this->faker->sentence(2),
            'subtitle' => $this->faker->sentence(3),
            'type' => $this->faker->word(),
            'code' => $this->faker->unique()->bothify('MOD-####'),
            'icon' => $this->faker->word(),
            'status' => $this->faker->boolean(),
            'moduleOrder' => $this->faker->numberBetween(1, 20),
            'link' => $this->faker->url(),
            'parent_module_id' => ParentModule::factory(), // ¡Esta línea es la clave!
        ];
    }
}
