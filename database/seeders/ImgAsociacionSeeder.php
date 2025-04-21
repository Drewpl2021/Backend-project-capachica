<?php

namespace Database\Seeders;

use App\Models\Img_Asociacion;
use App\Models\Asociacion; // Asegúrate de tener el modelo de Asociacion
use Illuminate\Database\Seeder;

class ImgAsociacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Obtener la primera asociación existente
        $asociacion = Asociacion::first(); // Asegúrate de que haya al menos una asociación en la base de datos

        // Crear algunas imágenes de asociación de ejemplo
        Img_Asociacion::create([
            'asociacion_id' => $asociacion->id,
            'url_image' => 'https://example.com/imagen1.jpg',
            'estado' => true,
            'codigo' => 101
        ]);

        Img_Asociacion::create([
            'asociacion_id' => $asociacion->id,
            'url_image' => 'https://example.com/imagen2.jpg',
            'estado' => true,
            'codigo' => 102
        ]);

        // Agrega más imágenes si es necesario
    }
}
