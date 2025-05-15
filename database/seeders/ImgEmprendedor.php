<?php

namespace Database\Seeders;

use App\Models\Emprendedor;
use App\Models\ImgEmprendedor as ModelsImgEmprendedor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ImgEmprendedor extends Seeder
{
    public function run()
    {
        // Obtener todas las asociaciones existentes
        $asociaciones = Emprendedor::all(); // Obtener todas las asociaciones

        foreach ($asociaciones as $asociacion) {
            // Crear algunas imágenes de asociación para cada asociación
            ModelsImgEmprendedor::create([
                'emprendedor_id' => $asociacion->id,
                'url_image' => 'https://example.com/imagen1.jpg',
                'description' => "Imagen para el servicio {$asociacion->name}",
                'estado' => true,
                'code' => '101'
            ]);
            ModelsImgEmprendedor::create([
                'emprendedor_id' => $asociacion->id,
                'url_image' => 'https://example.com/imagen2.jpg',
                'description' => "Imagen para el servicio {$asociacion->name}",
                'estado' => true,
                'code' => '102'
            ]);
            ModelsImgEmprendedor::create([
                'emprendedor_id' => $asociacion->id,
                'url_image' => 'https://example.com/imagen3.jpg',
                'estado' => true,
                'description' => "Imagen para el servicio {$asociacion->name}",
                'code' => '103'
            ]);
        }
    }
}
