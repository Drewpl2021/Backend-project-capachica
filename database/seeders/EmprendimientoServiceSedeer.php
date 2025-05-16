<?php

namespace Database\Seeders;

use App\Models\Emprendedor;
use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class EmprendimientoServiceSedeer extends Seeder
{
    public function run()
    {
        $emprendedores = Emprendedor::all();
        $services = Service::all();

        if ($emprendedores->isEmpty() || $services->isEmpty()) {
            $this->command->error('No hay emprendedores o servicios para relacionar.');
            return;
        }

        $totalEmprendedores = $emprendedores->count();
        $totalServicios = $services->count();

        foreach ($emprendedores as $indexE => $emprendedor) {
            // Seleccionar 1 a 3 servicios, evitando que el nombre sea repetitivo
            $selectedServices = $services->shuffle()->take(rand(1, 3));

            foreach ($selectedServices as $indexS => $service) {
                // Validar duplicados
                $exists = DB::table('emprendedor_service')
                    ->where('service_id', $service->id)
                    ->where('emprendedor_id', $emprendedor->id)
                    ->exists();

                if ($exists) {
                    $this->command->warn("Relación ya existe: {$emprendedor->razon_social} - {$service->name}");
                    continue;
                }

                // Generar valores personalizados únicos
                $randomSuffix = strtoupper(Str::random(3));
                $costoBase = rand(50, 200);

                $name = "{$service->name} Exclusivo {$randomSuffix} de {$emprendedor->razon_social}";
                $description = "Servicio {$service->name} personalizado para {$emprendedor->razon_social} - Código {$randomSuffix}";

                DB::table('emprendedor_service')->insert([
                    'id' => (string) Str::uuid(),
                    'service_id' => $service->id,
                    'emprendedor_id' => $emprendedor->id,
                    'code' => 'CODE-' . $randomSuffix,
                    'status' => true,
                    'costo' => $costoBase,
                    'cantidad' => rand(1, 30),
                    'costo_unidad' => round($costoBase / rand(1, 5), 2),
                    'name' => $name,
                    'description' => $description,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
