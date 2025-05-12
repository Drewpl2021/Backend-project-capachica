<?php

namespace Database\Seeders;

use App\Models\SectionDetailEnd;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SectionDetail;
use Illuminate\Support\Str;

class SectionDetailEndSeeder extends Seeder
{
    public function run(): void
    {

        $sectionDetailEnds = [
            [
                'id' => Str::uuid(),
                'status' => true,
                'code' => '01',
                'image' => 'https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEhw7FAqnDeHNDznHcZR6zVzlV0uYLYcFl8Dfxfi69HebPFYzNc-OVOsvbEwA1UeBTxtRdchkVzl5nin2oM3vwKwOkXGMC-CERER1OwmQSNeKoBZ3JukENXfdHoEZu4BLDmp7TabntvQt2I/s640/IMG+11.jpg',
                'title' => 'Descubre un nuevo lugar',
                'description' => 'Encuentre excelentes lugares para hospedarse, comer, comprar o visitar de la mano de expertos locales.',
                'subtitle' => '',
                'section_detail_id' => SectionDetail::where('code', '01')->first()->id,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null
            ],
            [
                'id' => Str::uuid(),
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
                'id' => Str::uuid(),
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
                'id' => Str::uuid(),
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
                'id' => Str::uuid(),
                'status' => true,
                'code' => '05',
                'image' => 'https://www.peru.travel/Contenido/General/Imagen/es/836/1.1/hoteles-puno-titilaka-peru.jpg',
                'title' => 'Paisajefrente a la playa',
                'description' => '',
                'subtitle' => '',
                'section_detail_id' => SectionDetail::where('code', '03')->first()->id,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null
            ],
            [
                'id' => Str::uuid(),
                'status' => true,
                'code' => '06',
                'image' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ-4eq5fVpvvOxe6weATgOFJhrS5if2-REaqQ&s',
                'title' => 'Vacaciones en grupo',
                'description' => '',
                'subtitle' => '',
                'section_detail_id' => SectionDetail::where('code', '03')->first()->id,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null
            ],
            [
                'id' => Str::uuid(),
                'status' => true,
                'code' => '07',
                'image' => 'https://media-cdn.tripadvisor.com/media/attractions-splice-spp-674x446/09/18/e2/e5.jpg',
                'title' => 'Escapadas urbanas',
                'description' => '',
                'subtitle' => '',
                'section_detail_id' => SectionDetail::where('code', '03')->first()->id,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null
            ],
            [
                'id' => Str::uuid(),
                'status' => true,
                'code' => '08',
                'image' => 'https://i.pinimg.com/736x/0c/ef/94/0cef946cc9d9b378060ed2fa9b1ffad0.jpg',
                'title' => 'Lo mejor de Capachica',
                'description' => 'El Distrito Municipal de Capachica ofrece la mejor agencia de viajes para descubrir la magia del Lago Titicaca con experiencias únicas y auténticas. A través de tours exclusivos, podrá explorar las islas flotantes y naturales, como Amantaní y Tikonata, con guías locales que lo sumergirán en la cultura y las tradiciones ancestrales de la región. Disfrute del turismo vivencial, compartiendo la hospitalidad de las comunidades quechuas, su gastronomía y su estilo de vida. Nuestras rutas lo llevarán a paisajes impresionantes, caminatas escénicas y paseos en bote al amanecer, permitiéndole conectar con la naturaleza en su máxima expresión. Viva una aventura inolvidable con la mejor agencia de viajes de Capachica y deje que el encanto del Titicaca convierta su viaje en una experiencia única.',
                'subtitle' => '',
                'section_detail_id' => SectionDetail::where('code', '04')->first()->id,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null
            ],
            [
                'id' => Str::uuid(),
                'status' => true,
                'code' => '09',
                'image' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT4ByLoCsbL0W3PjvGJsy69jBxjluWY12Bw8wMMlvOb1bhzBJwC9HayQ4tltZ3uPr0omkY&usqp=CAU',
                'title' => 'Llachon',
                'description' => 'Conocido por su impresionante belleza natural y sus tradiciones culturales',
                'subtitle' => '',
                'section_detail_id' => SectionDetail::where('code', '05')->first()->id,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null
            ],
            [
                'id' => Str::uuid(),
                'status' => true,
                'code' => '10',
                'image' => 'https://www.chullostravelperu.com/blog/wp-content/uploads/2023/02/PLAYA-CHIFRON-DE-CAPACHICA-PUNO.jpg',
                'title' => 'Chifrón',
                'description' => 'Chifrón cuenta con una extensa playa de arena que permite disfrutar de las aguas cristalinas del lago Titicaca',
                'subtitle' => '',
                'section_detail_id' => SectionDetail::where('code', '05')->first()->id,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null
            ],
            [
                'id' => Str::uuid(),
                'status' => true,
                'code' => '11',
                'image' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/3/3e/Vista_de_ccotos.jpg/1200px-Vista_de_ccotos.jpg',
                'title' => 'Ccotos',
                'description' => 'Ofrece una experiencia autentica y vivencial permitiendo a los visitantes conocer la cultura y la vida cotidiana de la comunidad',
                'subtitle' => '',
                'section_detail_id' => SectionDetail::where('code', '05')->first()->id,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null
            ],
            [
                'id' => Str::uuid(),
                'status' => true,
                'code' => '12',
                'image' => 'https://www.astursperu.org/wp-content/uploads/2017/07/isla-ticonata-asturs-peru.jpg',
                'title' => 'Isla Tikonata',
                'description' => 'Se caracteriza por sus casas construidas en forma circular, similar a los Putucos preIncas',
                'subtitle' => '',
                'section_detail_id' => SectionDetail::where('code', '05')->first()->id,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null
            ],
            [
                'id' => Str::uuid(),
                'status' => true,
                'code' => '13',
                'image' => 'https://q-xx.bstatic.com/xdata/images/hotel/840x460/311494840.jpg?k=e773cdff610d551d31d6cdc80c805c445d892b02cdb29eba15face805ce5f45d&o=',
                'title' => 'Llachon',
                'description' => 'Los visitantes se hospedarán en habitaciones tradicionales de adobe, decoradas de manera autentica.',
                'subtitle' => '',
                'section_detail_id' => SectionDetail::where('code', '06')->first()->id,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null
            ],
            [
                'id' => Str::uuid(),
                'status' => true,
                'code' => '14',
                'image' => 'https://images.trvl-media.com/lodging/23000000/22500000/22492800/22492790/9f98b66a.jpg?impolicy=resizecrop&rw=575&rh=575&ra=fill',
                'title' => 'Ccotos',
                'description' => 'Las cabañas se encuentran ubicadas al lado de las de las familias, a poca distancia del lago Titicaca',
                'subtitle' => '',
                'section_detail_id' => SectionDetail::where('code', '06')->first()->id,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null
            ],
            [
                'id' => Str::uuid(),
                'status' => true,
                'code' => '15',
                'image' => 'https://media-cdn.tripadvisor.com/media/photo-s/13/7a/0b/a0/totorani-inn.jpg',
                'title' => 'Isla Tikonata',
                'description' => 'Los visitantes se hospedarán en hermosas habitaciones tradicionales circulares, de adobe y decoradas de manera autentica.',
                'subtitle' => '',
                'section_detail_id' => SectionDetail::where('code', '06')->first()->id,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null
            ],
            [
                'id' => Str::uuid(),
                'status' => true,
                'code' => '16',
                'image' => 'https://q-xx.bstatic.com/xdata/images/hotel/max400/310830329.jpg?k=cc953a1bd6ffa166ebcadc5194916ecc2dd0ebfecb4aa2ca03091453f12de0e4',
                'title' => 'Siale - Paramis',
                'description' => 'Los visitantes se hospedarán en habitaciones tradicionales, decoradas de manera autentica.',
                'subtitle' => '',
                'section_detail_id' => SectionDetail::where('code', '06')->first()->id,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null
            ],
            [
                'id' => Str::uuid(),
                'status' => true,
                'code' => '17',
                'image' => 'https://images.trvl-media.com/lodging/100000000/99250000/99242000/99241942/ac6f8a38.jpg?impolicy=fcrop&w=357&h=201&p=1&q=medium',
                'title' => 'Escallani',
                'description' => 'Los visitantes se hospedarán en hermosas habitaciones tradicionales circulares, de adobe y decoradas de manera autentica.',
                'subtitle' => '',
                'section_detail_id' => SectionDetail::where('code', '06')->first()->id,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null
            ],
            [
                'id' => Str::uuid(),
                'status' => true,
                'code' => '18',
                'image' => 'https://municapachica.org.pe/wp-content/uploads/2024/08/453867459_483378587980487_7754598693501788576_n-1024x683.jpg',
                'title' => 'Siale - Paramis',
                'description' => 'La alimentación se basa en productos locales y ofrece la oportunidad de probar la "comida Novoandina"',
                'subtitle' => '',
                'section_detail_id' => SectionDetail::where('code', '07')->first()->id,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null
            ],
            [
                'id' => Str::uuid(),
                'status' => true,
                'code' => '19',
                'image' => 'https://turismocapachica.wordpress.com/wp-content/uploads/2009/12/productos-locales_ccotos.jpg',
                'title' => 'Escallani',
                'description' => 'Los restaurantes rurales ofrecen platos típicos, como carachis, pejerrey y mauris"',
                'subtitle' => '',
                'section_detail_id' => SectionDetail::where('code', '07')->first()->id,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null
            ],
            [
                'id' => Str::uuid(),
                'status' => true,
                'code' => '20',
                'image' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ2dUuCZe4Q4tjbHu-je-9aCJL5RNXN7qmdKA&s',
                'title' => 'Isla Tikonata',
                'description' => 'Platos típicos de la zona en restaurantes rurales, con productos locales de la parcelas y pescados del lago Titicaca',
                'subtitle' => '',
                'section_detail_id' => SectionDetail::where('code', '07')->first()->id,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null
            ],
            [
                'id' => Str::uuid(),
                'status' => true,
                'code' => '21',
                'image' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSAp3kUc1KTu7f1VIikOAVF6-Tt9Mdw7rv8eA&s',
                'title' => 'Chifrón',
                'description' => 'Plano típico de la región con énfasis en pescados del lago Titicaca especialmente truchas"',
                'subtitle' => '',
                'section_detail_id' => SectionDetail::where('code', '07')->first()->id,
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
