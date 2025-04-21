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
        // Obtener el primer registro de descripción de la municipalidad
        $municipalidadDescripcion = Municipalidad_Descripcion::first(); // Asegúrate de que esto sea un UUID válido

        // Verificar si se ha encontrado una descripción válida
        if (!$municipalidadDescripcion) {
            return; // Si no hay ninguna descripción de municipio, termina el seeding
        }

        // Crear el slider para esa municipalidad
        Slider_Muni::create([
            'municipio_descrip_id' => $municipalidadDescripcion->id,  // Usar el UUID de la municipalidad
            'titulo' => 'Slider 1',
            'descripcion' => 'Este es el primer slider.',
            'id' => Str::uuid(),  // Generar un UUID para el slider
        ]);
    }
}
