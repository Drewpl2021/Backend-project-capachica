<?php

namespace Database\Seeders;

use App\Models\Emprendedor;
use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EmprendimientoServiceSeeder extends Seeder
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

<<<<<<< HEAD:database/seeders/EmprendimientoServiceSedeer.php
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
=======
        foreach ($emprendedores as $emprendedor) {
            $selectedServices = $services->shuffle()->take(rand(1, 3));

            foreach ($selectedServices as $service) {
                $exists = DB::table('emprendedor_service')
                    ->where('service_id', $service->id)
                    ->where('emprendedor_id', $emprendedor->id)
                    ->exists();
>>>>>>> 55ea28b19b7b3429fd087cdd866f5312eb39435e:database/seeders/EmprendimientoServiceSeeder.php

                // Si hay categoría y imágenes definidas, las usamos
                if ($categoria && isset($imagenesPorCategoria[$categoria])) {
                    $imagenes = $imagenesPorCategoria[$categoria];
                }

<<<<<<< HEAD:database/seeders/EmprendimientoServiceSedeer.php
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
=======
                $randomSuffix = strtoupper(Str::random(3));
                $costoBase = rand(50, 200);

                $name = "{$service->name} Exclusivo {$randomSuffix} de {$emprendedor->razon_social}";
                $description = "Servicio {$service->name} personalizado para {$emprendedor->razon_social} - Código {$randomSuffix}";

                DB::table('emprendedor_service')->insert([
                    'id' => (string) Str::uuid(),
                    'service_id' => $service->id,
                    'emprendedor_id' => $emprendedor->id,
                    'code' => 'CODE-' . $randomSuffix,
                    'status' => true,
                    'costo' => $costoBase,
                    'cantidad' => rand(1, 30),
                    'costo_unidad' => round($costoBase / rand(1, 5), 2),
                    'name' => $name,
                    'description' => $description,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
>>>>>>> 55ea28b19b7b3429fd087cdd866f5312eb39435e:database/seeders/EmprendimientoServiceSeeder.php
            }
        }
}
