<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ServiceSedeer  extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $services = [
            [
                'name' => 'Hospedaje Familiar en Isla de los Uros',
                'description' => 'Hospedaje acogedor en casas tradicionales flotantes en la Isla de los Uros, con vistas al Lago Titicaca.',
                'code' => 'HOS-UR01',
                'base_price' => 40.00,
                'costo' => 40.00,
                'category' => 'Hospedaje',
                'status' => '1',
            ],
            [
                'name' => 'Tour Guiado a la Isla Taquile',
                'description' => 'Tour de día completo a la Isla Taquile, con guía local y actividades culturales tradicionales.',
                'code' => 'TOUR-TAQ1',
                'base_price' => 60.00,
                'costo' => 60.00,
                'category' => 'Turismo',
                'status' => '1',
            ],
            [
                'name' => 'Almuerzo Típico en Llachón',
                'description' => 'Disfruta de la gastronomía local con un almuerzo tradicional en Llachón, con productos frescos del lago y la sierra.',
                'code' => 'GAST-LLA1',
                'base_price' => 15.00,
                'costo' => 15.00,
                'category' => 'Gastronomía',
                'status' => '1',
            ],
            [
                'name' => 'Paseo en Bote por el Lago Titicaca',
                'description' => 'Excursión en bote por el Lago Titicaca, con paradas en pequeñas islas y paisajes naturales.',
                'code' => 'TOUR-BOAT1',
                'base_price' => 25.00,
                'costo' => 25.00,
                'category' => 'Turismo',
                'status' => '1',
            ],
            [
                'name' => 'Hospedaje en Casa Tradicional de Llachón',
                'description' => 'Hospedaje en casas tradicionales de la comunidad de Llachón, con desayuno incluido y ambiente familiar.',
                'code' => 'HOS-LLA01',
                'category' => 'Hospedaje',
                'status' => '1',
            ],
        ];

        foreach ($services as $service) {
            Service::create([
                'id' => Str::uuid(),
                'name' => $service['name'],
                'description' => $service['description'],
                'code' => $service['code'],
                'category' => $service['category'],
                'status' => $service['status'],
            ]);
        }
    }
}
