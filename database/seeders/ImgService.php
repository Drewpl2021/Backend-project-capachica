<?php

namespace Database\Seeders;

use App\Models\ImgService as ModelsImgService;
use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ImgService extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
        {
            $services = Service::all();

            // Imágenes específicas por categoría
            $imagenesPorCategoria = [
                'Hospedaje' => [
                    'https://images.trvl-media.com/lodging/35000000/34520000/34512400/34512319/45f26861.jpg?impolicy=fcrop&w=357&h=201&p=1&q=medium',
                    'https://images.trvl-media.com/lodging/58000000/57250000/57240700/57240698/607b3768.jpg?impolicy=resizecrop&rw=575&rh=575&ra=fill',
                    'https://images.trvl-media.com/lodging/92000000/91170000/91161900/91161805/321b92d8.jpg?impolicy=fcrop&w=357&h=201&p=1&q=medium',
                ],
                'Turismo' => [
                    'https://images.trvl-media.com/lodging/92000000/91170000/91161900/91161805/321b92d8.jpg?impolicy=fcrop&w=357&h=201&p=1&q=medium',
                    'https://dynamic-media-cdn.tripadvisor.com/media/photo-o/0a/a8/0a/10/catedral-de-puno.jpg?w=400&h=-1&s=1',
                    'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTrlJR3EaU_cBeKTfQmgzPuJnN7b_AlQGAULVyHUrL17rKCcR1D55h3UwjLuBDqhCHIq1E&usqp=CAU',
                ],
                'Gastronomía' => [
                    'https://comidasbolivianas.org/wp-content/uploads/2020/06/ispis-comida-tradicional-de-bolivia-1024x648.jpeg',
                    'https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEhu_RC7RqNH3HTuWjmlygCkNF7MIVIWp8FTb-o2GZ8GSEIVHwKarpA61VLinwz9InZnW0ZzjDNsh7DoKo39nbqSwuTimKzpZa9VhZsGIb6eRR2niYj7janeGrjddLRlg-VMrHBHLWKKajw/s1600/10351677_836194903065829_4161561282916266271_n.jpg',
                    'https://scontent.ftcq3-1.fna.fbcdn.net/v/t39.30808-6/475470390_2640113702850066_5529707664951068124_n.jpg?_nc_cat=107&ccb=1-7&_nc_sid=833d8c&_nc_ohc=9AoroDVsoY4Q7kNvwG29imP&_nc_oc=AdmxXDWOA1JkrODWHGe2Sc2z87lYJ_Wmf2DyHPq2v_K9mypKoaj7hVrxfwqqb4iRq4zHBB6yZyu6W-N3SSx9brv0&_nc_zt=23&_nc_ht=scontent.ftcq3-1.fna&_nc_gid=-1wz650nRwj8HaFxuVsLFw&oh=00_AfIarjwNAj89fBRCOd8vhjSuqaYjp88V1flGH-wsZTuO-w&oe=682D4F30',
                ],
                'Artesanías' => [
                    'https://turismocapachica.wordpress.com/wp-content/uploads/2009/12/artesanias.jpg',
                    'https://portal.andina.pe/EDPFotografia3/thumbnail/2022/09/28/000901648M.jpg',
                    'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTPyaVPbDh6E_qzqdM4DNK-2tYdEVNPIsYj2t6QYGn7Bk5Jl_-l-il9ArgmN0ablhaBYF0&usqp=CAU',
                ],
                'Transporte' => [
                    'https://www.turismolevita.com/img/img01.jpg',
                    'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQL1mzx_X8Y2aOmrkPOiULYoe4FmHGxjO070w&s',
                    'https://turismoyami.com/wp-content/uploads/2025/01/2.webp',
                ],
            ];

            foreach ($services as $service) {
                $categoria = $service->category;

                // Verificamos que existan imágenes para la categoría
                if (!isset($imagenesPorCategoria[$categoria])) {
                    // Si no hay imágenes definidas para la categoría, saltamos o ponemos imágenes genéricas
                    continue;
                }

                $imagenes = $imagenesPorCategoria[$categoria];

                foreach ($imagenes as $index => $url) {
                    $numeroImagen = $index + 1;
                    ModelsImgService::create([
                        'id' => Str::uuid(),
                        'service_id' => $service->id,
                        'imagen_url' => $url,
                        'description' => "Imagen {$numeroImagen} para el servicio {$service->name} (Categoría: {$categoria})",
                        'code' => strtoupper($service->code) . "_IMG" . $numeroImagen,
                    ]);
                }
            }
        }
}
