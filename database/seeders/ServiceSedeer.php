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
                'name' => 'Hospedaje',
                'description' => 'Alojamiento cómodo y acogedor en espacios diseñados para ofrecer descanso y tranquilidad, con servicios adaptados a los viajeros.',
                'code' => '01',
                'category' => 'Hospedaje',
                'status' => true,
            ],
            [
                'name' => 'Tours',
                'description' => 'Excursiones guiadas que combinan cultura, naturaleza y aventura para descubrir destinos de manera auténtica y enriquecedora.',
                'code' => '02',
                'category' => 'Turismo',
                'status' => true,
            ],
            [
                'name' => 'Gastronomía',
                'description' => 'Experiencias culinarias para degustar platos tradicionales y contemporáneos, con ingredientes frescos y sabores únicos.',
                'code' => '03',
                'category' => 'Gastronomía',
                'status' => true,
            ],
            [
                'name' => 'Artesanías',
                'description' => 'Visitas a mercados y talleres locales para adquirir piezas artesanales auténticas que reflejan la cultura y tradición local.',
                'code' => '04',
                'category' => 'Artesanías',
                'status' => true,
            ],
            [
                'name' => 'Transporte',
                'description' => 'Servicios de traslado cómodos y seguros que facilitan el movimiento entre puntos clave del destino.',
                'code' => '05',
                'category' => 'Transporte',
                'status' => true,
            ],
        ];

        foreach ($services as $service) {
            Service::updateOrCreate(
                ['code' => $service['code']],
                [
                    'id' => (string) Str::uuid(),
                    'name' => $service['name'],
                    'description' => $service['description'],
                    'category' => $service['category'],
                    'status' => $service['status'],
                ]
            );
        }
    }
}
