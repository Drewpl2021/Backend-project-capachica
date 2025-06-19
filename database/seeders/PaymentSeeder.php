<?php

namespace Database\Seeders;

use App\Models\Payment;
use App\Models\Reserva;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PaymentSeeder extends Seeder
{
    public function run()
    {
        // Obtener algunas reservas existentes (asegúrate de tener datos en 'reservas')
        $reservas = Reserva::take(10)->get();

        foreach ($reservas as $reserva) {
            Payment::create([
                'id' => Str::uuid(),
                'code' => 'PAY_' . strtoupper(Str::random(5)),
                'total' => $reserva->total,
                'bi' => $reserva->bi,
                'igv' => $reserva->igv,
                'reserva_id' => $reserva->id,
            ]);
        }
    }
}
