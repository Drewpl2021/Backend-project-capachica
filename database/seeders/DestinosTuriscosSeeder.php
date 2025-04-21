<?php

namespace Database\Seeders;

use App\Models\DestinosTuriscos;
use App\Models\Emprendedor;
use Illuminate\Database\Seeder;

class DestinosTuriscosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Obtener el primer emprendedor de la base de datos
        $emprendedor = Emprendedor::first(); // Asegúrate de que haya al menos un emprendedor

        // Crear algunos destinos turísticos de ejemplo
        DestinosTuriscos::create([
            'nombre' => 'Isla de los Uros',
            'descripcion' => 'Un grupo de islas flotantes ubicadas en el lago Titicaca.',
            'lugar' => 'Lago Titicaca, Puno',
            'emprendedor_id' => $emprendedor->id
        ]);

        DestinosTuriscos::create([
            'nombre' => 'Sillustani',
            'descripcion' => 'Un complejo funerario preincaico ubicado cerca de Puno.',
            'lugar' => 'Puno, Perú',
            'emprendedor_id' => $emprendedor->id
        ]);
    }
}
