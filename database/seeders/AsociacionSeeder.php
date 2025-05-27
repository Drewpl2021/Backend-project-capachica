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
        //COPIAR ssssasdasdasdasd
        // Obtener la primera municipalidad existente
        $municipalidad = Municipalidad::first();

        if (!$municipalidad) {
            $this->command->info('No hay municipalidades en la base de datos. Seeder de Asociacion no se ejecutó.');
            return;
        }

        $data = [
            [
                'municipalidad_id' => $municipalidad->id,
                'nombre' => 'Llachon',
                'url' => '/llachon',
                'descripcion' => 'Asociación dedicada a promover el ecoturismo en la zona de Capachica, ofreciendo experiencias de turismo vivencial con las comunidades locales.',
                'lugar' => 'Capachica, Puno',
                'estado' => true,
            ],
            [
                'municipalidad_id' => $municipalidad->id,
                'nombre' => 'Chifron',
                'url' => '/chifron',
                'descripcion' => 'Asociación de agricultores de la región de Capachica que cultivan productos orgánicos, enfocados en la comercialización directa con los mercados locales.',
                'lugar' => 'Capachica, Puno',
                'estado' => true,
            ],
            [
                'municipalidad_id' => $municipalidad->id,
                'nombre' => 'Isla Tikonata',
                'url' => '/isla-tikonata',
                'descripcion' => 'Asociación que promueve el desarrollo sostenible de la Isla Tikonata, enfocándose en la conservación ambiental y la educación turística para los visitantes.',
                'lugar' => 'Capachica, Puno',
                'estado' => true,
            ],
            [
                'municipalidad_id' => $municipalidad->id,
                'nombre' => 'Siale-Paramis',
                'url' => '/siale-paramis',
                'descripcion' => 'Asociación de productores agropecuarios que cultivan productos autóctonos como la quinua y la papa, contribuyendo al desarrollo de la economía local.',
                'lugar' => 'Capachica, Puno',
                'estado' => true,
            ],
            [
                'municipalidad_id' => $municipalidad->id,
                'nombre' => 'Escallani',
                'url' => '/escallani',
                'descripcion' => 'Asociación orientada a la venta de artesanías típicas de Capachica, elaboradas por las comunidades locales, con énfasis en la preservación de las tradiciones culturales.',
                'lugar' => 'Capachica, Puno',
                'estado' => true,
            ],
            [
                'municipalidad_id' => $municipalidad->id,
                'nombre' => 'Ccotos',
                'url' => '/ccotos',
                'descripcion' => 'Asociación dedicada a la producción y comercialización de derivados lácteos en la región de Capachica, mejorando la calidad de vida de las familias productoras.',
                'lugar' => 'Capachica, Puno',
                'estado' => true,
            ],
        ];

        foreach ($data as $asociacion) {
            Asociacion::create($asociacion);
        }
    }
}
