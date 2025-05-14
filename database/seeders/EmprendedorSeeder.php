<?php

namespace Database\Seeders;

use App\Models\Emprendedor;
use App\Models\Asociacion;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EmprendedorSeeder extends Seeder
{
    public function run()
    {
        // Obtener todos los usuarios
        $users = User::all();

        // Verificar si hay usuarios
        if ($users->isEmpty()) {
            $this->command->error('No hay usuarios en la base de datos. No se puede continuar con la creación de emprendedores.');
            return;
        }

        // Obtener todas las asociaciones
        $asociaciones = Asociacion::all();

        // Verificar si hay asociaciones
        if ($asociaciones->isEmpty()) {
            $this->command->error('No hay asociaciones en la base de datos. No se puede continuar con la creación de emprendedores.');
            return;
        }

        // Crear un emprendedor para cada conjunto de usuarios
        foreach ($asociaciones as $asociacion) {
            // Seleccionar al menos dos usuarios aleatorios para este emprendedor
            $selectedUsers = $users->random(2);  // Tomar dos usuarios aleatorios

            // Datos del emprendedor
            $emprendedorData = [
                'id' => (string) Str::uuid(),
                'asociacion_id' => $asociacion->id,
                'razon_social' => 'Emprendimiento en ' . $asociacion->nombre,
                'name_family' => 'Familia ' . Str::upper(Str::random(1)),
                'address' => 'Calle ' . rand(1, 100) . ', ' . $asociacion->lugar,
                'code' => 'EMP-' . Str::upper(Str::random(5)),
                'ruc' => rand(10000000000, 99999999999),
                'description' => "Emprendimiento dedicado a " . ['artesanía', 'agricultura', 'textiles', 'alimentos'][rand(0, 3)] . " en " . $asociacion->nombre,
                'lugar' => $asociacion->lugar,
                'img_logo' => "emprendimientos/logos/" . Str::random(10) . ".png",
            ];

            // Crear el emprendedor
            $emprendedor = Emprendedor::create($emprendedorData);

            // Asociar los usuarios seleccionados a este emprendedor (con UUID en la tabla pivote)
            foreach ($selectedUsers as $user) {
                // Insertar en la tabla intermedia 'emprendedor_user' con UUID
                DB::table('emprendedor_user')->insert([
                    'id' => (string) Str::uuid(),  // Generar un UUID para la tabla intermedia
                    'user_id' => $user->id,  // ID del usuario
                    'emprendedor_id' => $emprendedor->id,  // ID del emprendedor
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

        }

    }
}
