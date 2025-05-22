<?php

namespace Tests\Feature;

use App\Models\Municipalidad;
use Tests\TestCase;
use App\Models\Municipalidad_Descripcion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MunicipalidadDescripcionIntegrationTest extends TestCase
{
    protected $municipalidad;

    protected $token;
    protected $adminUser;


    public function setUp(): void
    {
        parent::setUp();

        // Ejecutar seeders necesarios
        $this->seed(\Database\Seeders\UserAdminSeeder::class);

        // Usuario admin para autenticación
        $this->adminUser = User::where('username', 'andres.montes')->first();

        $this->token = auth('api')->login($this->adminUser);

        // Crear una municipalidad para asociar descripción
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
        Municipalidad_Descripcion::factory()->count(15)->create([
            'municipalidad_id' => $this->municipalidad->id,
        ]);

        $response = $this->getJson('/municipalidad/descripcion?size=10');

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
    public function test_store_crea_descripcion_con_municipalidad_id()
    {
        $data = [
            'logo' => 'logo-test.png',
            'direccion' => 'Av. Test 123',
            'descripcion' => 'Descripción de prueba',
            'ruc' => '12345678901',
            'correo' => 'correo@test.com',
            'nombre_alcalde' => 'Juan Pérez',
            'anio_gestion' => '2023',
        ];

        $response = $this->postJson(
            "/municipalidad/descripcion/{$this->municipalidad->id}",
            $data,
            $this->headers()
        );

        $response->assertStatus(201)
            ->assertJsonFragment([
                'status' => true,
                'message' => 'Descripción de municipalidad creada exitosamente',
            ]);

        $this->assertDatabaseHas('municipalidad__descripcions', [
            'municipalidad_id' => $this->municipalidad->id,
            'ruc' => '12345678901',
        ]);
    }

    /** @test */
    public function test_show_retorna_descripcion_por_id()
    {
        $descripcion = Municipalidad_Descripcion::factory()->create([
            'municipalidad_id' => $this->municipalidad->id,
        ]);

        $response = $this->getJson(
            "/municipalidad/descripcion/{$descripcion->id}",
            $this->headers()
        );

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'Descripción de municipio encontrada',
                'data' => [
                    'id' => $descripcion->id,
                    'municipalidad_id' => $this->municipalidad->id,
                ],
            ]);
    }

    /** @test */
    public function test_update_modifica_descripcion_por_id()
    {
        $descripcion = Municipalidad_Descripcion::factory()->create([
            'municipalidad_id' => $this->municipalidad->id,
        ]);

        $data = [
            'municipalidad_id' => $this->municipalidad->id,
            'logo' => 'logo-actualizado.png',
            'direccion' => 'Av. Actualizada 456',
            'descripcion' => 'Descripción actualizada',
            'ruc' => '98765432109',
            'correo' => 'actualizado@test.com',
            'nombre_alcalde' => 'María López',
            'anio_gestion' => '2024',
        ];

        $response = $this->putJson(
            "/municipalidad/descripcion/{$descripcion->id}",
            $data,
            $this->headers()
        );

        $response->assertStatus(200)
            ->assertJsonFragment([
                'status' => true,
                'message' => 'Descripción de municipio actualizada exitosamente',
            ]);

        $this->assertDatabaseHas('municipalidad__descripcions', [
            'id' => $descripcion->id,
            'ruc' => '98765432109',
        ]);
    }

    /** @test */
    public function test_destroy_elimina_descripcion_por_id()
    {
        $descripcion = Municipalidad_Descripcion::factory()->create([
            'municipalidad_id' => $this->municipalidad->id,
        ]);

        $response = $this->deleteJson(
            "/municipalidad/descripcion/{$descripcion->id}",
            [],
            $this->headers()
        );

        $response->assertStatus(200)
            ->assertJsonFragment([
                'status' => true,
                'message' => 'Descripción de municipio eliminada exitosamente',
            ]);

        $this->assertSoftDeleted('municipalidad__descripcions', [
            'id' => $descripcion->id,
        ]);
    }
}
