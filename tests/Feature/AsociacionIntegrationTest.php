<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Asociacion;
use App\Models\img_asociacion;
use App\Models\ImgAsociacion;
use App\Models\User;
use App\Models\Municipalidad;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AsociacionIntegrationTest extends TestCase
{

    protected $token;
    protected $adminUser;
    protected $municipalidad;

    public function setUp(): void
    {
        parent::setUp();

        // Seeder para crear usuario admin con rol
        $this->seed(\Database\Seeders\UserAdminSeeder::class);

        $this->adminUser = User::where('username', 'andres.montes')->first();

        $this->token = auth('api')->login($this->adminUser);

        // Crear municipalidad para relación
        $this->municipalidad = Municipalidad::factory()->create();
    }

    private function headers()
    {
        return [
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json',
        ];
    }

    /** @test */
    public function test_index_retorna_paginacion_y_datos()
    {
        Asociacion::factory()->count(15)->create(['municipalidad_id' => $this->municipalidad->id]);

        $response = $this->getJson('/asociaciones?size=10');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'content',
                'totalElements',
                'currentPage',
                'totalPages',
                'perPage',
            ]);

        $this->assertCount(10, $response->json('content'));
    }

    /** @test */
    public function test_store_crea_asociacion_con_imagenes()
    {
        $data = [
            'municipalidad_id' => $this->municipalidad->id,
            'nombre' => 'Asociacion Test',
            'descripcion' => 'Descripcion Test',
            'lugar' => 'Lugar Test',
            'url' => 'http://testurl.com',
            'estado' => true,
            'imagenes' => [
                [
                    'url_image' => 'http://testimage.com/img1.jpg',
                    'estado' => true,
                    'codigo' => 'IMG001',
                    'description' => 'Imagen 1',
                ],
                [
                    'url_image' => 'http://testimage.com/img2.jpg',
                    'estado' => true,
                    'codigo' => 'IMG002',
                    'description' => 'Imagen 2',
                ],
            ],
        ];

        $response = $this->postJson('/asociacion', $data, $this->headers());

        $response->assertStatus(201)
            ->assertJsonFragment(['nombre' => 'Asociacion Test']);

        $this->assertDatabaseHas('asociacions', ['nombre' => 'Asociacion Test']);
        $this->assertDatabaseHas('img_asociacions', ['codigo' => 'IMG001']);
        $this->assertDatabaseHas('img_asociacions', ['codigo' => 'IMG002']);
    }

    /** @test */
    public function test_show_retorna_asociacion_con_imagenes()
    {
        $asociacion = Asociacion::factory()->create(['municipalidad_id' => $this->municipalidad->id]);
        $img = img_asociacion::factory()->create(['asociacion_id' => $asociacion->id]);

        $response = $this->getJson("/asociacion/{$asociacion->id}", $this->headers());

        $response->assertStatus(200)
            ->assertJsonFragment(['id' => $asociacion->id])
            ->assertJsonFragment(['id' => $img->id]);
    }

    /** @test */
    public function test_update_modifica_asociacion_e_imagenes()
    {
        $asociacion = Asociacion::factory()->create(['municipalidad_id' => $this->municipalidad->id]);
        $img = img_asociacion::factory()->create(['asociacion_id' => $asociacion->id]);

        $data = [
            'municipalidad_id' => $this->municipalidad->id,
            'nombre' => 'Asociacion Actualizada',
            'descripcion' => 'Descripcion actualizada',
            'lugar' => 'Lugar actualizado',
            'url' => 'http://updatedurl.com',
            'estado' => false,
            'imagenes' => [
                [
                    'id' => $img->id,
                    'url_image' => 'http://updatedimage.com/img-updated.jpg',
                    'estado' => true,
                    'codigo' => 'IMG001-UPD',
                    'description' => 'Imagen actualizada',
                ],
                [
                    'url_image' => 'http://newimage.com/img-new.jpg',
                    'estado' => true,
                    'codigo' => 'IMG003',
                    'description' => 'Nueva imagen',
                ],
            ],
        ];

        $response = $this->putJson("/asociacion/{$asociacion->id}", $data, $this->headers());

        $response->assertStatus(200)
            ->assertJsonFragment(['nombre' => 'Asociacion Actualizada']);

        $this->assertDatabaseHas('asociacions', ['nombre' => 'Asociacion Actualizada']);
        $this->assertDatabaseHas('img_asociacions', ['codigo' => 'IMG001-UPD']);
        $this->assertDatabaseHas('img_asociacions', ['codigo' => 'IMG003']);
    }

    /** @test */
    public function test_destroy_elimina_asociacion()
    {
        $asociacion = Asociacion::factory()->create(['municipalidad_id' => $this->municipalidad->id]);

        $response = $this->deleteJson("/asociacion/{$asociacion->id}", [], $this->headers());

        $response->assertStatus(200)
            ->assertJsonFragment(['message' => 'Asociación eliminada exitosamente']);

        $this->assertSoftDeleted('asociacions', ['id' => $asociacion->id]);
    }

    /** @test */
    public function test_showWithEmprendedoresYServicios_retorna_info()
    {
        $asociacion = Asociacion::factory()->create(['municipalidad_id' => $this->municipalidad->id]);

        $response = $this->getJson("/asociacion/emprendedores-servicios/{$asociacion->id}", $this->headers());

        $response->assertStatus(200)
            ->assertJsonStructure([
                'asociacion' => ['id', 'nombre', 'descripcion', 'url', 'lugar', 'estado', 'createdAt', 'updatedAt', 'deletedAt'],
                'emprendedores' => [],
                'totalElements',
                'currentPage',
                'totalPages',
            ]);
    }
}
