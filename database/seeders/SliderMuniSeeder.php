<?php

namespace Database\Seeders;

use App\Models\Slider_Muni;
use App\Models\Municipalidad_Descripcion;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SliderMuniSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener todos los registros de descripción de la municipalidad
        $municipalidadDescripciones = Municipalidad_Descripcion::all(); // Obtener todas las descripciones

        // Verificar si se han encontrado descripciones
        if ($municipalidadDescripciones->isEmpty()) {
            return; // Si no hay descripciones, termina el seeding
        }

        foreach ($municipalidadDescripciones as $descripcion) {
            // Crear el slider para esa municipalidad
            Slider_Muni::create([
                'municipio_descrip_id' => $descripcion->id,  // Usar el UUID de la municipalidad
                'titulo' => 'Slider ' . $descripcion->id, // Usar el id de la descripción para un título único
                'descripcion' => 'Este es el slider para ' . $descripcion->municipalidad->nombre, // Descripción personalizada
                'id' => Str::uuid(),  // Generar un UUID para el slider
            ]);
        }
    }
}
