<?php

namespace Database\Seeders;

use App\Models\Emprendedor;
use App\Models\Service;
use App\Models\EmprendedorService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class EmprendimientoServiceSeeder extends Seeder
{
    public function run()
    {
        // 1. Verificar existencia de la tabla
        if (!Schema::hasTable('emprendedor_service')) {
            $this->command->error('❌ La tabla emprendedor_service no existe. Ejecuta las migraciones primero.');
            return;
        }

        // 2. Verificar emprendedores
        $totalEmprendedores = Emprendedor::count();
        if ($totalEmprendedores === 0) {
            $this->command->error('❌ No hay emprendedores. Ejecuta EmprendedorSeeder primero.');
            return;
        }

        // 3. Verificar servicios existentes con IDs válidos
        $services = Service::where('id', '<>', '0')->get();
        if ($services->isEmpty()) {
            $this->command->error('❌ No hay servicios válidos. Ejecuta ServiceSeeder primero.');
            return;
        }

        $this->command->info("✅ Comenzando asignación de servicios a $totalEmprendedores emprendedores...");

        $progreso = $this->command->getOutput()->createProgressBar($totalEmprendedores);
        $errores = 0;

        DB::transaction(function () use ($services, $progreso, &$errores) {
            Emprendedor::chunk(100, function ($emprendedores) use ($services, $progreso, &$errores) {
                foreach ($emprendedores as $emprendedor) {
                    try {
                        // Obtener solo IDs de servicios existentes
                        $serviceIdsValidos = $services->pluck('id')->toArray();

                        // Seleccionar 1-3 servicios aleatorios (asegurando que existan)
                        $serviciosAsignar = Service::whereIn('id', $serviceIdsValidos)
                            ->inRandomOrder()
                            ->limit(rand(1, 3))
                            ->get();

                        foreach ($serviciosAsignar as $service) {
                            // Usar firstOrCreate para evitar duplicados
                            EmprendedorService::firstOrCreate([
                                'service_id' => $service->id,
                                'emprendedor_id' => $emprendedor->id,
                            ], [
                                'id' => Str::uuid(),
                                'code' => 'SRV-' . Str::upper(Str::random(5)),
                                'cantidad' => rand(1, 10),
                                'name' => $service->name,
                                'description' => $this->generarDescripcion($service, $emprendedor),
                            ]);
                        }
                    } catch (\Exception $e) {
                        $errores++;
                        $this->command->error("\n⚠ Error con emprendedor {$emprendedor->id}: " . $e->getMessage());
                    }
                    $progreso->advance();
                }
            });
        });

        $progreso->finish();

        if ($errores > 0) {
            $this->command->error("\n⚠ Se completó con $errores errores.");
        } else {
            $this->command->info("\n✔ Se asignaron servicios exitosamente a $totalEmprendedores emprendedores.");
        }
    }

    protected function generarDescripcion($service, $emprendedor)
    {
        $descripciones = [
            "{$service->name} exclusivo por {$emprendedor->razon_social}",
            "Disfruta de {$service->name} con {$emprendedor->name_family}",
            "Experiencia en {$service->name} en {$emprendedor->lugar}",
            "Servicio premium de {$service->name} por {$emprendedor->razon_social}"
        ];

        return $descripciones[array_rand($descripciones)];
    }
}
