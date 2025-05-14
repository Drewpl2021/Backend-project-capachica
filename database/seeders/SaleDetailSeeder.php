<?php

namespace Database\Seeders;

use App\Models\EmprendedorService;
use App\Models\Sale;
use App\Models\SaleDetail;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SaleDetailSeeder extends Seeder
{
    public function run()
    {
        // Obtener todas las ventas y servicios
        $sales = Sale::all();
        $emprendimientoServices = EmprendedorService::all();

        // Verificar si hay ventas y servicios
        if ($sales->isEmpty() || $emprendimientoServices->isEmpty()) {
            $this->command->error('No hay ventas o servicios en la base de datos.');
            return;
        }

        // Crear detalles de venta para cada venta
        foreach ($sales as $sale) {
            // Seleccionar un servicio aleatorio para la venta
            $service = $emprendimientoServices->random();

            SaleDetail::create([
                'id' => Str::uuid(),  // Generar un UUID para el detalle de la venta
                'emprendedor_service_id' => $service->id,  // Asignar el servicio aleatorio
                'sale_id' => $sale->id,  // Asignar la venta
                'description' => 'Detalle de la venta ' . $sale->code,  // Descripción de la venta
                'costo' => 100.00,  // Costo del detalle
                'IGV' => 18.00,  // Impuesto general a las ventas
                'BI' => 100.00,  // Base imponible
                'total' => 118.00,  // Total del detalle
                'lugar' => 'Lugar de la venta',  // Lugar de la venta
            ]);
        }
    }
}
