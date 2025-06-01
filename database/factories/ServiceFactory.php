<?php

namespace Database\Factories;

use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ServiceFactory extends Factory
{
    protected $model = Service::class;

    public function definition()
    {
        $categories = ['Hospedaje', 'Turismo', 'Gastronomía', 'Artesanías', 'Transporte'];

        $category = $this->faker->randomElement($categories);

        $nameMap = [
            'Hospedaje' => 'Hospedaje',
            'Turismo' => 'Tours',
            'Gastronomía' => 'Gastronomía',
            'Artesanías' => 'Artesanías',
            'Transporte' => 'Transporte',
        ];

        $descriptionMap = [
            'Hospedaje' => 'Alojamiento cómodo y acogedor en espacios diseñados para ofrecer descanso y tranquilidad, con servicios adaptados a los viajeros.',
            'Turismo' => 'Excursiones guiadas que combinan cultura, naturaleza y aventura para descubrir destinos de manera auténtica y enriquecedora.',
            'Gastronomía' => 'Experiencias culinarias para degustar platos tradicionales y contemporáneos, con ingredientes frescos y sabores únicos.',
            'Artesanías' => 'Visitas a mercados y talleres locales para adquirir piezas artesanales auténticas que reflejan la cultura y tradición local.',
            'Transporte' => 'Servicios de traslado cómodos y seguros que facilitan el movimiento entre puntos clave del destino.',
        ];

        return [
            'id' => (string) Str::uuid(),
            'name' => $nameMap[$category],
            'description' => $descriptionMap[$category],
            'code' => $this->faker->unique()->bothify('??##'), // Aquí está el cambio para evitar duplicados
            'category' => $category,
            'status' => true,
        ];
    }
}
