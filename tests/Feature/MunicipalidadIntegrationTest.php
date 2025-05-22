<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Municipalidad;
use App\Models\User;

class MunicipalidadIntegrationTest extends TestCase
{
    protected $token;
    protected $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        // Seeder que crea un usuario admin (con rol)
        $this->seed(\Database\Seeders\UserAdminSeeder::class);

        // Buscar usuario admin creado
        $this->adminUser = User::where('username', 'andres.montes')->first();

        // Generar token JWT para el usuario admin
        $this->token = auth('api')->login($this->adminUser);
    }

    // Método para incluir headers con token JWT en las peticiones
    private function headers()
    {
        return [
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json',
        ];
    }

    public function test_index_retorna_paginacion_y_datos()
    {
        Municipalidad::factory()->count(15)->create();

        // Ruta pública, no requiere token
        $response = $this->getJson('/municipalidad?size=10');

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

    public function test_store_crea_municipalidad_en_bd()
    {
        $data = [
            'distrito' => 'Distrito Test',
            'provincia' => 'Provincia Test',
            'region' => 'Region Test',
            'codigo' => 'COD123',
        ];

        // POST con token JWT
        $response = $this->postJson('/municipalidad/crear', $data, $this->headers());

        // Si tu controlador devuelve 200, verifica 200; si 201, cambia a 201
        $response->assertStatus(200)
            ->assertJsonFragment(['distrito' => 'Distrito Test']);

        // Verificar que se guardó en la tabla correcta
        $this->assertDatabaseHas('municipalidads', ['codigo' => 'COD123']);
    }

    public function test_show_retorna_municipalidad_por_id()
    {
        $municipalidad = Municipalidad::factory()->create();

        $response = $this->getJson("/municipalidad/{$municipalidad->id}", $this->headers());

        $response->assertStatus(200)
            ->assertJson([
                'id' => $municipalidad->id,
                'distrito' => $municipalidad->distrito,
            ]);
    }

    public function test_update_modifica_municipalidad_en_bd()
    {
        $municipalidad = Municipalidad::factory()->create();

        $data = [
            'distrito' => 'Distrito Actualizado',
            'provincia' => 'Provincia Actualizada',
            'region' => 'Region Actualizada',
            'codigo' => 'COD999',
        ];

        $response = $this->putJson("/municipalidad/{$municipalidad->id}", $data, $this->headers());

        $response->assertStatus(200)
            ->assertJsonFragment(['distrito' => 'Distrito Actualizado']);

        $this->assertDatabaseHas('municipalidads', ['codigo' => 'COD999']);
    }

    public function test_destroy_elimina_municipalidad()
    {
        $municipalidad = Municipalidad::factory()->create();

        $response = $this->deleteJson("/municipalidad/{$municipalidad->id}", [], $this->headers());

        $response->assertStatus(200);

        $this->assertSoftDeleted('municipalidads', ['id' => $municipalidad->id]);
    }
}
