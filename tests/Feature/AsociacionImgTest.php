<?php

namespace Tests\Feature;

use App\Models\Asociacion;
use App\Models\User;
use App\Models\Municipalidad;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AsociacionImgTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;
    protected $municipalidad;

    protected function setUp(): void
    {
        parent::setUp();

        // Ejecutar seeders para tener usuarios y asociaciones disponibles
        $this->seed(\Database\Seeders\UserAdminSeeder::class);

        // Obtener usuario admin para autenticación
        $this->adminUser = User::where('username', 'andres.montes')->first();

        // Crear municipalidad para la relación
        $this->municipalidad = Municipalidad::factory()->create();

        // Crear asociación de ejemplo
        Asociacion::factory()->create([
            'municipalidad_id' => $this->municipalidad->id,
            'nombre' => 'Asociacion Existente'
        ]);
    }

    /** @test */
    public function puede_listar_asociacion_existente_y_agregarle_una_imagen()
    {
        // 1️⃣ Obtenemos la asociación desde la base de datos
        $asociacion = Asociacion::where('nombre', 'Asociacion Existente')->first();
        $this->assertNotNull($asociacion, 'La asociación debería existir en la base de datos.');

        // 2️⃣ Consultamos la asociación (GET)
        $responseGet = $this->actingAs($this->adminUser, 'api')
            ->getJson("/asociacion/{$asociacion->id}");

        $responseGet->assertStatus(200)
            ->assertJsonFragment(['nombre' => 'Asociacion Existente']);

        // Creamos el payload de la nueva imagen
        $data = [
            'asociacion_id' => $asociacion->id,
            'url_image' => 'http://imagenprueba.com/img1.jpg',
            'estado' => true,
            'codigo' => 'IMG999',
        ];

        // Enviamos la petición POST a /img-asociacion
        $responsePost = $this->actingAs($this->adminUser, 'api')
            ->postJson('/img-asociacion', $data);

        // Verificamos la respuesta
        $responsePost->assertStatus(201)
            ->assertJsonFragment([
                'url_image' => 'http://imagenprueba.com/img1.jpg',
                'codigo' => 'IMG999',
                'asociacion_id' => $asociacion->id,
            ]);

        // Confirmamos que la imagen existe en la base de datos
        $this->assertDatabaseHas('img_asociacions', [
            'codigo' => 'IMG999',
            'asociacion_id' => $asociacion->id
        ]);
    }
}
