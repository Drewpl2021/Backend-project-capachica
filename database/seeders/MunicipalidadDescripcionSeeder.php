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

        // Crear una descripción para el municipio de Capachica
        Municipalidad_Descripcion::create([
            'municipalidad_id' => $municipalidad->id,  // Usar el UUID de la municipalidad correspondiente
            'logo' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRldqMfXjllg2bGK4LGGsYPs-DmydyR_s1LCw&s',  // Logo de la Municipalidad de Capachica
            'direccion' => 'Plaza de Armas, Capachica',  // Dirección de la Municipalidad de Capachica
            'descripcion' => 'La Municipalidad Distrital de Capachica está ubicada en la provincia de Puno, al sureste del Perú. Se encarga de la gestión local, promoción del turismo y desarrollo de proyectos sociales y económicos para la población.',  // Descripción detallada
            'ruc' => '20192140448',  // RUC de la Municipalidad de Capachica
            'correo' => 'municipalidad@capachica.gob.pe',  // Correo oficial de la Municipalidad de Capachica
            'nombre_alcalde' => 'Ascencion Laquise Humpire',  // Nombre del alcalde de Capachica (ejemplo)
            'anio_gestion' => '2023-2026'  // Año de gestión actual
        ]);
    }
}
