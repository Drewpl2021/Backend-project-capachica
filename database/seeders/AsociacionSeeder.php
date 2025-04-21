<?php

namespace Database\Seeders;

use App\Models\Asociacion;
use App\Models\Municipalidad;
use Illuminate\Database\Seeder;

class AsociacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Obtener una municipalidad existente
        $municipalidad = Municipalidad::first(); // Asegúrate de que haya al menos una municipalidad en la base de datos

        // Crear algunas asociaciones de ejemplo
        Asociacion::create([
            'municipalidad_id' => $municipalidad->id,
            'nombre' => 'Asociación de Turismo Capachica',
            'descripcion' => 'Asociación dedicada al desarrollo del turismo en la zona de Capachica.',
            'lugar' => 'Capachica, Puno',
            'estado' => true
        ]);

        Asociacion::create([
            'municipalidad_id' => $municipalidad->id,
            'nombre' => 'Asociación de Agricultores Juliaca',
            'descripcion' => 'Asociación de agricultores trabajando en la región de Juliaca.',
            'lugar' => 'Juliaca, Puno',
            'estado' => true
        ]);
    }
}
