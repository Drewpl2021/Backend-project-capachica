<?php

namespace Database\Seeders;

use App\Models\EmprendedorService;
use App\Models\ImgEmprendedorService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ImgEmprendedorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener todos los emprendedores
        $emprendedores = EmprendedorService::all();

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
                ImgEmprendedorService::create([
                    'emprendedor_service_id' => $emprendedor->id,
                    'url_image' => $img['url_image'],
                    'description' => "Imagen para el emprendedor {$emprendedor->name_family}",
                    'estado' => true,
                    'code' => $img['code'] . '-' . $emprendedor->id, // para código único combinando id
                ]);
            }
        }
    }
}
