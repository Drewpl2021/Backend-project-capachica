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
                'description' => 'Alojamiento cómodo y acogedor en espacios diseñados para ofrecer descanso y tranquilidad, con servicios adaptados a las necesidades de viajeros de todo el mundo.',
                'code' => 'HOS-001',
                'category' => 'Hospedaje',
                'status' => '1',
            ],
            [
                'name' => 'Tours',
                'description' => 'Excursiones guiadas que combinan cultura, naturaleza y aventura para que los viajeros descubran y disfruten destinos de manera auténtica y enriquecedora.',
                'code' => 'TOUR-001',
                'category' => 'Turismo',
                'status' => '1',
            ],
            [
                'name' => 'Gastronomía',
                'description' => 'Experiencias culinarias que permiten a los visitantes degustar platos tradicionales y contemporáneos, destacando ingredientes frescos y sabores únicos.',
                'code' => 'GAST-001',
                'category' => 'Gastronomía',
                'status' => '1',
            ],
            [
                'name' => 'Artesanías',
                'description' => 'Visitas a mercados y talleres locales donde se pueden adquirir piezas artesanales auténticas que reflejan la cultura y tradición del destino.',
                'code' => 'ARTS-001',
                'category' => 'Artesanias',
                'status' => '1',
            ],
            [
                'name' => 'Transporte',
                'description' => 'Servicios de traslado cómodos y seguros que facilitan el movimiento de los viajeros entre puntos clave del destino, garantizando puntualidad y atención personalizada.',
                'code' => 'TRANS-001',
                'category' => 'Transporte',
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
