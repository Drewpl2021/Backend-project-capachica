<?php

namespace Database\Seeders;

use App\Models\Emprendedor;
use App\Models\Asociacion; // Asegúrate de tener el modelo de Asociación
use Illuminate\Database\Seeder;

class EmprendedorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Obtener una asociación existente
        $asociacion = Asociacion::first(); // Asegúrate de que haya al menos una asociación en la base de datos

        // Crear algunos emprendedores de ejemplo
        Emprendedor::create([
            'asociacion_id' => $asociacion->id,
            'razon_social' => 'Emprendedor 1',
            'familia' => 'Familia A',
        ]);

        Emprendedor::create([
            'asociacion_id' => $asociacion->id,
            'razon_social' => 'Emprendedor 2',
            'familia' => 'Familia A',
        ]);

        // Agrega más emprendedores si es necesario
    }
}
