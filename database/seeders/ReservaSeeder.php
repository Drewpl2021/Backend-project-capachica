<?php

namespace Database\Seeders;

use App\Models\Reserva;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ReservaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Obtener usuarios existentes
        $users = User::all();

        // Verificar si hay usuarios
        if ($users->isEmpty()) {
            $this->command->error('No hay usuarios en la base de datos.');
            return;
        }

        // Crear 10 reservas de ejemplo
        foreach ($users as $user) {
            Reserva::create([
                'id' => Str::uuid(),
                'user_id' => $user->id,  // Asignamos un usuario aleatorio
                'code' => 'CODE-' . strtoupper(Str::random(5)),  // Código de la reserva
                'igv' => 18.00,  // Ejemplo de IGV
                'bi' => 100.00,  // Ejemplo de base imponible
                'total' => 118.00,  // Ejemplo de total
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
