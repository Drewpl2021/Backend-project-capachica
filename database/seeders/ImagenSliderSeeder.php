<?php

namespace Database\Seeders;

use App\Models\Imagen_Slider;
use App\Models\Slider_Muni;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ImagenSliderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener el primer slider existente
        $sliders = Slider_Muni::all(); // Obtener todos los sliders

        // Verificar si se han encontrado sliders
        if ($sliders->isEmpty()) {
            return; // Si no hay sliders, termina el seeding
        }

        foreach ($sliders as $slider) {
            // Crear una nueva imagen de slider
            Imagen_Slider::create([
                'id' => Str::uuid(),  // Generar un UUID para la imagen
                'slider_id' => $slider->id,  // Usar el UUID del slider
                'url_image' => 'https://example.com/imagen1.jpg',
                'estado' => true,
                'codigo' => 101,  // Código para la imagen
            ]);
        }
    }
}
