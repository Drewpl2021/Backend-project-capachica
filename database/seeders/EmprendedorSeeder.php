<?php

namespace Database\Seeders;

use App\Models\Emprendedor;
use App\Models\Asociacion;
use App\Models\User;
use Illuminate\Database\Seeder;
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

        // Crear un emprendedor para cada usuario
        foreach ($users as $user) {
            // Seleccionar una asociación aleatoria para este emprendedor
            $asociacion = $asociaciones->random();

            // Datos del emprendedor
            $emprendedorData = [
                'id' => (string) Str::uuid(),
                'user_id' => $user->id,
                'asociacion_id' => $asociacion->id,
                'razon_social' => 'Emprendimiento de ' . $user->name,
                'name_family' => 'Familia ' . Str::upper(Str::random(1)),
                'address' => 'Calle ' . rand(1, 100) . ', ' . $asociacion->lugar,
                'code' => 'EMP-' . Str::upper(Str::random(5)),
                'ruc' => rand(10000000000, 99999999999),
                'description' => "Emprendimiento de " . $user->name . " dedicado a " .
                    ['artesanía', 'agricultura', 'textiles', 'alimentos'][rand(0, 3)] .
                    " en " . $asociacion->nombre,
                'lugar' => $asociacion->lugar,
                'img_logo' => "emprendimientos/logos/" . Str::random(10) . ".png",
            ];

            // Crear el emprendedor
            $emprendedor = Emprendedor::create($emprendedorData);

            $this->command->info("Emprendedor creado para usuario {$user->name} (ID: {$user->id}):");
            $this->command->info("- Razon Social: {$emprendedor->razon_social}");
            $this->command->info("- Asociación: {$asociacion->nombre}");
        }

        $this->command->info('Se crearon ' . count($users) . ' emprendedores exitosamente.');
    }
}
