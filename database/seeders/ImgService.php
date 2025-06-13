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
        $imageSamples = [
            'Turismo' => [
                'https://images.unsplash.com/photo-1506744038136-46273834b3fb',
                'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee'
            ],
            'Artesanías' => [
                'https://images.unsplash.com/photo-1575936123452-b67c3203c357',
                'https://images.unsplash.com/photo-1606614603067-9b7b5b6e8b59'
            ],
            'Transporte' => [
                'https://images.unsplash.com/photo-1507525428034-b723cf961d3e',
                'https://images.unsplash.com/photo-1484291470158-b8f8d608850d'
            ],
            'Gastronomia' => [
                'https://images.unsplash.com/photo-1604908554183-d96ec4d1eec0',
                'https://images.unsplash.com/photo-1556912167-f556f1f39df4'
            ],
            'Hospedaje' => [
                'https://images.unsplash.com/photo-1554995207-c18c203602cb',
                'https://images.unsplash.com/photo-1576671081837-a088337b73a5'
            ],
            'default' => [
                'https://source.unsplash.com/random/800x600?travel',
                'https://source.unsplash.com/random/800x600?destination'
            ]
        ];

        $services = Service::all();

        foreach ($services as $service) {
            $categoryKey = strtolower($service->category);
            $urls = $imageSamples[$categoryKey] ?? $imageSamples['default'];

            foreach ($urls as $index => $imageUrl) {
                ModelsImgService::create([
                    'id' => Str::uuid(),
                    'service_id' => $service->id,
                    'imagen_url' => $imageUrl . '?auto=compress&cs=tinysrgb&h=650&w=940', // mejor calidad
                    'description' => "Imagen " . ($index + 1) . " para el servicio {$service->name}",
                    'code' => strtoupper($service->code) . "_IMG" . ($index + 1),
                ]);
            }
        }
    }
}
