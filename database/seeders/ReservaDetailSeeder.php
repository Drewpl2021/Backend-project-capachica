<?php

namespace Database\Seeders;

use App\Models\EmprendedorService;
use App\Models\Reserva;
use App\Models\ReserveDetail;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ReservaDetailSeeder extends Seeder
{
    public function run()
    {
        // Obtener todas las reservas
        $reservas = Reserva::all();

        // Verificar si hay reservas
        if ($reservas->isEmpty()) {
            $this->command->error('No hay reservas en la base de datos.');
            return;
        }

        // Obtener todos los servicios de emprendedores
        $emprendimientoServices = EmprendedorService::all();

        // Verificar si hay servicios
        if ($emprendimientoServices->isEmpty()) {
            $this->command->error('No hay servicios en la base de datos.');
            return;
        }

        // Crear 10 detalles de reservas para cada reserva
        foreach ($reservas as $reserva) {
            // Seleccionar un servicio aleatorio para la reserva
            $service = $emprendimientoServices->random();

            // Insertar el detalle de la reserva
            ReserveDetail::create([
                'id' => Str::uuid(),  // Generar un UUID para el detalle de la reserva
                'emprendedor_service_id' => $service->id,  // Asignar el servicio aleatorio
                'reserva_id' => $reserva->id,  // Asignar la reserva
                'description' => 'Detalle de la reserva ' . $reserva->code,
                'cantidad' => 1, // <-- Agrega esto con el valor que corresponda
                'costo' => 100.00,  // Costo de la reserva
                'IGV' => 18.00,  // Impuesto general a las ventas (IGV)
                'BI' => 100.00,  // Base imponible
                'total' => 118.00,  // Total de la reserva
                'lugar' => 'Lugar de ejemplo',  // Lugar donde se realiza la reserva
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
