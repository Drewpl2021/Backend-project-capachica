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
        $emprendedores = EmprendedorService::all();

        $imagenes = [
            [
                'url_image' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTRYzzAWe4X_PjGyaWOwV5AJoMbyCnN-43PsA&s',
                'code' => '101',
            ],
            [
                'url_image' => 'https://i0.wp.com/foodandpleasure.com/wp-content/uploads/2022/11/hoteles-boho-chic-mexico-habitas-bacalar-2.jpeg?resize=600%2C401&ssl=1',
                'code' => '102',
            ],
            [
                'url_image' => 'https://cf.bstatic.com/xdata/images/hotel/max1024x768/528629425.jpg?k=a14193160f63cc0b4f05d9d37ea4b3d134536504920310e018b66e5b84671afd&o=&hp=1',
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
                    'code' => $img['code'] . '-' . $emprendedor->id,
                ]);
            }
        }
    }
}
