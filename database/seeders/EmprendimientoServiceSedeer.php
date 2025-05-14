<?php

namespace Database\Seeders;

use App\Models\asociacion;
use App\Models\Emprendedor;
use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EmprendimientoServiceSedeer extends Seeder
{
    public function run()
    {
        // Obtener todos los emprendedores
        $emprendedores = Emprendedor::all();

        // Verificar si hay emprendedores
        if ($emprendedores->isEmpty()) {
            $this->command->error('No hay emprendedores en la base de datos. No se puede continuar con la creación de relaciones.');
            return;
        }

        // Obtener todos los servicios
        $services = Service::all();

        // Verificar si hay servicios
        if ($services->isEmpty()) {
            $this->command->error('No hay servicios en la base de datos. No se puede continuar con la creación de relaciones.');
            return;
        }

        // Crear relaciones entre emprendedores y servicios
        foreach ($emprendedores as $emprendedor) {
            // Seleccionar al menos un servicio aleatorio para cada emprendedor
            $selectedServices = $services->random(1);  // Tomar un servicio aleatorio

            foreach ($selectedServices as $service) {
                // Asegurarse de que el 'service_id' es el UUID del servicio
                if (!$service) {
                    $this->command->error('No se encontró un servicio válido para el emprendedor ' . $emprendedor->name);
                    continue; // Saltar a la siguiente iteración si no hay servicio válido
                }

                // Datos para la tabla intermedia 'Emprendimiento_servicio'
                DB::table('emprendedor_service')->insert([
                    'id' => (string) Str::uuid(),  // Generar un UUID para la tabla intermedia
                    'service_id' => $service->id,  // Asegurarse de que estamos usando el UUID del servicio
                    'emprendedor_id' => $emprendedor->id,  // ID del emprendedor
                    'code' => 'CODE-' . Str::upper(Str::random(5)),  // Código aleatorio para la relación
                    'cantidad' => rand(1, 10),  // Cantidad aleatoria
                    'name' => 'Servicio de ' . $service->name,  // Nombre relacionado al servicio
                    'description' => 'Descripción del servicio para ' . $emprendedor->name,  // Descripción relacionada
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
