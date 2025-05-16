<?php

namespace Database\Seeders;

use App\Models\Emprendedor;
use App\Models\Payment;
use App\Models\Reserva;
use App\Models\Sale;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SaleSeeder extends Seeder
{
    public function run()
    {
        // Obtener algunos emprendimientos, pagos y reservas
        $emprendimientos = Emprendedor::all();
        $payments = Payment::all();
        $reservas = Reserva::all();

        // Verificar si hay datos
        if ($emprendimientos->isEmpty() || $payments->isEmpty() || $reservas->isEmpty()) {
            $this->command->error('No hay suficientes datos en las tablas de emprendimientos, pagos o reservas.');
            return;
        }

        // Crear ventas
        foreach ($emprendimientos as $emprendimiento) {
            $sale = Sale::create([
                'id' => Str::uuid(),
                'emprendedor_id' => $emprendimiento->id,
                'payment_id' => $payments->random()->id,
                'reserva_id' => $reservas->random()->id,
                'code' => 'SALE-' . strtoupper(Str::random(5)),
                'IGV' => 18.00,
                'BI' => 100.00,
                'total' => 118.00,
            ]);
        }
    }
}
