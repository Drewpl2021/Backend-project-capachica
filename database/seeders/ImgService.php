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

        foreach ($services as $service) {
            // Ejemplo: Crear 2 imágenes por servicio con URLs de ejemplo
            for ($i = 1; $i <= 2; $i++) {
                ModelsImgService::create([
                    'id' => Str::uuid(),
                    'service_id' => $service->id,
                    'imagen_url' => "https://example.com/images/{$service->code}_img{$i}.jpg",
                    'description' => "Imagen {$i} para el servicio {$service->name}",
                    'code' => strtoupper($service->code) . "_IMG{$i}",
                ]);
            }
        }
    }
}
