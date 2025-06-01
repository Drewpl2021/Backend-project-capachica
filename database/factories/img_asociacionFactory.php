<?php

namespace Database\Factories;

use App\Models\ImgAsociacion;
use App\Models\Asociacion;
use App\Models\img_asociacion;
use Illuminate\Database\Eloquent\Factories\Factory;

class img_asociacionFactory extends Factory
{
    protected $model = img_asociacion::class;

    public function definition()
    {
        return [
            'asociacion_id' => Asociacion::factory(),
            'url_image' => $this->faker->imageUrl(640, 480, 'business'),
            'estado' => $this->faker->boolean(90),
            'codigo' => $this->faker->unique()->bothify('IMG###'),
        ];
    }
}
