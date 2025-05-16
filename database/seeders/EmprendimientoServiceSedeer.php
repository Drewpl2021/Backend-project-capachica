<?php

namespace Database\Seeders;

use App\Models\Emprendedor;
use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class EmprendimientoServiceSedeer extends Seeder
{
    public function run()
        {
            $emprendedorServices = EmprendedorService::all();

            // Si tienes categorías o tipos, podrías mapear imágenes por categoría
            // Ejemplo:
            $imagenesPorCategoria = [
                'Hospedaje' => [
                    'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTRYzzAWe4X_PjGyaWOwV5AJoMbyCnN-43PsA&s',
                    'https://i0.wp.com/foodandpleasure.com/wp-content/uploads/2022/11/hoteles-boho-chic-mexico-habitas-bacalar-2.jpeg?resize=600%2C401&ssl=1',
                ],
                'Tours' => [
                    'https://cf.bstatic.com/xdata/images/hotel/max1024x768/528629425.jpg?k=a14193160f63cc0b4f05d9d37ea4b3d134536504920310e018b66e5b84671afd&o=&hp=1',
                    'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=800&q=80',
                ],
                // agrega más categorías si quieres
            ];

            // Opcional: imágenes genéricas si no hay categoría o no quieres filtrar
            $imagenesGenericas = [
                'https://images.unsplash.com/photo-1526772662000-3f88f10405ff?auto=format&fit=crop&w=800&q=80',
                'https://images.unsplash.com/photo-1486308510493-cb4096d6f55c?auto=format&fit=crop&w=800&q=80',
                'https://images.unsplash.com/photo-1519125323398-675f0ddb6308?auto=format&fit=crop&w=800&q=80',
            ];

            foreach ($emprendedorServices as $eservice) {
                // Aquí puedes obtener la categoría si la tienes en $eservice (por ej: $eservice->category)
                // Como ejemplo lo dejo vacío para que uses imágenes genéricas siempre.
                $categoria = $eservice->category ?? null;

                $imagenes = $imagenesGenericas;

                // Si hay categoría y imágenes definidas, las usamos
                if ($categoria && isset($imagenesPorCategoria[$categoria])) {
                    $imagenes = $imagenesPorCategoria[$categoria];
                }

                foreach ($imagenes as $index => $url) {
                    ImgEmprendedorService::create([
                        'id' => Str::uuid(),
                        'emprendedor_service_id' => $eservice->id,
                        'url_image' => $url,
                        'description' => "Imagen " . ($index + 1) . " para el servicio personalizado {$eservice->name}",
                        'estado' => true,
                        'code' => 'IMG-' . strtoupper(Str::random(4)) . '-' . substr($eservice->code ?? 'XXX', 0, 3),
                    ]);
                }
            }
        }
}
