<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ParentModule;
use App\Models\Module;
use App\Models\Municipalidad;
use Illuminate\Support\Str;

class MunicipalidadSeeder  extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear algunas municipalidades de ejemplo
        Municipalidad::create([
            'id' => Str::uuid(),
            'distrito' => 'Capachica',
            'provincia' => 'Puno',
            'region' => 'Puno',
            'codigo' => '01'
        ]);

        Municipalidad::create([
            'id' => Str::uuid(),
            'distrito' => 'Juliaca',
            'provincia' => 'San Román',
            'region' => 'Puno',
            'codigo' => '02'
        ]);
        // Agrega más módulos aquí...
    }
}
