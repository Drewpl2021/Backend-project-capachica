<?php

namespace Database\Seeders;


use App\Models\Asociacion;
use App\Models\Img_asociacion;
use Illuminate\Database\Seeder;

class ImgAsociacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Obtener todas las asociaciones existentes
        $asociaciones = Asociacion::all(); // Obtener todas las asociaciones

        foreach ($asociaciones as $asociacion) {
            // Crear algunas imágenes de asociación para cada asociación
            Img_Asociacion::create([
                'asociacion_id' => $asociacion->id,
                'url_image' => 'https://consultasenlinea.mincetur.gob.pe/fichaInventario/foto.aspx?cod=471157',
                'estado' => true,
                'codigo' => 101
            ]);
            img_asociacion::create([
                'asociacion_id' => $asociacion->id,
                'url_image' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQYpsgH3y4Sc4KtA_O9XOL9M2f1kHvPCXM9Dg&s',
                'estado' => true,
                'codigo' => 102
            ]);
            Img_asociacion::create([
                'asociacion_id' => $asociacion->id,
                'url_image' => 'https://losviajesdeali.com/wp-content/uploads/2015/01/pen%C3%ADnsula-de-capachica-1-18.jpg',
                'estado' => true,
                'codigo' => 103
            ]);
        }
    }
}
