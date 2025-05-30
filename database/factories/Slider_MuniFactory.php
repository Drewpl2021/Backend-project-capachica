<?php

namespace Database\Factories;

use App\Models\Municipalidad;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Slider_Muni>
 */
class Slider_MuniFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
   
    public function definition()
    {
        return [
            'id' => $this->faker->uuid(),
            'municipalidad_id' => Municipalidad::factory(),
            'titulo' => $this->faker->sentence(4),
            'descripcion' => $this->faker->text(200), // <-- ¡coma añadida aquí!
            'url_images' => $this->faker->imageUrl(640, 480, 'slider', true),
        ];
    }
}
