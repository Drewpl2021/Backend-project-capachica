<?php

namespace Database\Seeders;

use App\Models\Payment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PaymentSeeder extends Seeder
{
    public function run()
    {
        // Crear 10 pagos de ejemplo
        for ($i = 0; $i < 10; $i++) {
            Payment::create([
                'id' => Str::uuid(),  // Generar un UUID para el pago
                'code' => 'CODE-' . strtoupper(Str::random(5)),  // Código de pago aleatorio
                'total' => '1000.00',  // Total de pago
                'bi' => '850.00',  // Base imponible
                'igv' => '150.00',  // IGV
            ]);
        }
    }
}
