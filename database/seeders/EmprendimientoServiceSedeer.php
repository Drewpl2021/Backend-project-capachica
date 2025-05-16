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

        if ($emprendedores->isEmpty()) {
            $this->command->error('No hay emprendedores en la base de datos. No se puede continuar con la creación de relaciones.');
            return;
        }

        // Obtener todos los servicios
        $services = Service::all();

        if ($services->isEmpty()) {
            $this->command->error('No hay servicios en la base de datos. No se puede continuar con la creación de relaciones.');
            return;
        }

        foreach ($emprendedores as $emprendedor) {
            // Seleccionar de 1 a 3 servicios aleatorios para cada emprendedor
            $selectedServices = $services->random(rand(1, 3));

            foreach ($selectedServices as $service) {
                // Validar si ya existe la relación para evitar duplicados
                $exists = DB::table('emprendedor_service')
                    ->where('service_id', $service->id)
                    ->where('emprendedor_id', $emprendedor->id)
                    ->exists();

                if ($exists) {
                    $this->command->warn("La relación entre emprendedor {$emprendedor->razon_social} y servicio {$service->name} ya existe. Se omite.");
                    continue;
                }

                DB::table('emprendedor_service')->insert([
                    'id' => (string) Str::uuid(),
                    'service_id' => $service->id,
                    'emprendedor_id' => $emprendedor->id,
                    'code' => 'CODE-' . Str::upper(Str::random(5)),
                    'status' => true,
                    'costo' => 50.00, // <- Debes pasar un valor aquí
                    // Asumiendo que 'name' y 'description' personalizados basados en el servicio
                    'name' => 'Servicio para ' . $emprendedor->razon_social,
                    'description' => 'Descripción personalizada del servicio ' . $service->name . ' para el emprendedor ' . $emprendedor->razon_social,
                    'costo' => $service->base_price ? $service->base_price * rand(80, 120) / 100 : rand(10, 100),
                    'cantidad' => $service->base_price ? $service->base_price * rand(80, 120) / 100 : rand(10, 100),
                    'costo_unidad' => $service->base_price ? $service->base_price : rand(5, 50),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
