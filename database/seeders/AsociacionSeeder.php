<?php

namespace Database\Seeders;

use App\Models\Asociacion;
use App\Models\Municipalidad;
use Illuminate\Database\Seeder;

class AsociacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Obtener una municipalidad existente
        $municipalidad = Municipalidad::first(); // Asegúrate de que haya al menos una municipalidad en la base de datos

        Asociacion::create([
            'municipalidad_id' => $municipalidad->id,
            'nombre' => 'Llachon',
            'descripcion' => 'Asociación dedicada a promover el ecoturismo en la zona de Capachica, ofreciendo experiencias de turismo vivencial con las comunidades locales.',
            'lugar' => 'Capachica, Puno',
            'estado' => true
        ]);

        Asociacion::create([
            'municipalidad_id' => $municipalidad->id,
            'nombre' => 'Chifron',
            'descripcion' => 'Asociación de agricultores de la región de Capachica que cultivan productos orgánicos, enfocados en la comercialización directa con los mercados locales.',
            'lugar' => 'Capachica, Puno',
            'estado' => true
        ]);

        Asociacion::create([
            'municipalidad_id' => $municipalidad->id,
            'nombre' => 'Isla Tikonata',
            'descripcion' => 'Asociación que promueve el desarrollo sostenible de la Isla Tikonata, enfocándose en la conservación ambiental y la educación turística para los visitantes.',
            'lugar' => 'Capachica, Puno',
            'estado' => true
        ]);

        Asociacion::create([
            'municipalidad_id' => $municipalidad->id,
            'nombre' => 'Siale-Paramis',
            'descripcion' => 'Asociación de productores agropecuarios que cultivan productos autóctonos como la quinua y la papa, contribuyendo al desarrollo de la economía local.',
            'lugar' => 'Capachica, Puno',
            'estado' => true
        ]);

        Asociacion::create([
            'municipalidad_id' => $municipalidad->id,
            'nombre' => 'Escallani',
            'descripcion' => 'Asociación orientada a la venta de artesanías típicas de Capachica, elaboradas por las comunidades locales, con énfasis en la preservación de las tradiciones culturales.',
            'lugar' => 'Capachica, Puno',
            'estado' => true
        ]);

        Asociacion::create([
            'municipalidad_id' => $municipalidad->id,
            'nombre' => 'Ccotos',
            'descripcion' => 'Asociación dedicada a la producción y comercialización de derivados lácteos en la región de Capachica, mejorando la calidad de vida de las familias productoras.',
            'lugar' => 'Capachica, Puno',
            'estado' => true
        ]);
    }
}
