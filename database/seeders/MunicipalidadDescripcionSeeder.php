<?php

namespace Database\Seeders;

use App\Models\Municipalidad_Descripcion;
use App\Models\Municipalidad;
use Illuminate\Database\Seeder;

class MunicipalidadDescripcionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $municipalidad = Municipalidad::first();

        if (!$municipalidad) {
            return; // Si no hay ninguna municipalidad, termina el seeding
        }

        // Crear algunas descripciones de municipios de ejemplo
        Municipalidad_Descripcion::create([
            'municipalidad_id' => $municipalidad->id,  // Usar el UUID de la municipalidad
            'logo' => 'https://example.com/logo.png',
            'direccion' => 'Av. Central 123',
            'descripcion' => 'Municipalidad ubicada en el centro de Capachica.',
            'ruc' => '12345678901',
            'correo' => 'contacto@capachica.gob.pe',
            'nombre_alcalde' => 'Juan Pérez',
            'anio_gestion' => '2023'
        ]);
    }
}
