<?php

namespace Database\Seeders;

use App\Models\Emprendedor;
use App\Models\Asociacion; // Asegúrate de tener el modelo de Asociación
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EmprendedorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Buscar al usuario por su 'username'
        $user = User::where('username', 'andres.montes')->first();

        // Verificar si se encontró el usuario
        if (!$user) {
            // Si no se encontró, mostrar un mensaje de error y detener la ejecución
            echo "El usuario 'andres.montes' no se encuentra en la base de datos. No se puede continuar con la creación de emprendedores.\n";
            return; // Detener la ejecución si el usuario no existe
        }

        // Si el usuario existe, obtener las asociaciones
        $asociaciones = Asociacion::all();

        // Verificar si hay asociaciones
        if ($asociaciones->isEmpty()) {
            echo "No hay asociaciones en la base de datos. No se puede continuar con la creación de emprendedores.\n";
            return;
        }

        // Crear un emprendedor para cada asociación
        foreach ($asociaciones as $asociacion) {
            // Crear el emprendedor
            Emprendedor::create([
                'id' => (string) Str::uuid(),
                'user_id' => $user->id || 'ec03a1d3-12b8-4c64-9513-60bfd7e60f0a',  // Asegurándonos de que se usa el ID correcto del usuario
                'asociacion_id' => $asociacion->id,
                'razon_social' => 'Emprendedor de ' . $asociacion->nombre,
                'name_family' => 'Familia A',
                'address' => 'Dirección genérica 1',
                'code' => 'EMP-' . Str::random(5),
                'ruc' => Str::random(11),
                'description' => null,
                'lugar' => null,
                'img_logo' => null,
            ]);
            break; // Solo crear un emprendedor para este usuario
        }
    }
}
