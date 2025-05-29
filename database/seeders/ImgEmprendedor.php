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
        $emprendedores = Emprendedor::all();
        $imagenes = [
            'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1494526585095-c41746248156?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1508214751196-bcfd4ca60f91?auto=format&fit=crop&w=800&q=80',
        ];

        foreach ($emprendedores as $emprendedor) {
            foreach ($imagenes as $index => $url) {
                $numeroImagen = $index + 1;
                ModelsImgEmprendedor::create([
                    'emprendedor_id' => $emprendedor->id,
                    'url_image' => $url,
                    'description' => "Imagen {$numeroImagen} para la familia {$emprendedor->name_family}",
                    'estado' => true,
                    'code' => 'IMG-' . strtoupper(Str::random(4)) . '-' . $emprendedor->name_family,
                ]);
            }
        }
    }
}
