<?php

namespace Database\Seeders;

use App\Models\SectionDetailEnd;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SectionDetail;


class SectionDetailEndSeeder extends Seeder
{
    public function run(): void
    {

        $sectionDetailEnds = [
            [
                'id' => \Str::uuid(),
                'status' => true,
                'code' => '01',
                'image' => 'image1.jpg',
                'title' => 'Descubre un nuevo lugar',
                'description' => 'Encuentre excelentes lugares para hospedarse, comer, comprar o visitar de la mano de expertos locales.',
                'subtitle' => '',
                'section_detail_id' => SectionDetail::where('code', '01')->first()->id,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null
            ],
            [
                'id' => \Str::uuid(),
                'status' => true,
                'code' => '02',
                'image' => '',
                'title' => 'Descubre Capachica',
                'description' => 'Un rincón único a orillas del majestuoso Lago Titicaca, donde la naturaleza y la cultura ancestral se entrelazan. Déjese cautivar por sus impresionantes paisajes y su gente acogedora.',
                'subtitle' => '',
                'section_detail_id' => SectionDetail::where('code', '02')->first()->id,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null
            ],
            [
                'id' => \Str::uuid(),
                'status' => true,
                'code' => '03',
                'image' => '',
                'title' => 'Vive la Experiencia',
                'description' => 'Sumérgete en la autenticidad de sus comunidades quechuas, disfruta de la gastronomía local y explora sus islas flotantes y naturales. Un destino lleno de historia y tradición.',
                'subtitle' => '',
                'section_detail_id' => SectionDetail::where('code', '02')->first()->id,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null
            ],
            [
                'id' => \Str::uuid(),
                'status' => true,
                'code' => '04',
                'image' => '',
                'title' => 'Relájate y conéctate',
                'description' => 'En Capachica, la tranquilidad del lago y el aire puro andino te invitan a desconectarte del estrés diario. Un lugar ideal para recargar energías y crear momentos inolvidables.',
                'subtitle' => '',
                'section_detail_id' => SectionDetail::where('code', '02')->first()->id,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null
            ],
            [
                'id' => \Str::uuid(),
                'status' => true,
                'code' => '05',
                'image' => 'image1.jpg',
                'title' => 'Paisajefrente a la playa',
                'description' => '',
                'subtitle' => '',
                'section_detail_id' => SectionDetail::where('code', '03')->first()->id,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null
            ],
            [
                'id' => \Str::uuid(),
                'status' => true,
                'code' => '06',
                'image' => 'image1.jpg',
                'title' => 'Vacaciones en grupo',
                'description' => '',
                'subtitle' => '',
                'section_detail_id' => SectionDetail::where('code', '03')->first()->id,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null
            ],
            [
                'id' => \Str::uuid(),
                'status' => true,
                'code' => '07',
                'image' => 'image1.jpg',
                'title' => 'Escapadas urbanas',
                'description' => '',
                'subtitle' => '',
                'section_detail_id' => SectionDetail::where('code', '03')->first()->id,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null
            ],
            [
                'id' => \Str::uuid(),
                'status' => true,
                'code' => '07',
                'image' => 'image1.jpg',
                'title' => 'Lo mejor de Capachica',
                'description' => 'El Distrito Municipal de Capachica ofrece la mejor agencia de viajes para descubrir la magia del Lago Titicaca con experiencias únicas y auténticas. A través de tours exclusivos, podrá explorar las islas flotantes y naturales, como Amantaní y Tikonata, con guías locales que lo sumergirán en la cultura y las tradiciones ancestrales de la región. Disfrute del turismo vivencial, compartiendo la hospitalidad de las comunidades quechuas, su gastronomía y su estilo de vida. Nuestras rutas lo llevarán a paisajes impresionantes, caminatas escénicas y paseos en bote al amanecer, permitiéndole conectar con la naturaleza en su máxima expresión. Viva una aventura inolvidable con la mejor agencia de viajes de Capachica y deje que el encanto del Titicaca convierta su viaje en una experiencia única.',
                'subtitle' => '',
                'section_detail_id' => SectionDetail::where('code', '04')->first()->id,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null
            ],
        ];

        // Insertar los datos en la base de datos
        foreach ($sectionDetailEnds as $detail) {
            SectionDetailEnd::create($detail);
        }
    }
}
