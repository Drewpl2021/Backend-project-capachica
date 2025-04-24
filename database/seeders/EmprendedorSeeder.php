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
        // Obtener todas las asociaciones existentes
        $asociaciones = Asociacion::all(); // Se obtiene todas las asociaciones en la base de datos

        // Iterar sobre cada asociación
        foreach ($asociaciones as $asociacion) {
            // Crear dos emprendedores para cada asociación
            Emprendedor::create([
                'asociacion_id' => $asociacion->id,
                'razon_social' => 'Emprendedor 1 - ' . $asociacion->nombre,
                'familia' => 'Familia A',
            ]);

            Emprendedor::create([
                'asociacion_id' => $asociacion->id,
                'razon_social' => 'Emprendedor 2 - ' . $asociacion->nombre,
                'familia' => 'Familia B',
            ]);
        }
    }
}
