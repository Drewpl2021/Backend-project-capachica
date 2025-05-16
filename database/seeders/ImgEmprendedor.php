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
        // Obtener todos los emprendedores
        $emprendedores = Emprendedor::all();

        // Arreglo con las imágenes a crear por cada emprendedor
        $imagenes = [
            [
                'url_image' => 'https://example.com/imagen1.jpg',
                'code' => '101',
            ],
            [
                'url_image' => 'https://example.com/imagen2.jpg',
                'code' => '102',
            ],
            [
                'url_image' => 'https://example.com/imagen3.jpg',
                'code' => '103',
            ],
        ];

        foreach ($emprendedores as $emprendedor) {
            foreach ($imagenes as $img) {
                ModelsImgEmprendedor::create([
                    'emprendedor_id' => $emprendedor->id,
                    'url_image' => $img['url_image'],
                    'description' => "Imagen para el emprendedor {$emprendedor->name_family}",
                    'estado' => true,
                    'code' => $img['code'] . '-' . $emprendedor->id, // para código único combinando id
                ]);
            }
        }
    }
}
