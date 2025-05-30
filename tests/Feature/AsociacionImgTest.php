<?php

namespace Tests\Feature;

use App\Models\Img_asociacion;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Asociacion;

class AsociacionImgTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function puede_crear_una_asociacion_con_imagen()
    {
        $asociacion = Asociacion::factory()->create();

        $imagen = Img_asociacion::factory()->create([
            'asociacion_id' => $asociacion->id,
        ]);

        $this->assertDatabaseHas('asociacions', [
            'id' => $asociacion->id,
            'nombre' => $asociacion->nombre,
        ]);

        $this->assertDatabaseHas('img_asociacions', [
            'id' => $imagen->id,
            'asociacion_id' => $asociacion->id,
            'codigo' => $imagen->codigo,
        ]);
    }

    /** @test */
    public function puede_crear_asociacion_con_estado_true()
    {
        $asociacion = Asociacion::factory()->create(['estado' => true]);

        $this->assertDatabaseHas('asociacions', [
            'id' => $asociacion->id,
            'estado' => true,
        ]);
    }

    /** @test */
    public function asociacion_tiene_imagenes_relacionadas()
    {
        $asociacion = Asociacion::factory()->create();

        Img_asociacion::factory()->count(3)->create([
            'asociacion_id' => $asociacion->id,
        ]);

        $this->assertCount(3, $asociacion->imgAsociacions);
    }

    /** @test */
    public function codigo_de_imagenes_es_unico()
    {
        $imagen1 = Img_asociacion::factory()->create();
        $imagen2 = Img_asociacion::factory()->create();

        $this->assertNotEquals($imagen1->codigo, $imagen2->codigo);
    }
}
