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
        $slider = Slider_Muni::first(); // Asegúrate de que esto sea un UUID válido

        // Verificar si se ha encontrado un slider válido
        if (!$slider) {
            return; // Si no hay ningún slider, termina el seeding
        }

        // Crear una nueva imagen de slider
        Imagen_Slider::create([
            'slider_id' => $slider->id,  // Usar el UUID del slider
            'url_image' => 'https://example.com/imagen1.jpg',
            'estado' => true,
            'codigo' => 101,  // Código para la imagen
            'id' => Str::uuid(),  // Generar un UUID para la imagen
        ]);
    }
}
