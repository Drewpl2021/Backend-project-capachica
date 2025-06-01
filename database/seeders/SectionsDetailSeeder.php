<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Section;
use App\Models\SectionDetail;
use Illuminate\Support\Str;

class SectionsDetailSeeder extends Seeder
{
    public function run(): void
    {
        // Buscar las secciones por su código y obtener el `id` automáticamente
        $sectionDetails = [
            [
                'id' => Str::uuid(),
                'status' => true,
                'code' => '01',
                'title' => 'Descrubre la Península de Capachica',
                'description' => '',
                'section_id' => Section::where('code', '01')->first()->id,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null
            ],
            [
                'id' => Str::uuid(),
                'status' => true,
                'code' => '02',
                'title' => 'Descubre - Vive - Conecta',
                'description' => '',
                'section_id' => Section::where('code', '01')->first()->id,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null
            ],
            [
                'id' => Str::uuid(),
                'status' => true,
                'code' => '03',
                'title' => 'Vea nuestras ultimas ideas de vacaciones',
                'description' => '',
                'section_id' => Section::where('code', '01')->first()->id,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null
            ],
            [
                'id' => Str::uuid(),
                'status' => true,
                'code' => '04',
                'title' => 'Lo mejor de Capachica',
                'description' => 'El Distrito Municipal de Capachica ofrece la mejor agencia de viajes para descubrir la magia del Lago Titicaca con experiencias únicas y auténticas. A través de tours exclusivos, podrá explorar las islas flotantes y naturales, como Amantaní y Tikonata, con guías locales que lo sumergirán en la cultura y las tradiciones ancestrales de la región. Disfrute del turismo vivencial, compartiendo la hospitalidad de las comunidades quechuas, su gastronomía y su estilo de vida. Nuestras rutas lo llevarán a paisajes impresionantes, caminatas escénicas y paseos en bote al amanecer, permitiéndole conectar con la naturaleza en su máxima expresión. Viva una aventura inolvidable con la mejor agencia de viajes de Capachica y deje que el encanto del Titicaca convierta su viaje en una experiencia única.',
                'section_id' => Section::where('code', '01')->first()->id,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null
            ],
            [
                'id' => Str::uuid(),
                'status' => true,
                'code' => '05',
                'title' => 'Destinos mas Populares',
                'description' => '',
                'section_id' => Section::where('code', '01')->first()->id,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null
            ],
            [
                'id' => Str::uuid(),
                'status' => true,
                'code' => '06',
                'title' => 'Hospedajes populares',
                'description' => '',
                'section_id' => Section::where('code', '01')->first()->id,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null
            ],
            [
                'id' => Str::uuid(),
                'status' => true,
                'code' => '07',
                'title' => 'Mejores Lugares para Comer',
                'description' => '',
                'section_id' => Section::where('code', '01')->first()->id,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null
            ],
                        [
                'id' => Str::uuid(),
                'status' => true,
                'code' => '08',
                'title' => 'Haga que su recorrido sea memorable y seguro con nosotros',
                'description' => 'Somos una agencia de viajes local con sede en Capachica, Puno, un lugar mágico a orillas del lago Titicaca. Nuestra misión es conectar a los viajeros con la rica cultura andina, la paz de sus paisajes y la calidez de nuestras comunidades. Ya sea que esté buscando aventuras de senderismo, auténticas casas de familia o relajación junto al lago, nuestro equipo garantiza una experiencia segura e inolvidable en cada paso del camino.',
                'section_id' => Section::where('code', '01')->first()->id,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null
            ]
            // Agrega más detalles si es necesario
        ];

        foreach ($sectionDetails as $detail) {
            SectionDetail::create($detail);  // Crea los detalles de sección
        }
    }
}
