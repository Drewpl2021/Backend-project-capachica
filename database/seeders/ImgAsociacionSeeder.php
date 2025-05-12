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
        // Obtener todas las asociaciones existentes
        $asociaciones = Asociacion::all(); // Obtener todas las asociaciones

        foreach ($asociaciones as $asociacion) {
            // Crear algunas imágenes de asociación para cada asociación
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
            Img_Asociacion::create([
                'asociacion_id' => $asociacion->id,
                'url_image' => 'https://example.com/imagen3.jpg',
                'estado' => true,
                'codigo' => 103
            ]);
        }
    }
}
