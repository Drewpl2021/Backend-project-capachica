<?php

namespace Database\Seeders;

use App\Models\Municipalidad;
use App\Models\Slider_Muni;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SliderMuniSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener todos los registros de municipalidades
        $municipalidades = Municipalidad::all(); // Obtener todas las municipalidades

        // Verificar si se han encontrado municipalidades
        if ($municipalidades->isEmpty()) {
            return; // Si no hay municipalidades, termina el seeding
        }

        foreach ($municipalidades as $municipalidad) {
            // Crear un slider para esa municipalidad
            Slider_Muni::create([
                'municipalidad_id' => $municipalidad->id,  // Usar el ID de la municipalidad
                'titulo' => 'Slider ' . $municipalidad->id, // Título único por ID de la municipalidad
                'url_images' => 'https://example.com/imagen1.jpg', // URL de la imagen
                'descripcion' => 'Este es el slider para ' . $municipalidad->nombre, // Descripción personalizada
                'id' => Str::uuid(),  // Generar un UUID para el slider
            ]);
        }
    }
}
