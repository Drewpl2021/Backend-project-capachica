<?php

namespace Tests\Feature;

use App\Models\asociacion;
use Tests\TestCase;
use App\Models\User;
use App\Models\Emprendedor;
use App\Models\Service;
use Illuminate\Support\Str;

class EmprendedorIntegrationTest extends TestCase
{

    protected $token;
    protected $adminUser;
    protected $asociacion;

    public function setUp(): void
    {
        parent::setUp();

        // Carga seeder que crea usuario admin con rol
        $this->seed(\Database\Seeders\UserAdminSeeder::class);

        $this->adminUser = User::where('username', 'andres.montes')->first();
        $this->asociacion = asociacion::factory()->create();

        $this->token = auth('api')->login($this->adminUser);
    }

    private function headers()
    {
        return [
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json',
        ];
    }

    public function test_index_emprendedores()
    {
        Emprendedor::factory()->count(15)->create();

        $response = $this->getJson('/emprendedor?size=10', $this->headers());

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

    public function testStoreEmprendedor()
    {
        $data = [
            'razon_social' => 'Turismo Andino S.A.C.',
            'asociacion_id' => $this->asociacion->id,
            'address' => 'Calle Falsa 123',
            'code' => 'EMP-TEST',
            'ruc' => '20231234567',
            'description' => 'Una empresa de turismo.',
            'lugar' => 'Capachica',
            'img_logo' => 'ruta/logo.png',
            'name_family' => 'Familia Quispe',
            'status' => true,
        ];

        $response = $this->postJson('/emprendedor', $data, $this->headers());

        $response->assertStatus(201)
            ->assertJsonPath('data.razon_social', 'Turismo Andino S.A.C.')
            ->assertJson(['success' => true]);
    }

    public function test_show_emprendedor()
    {
        $emprendedor = Emprendedor::factory()->create();

        $response = $this->getJson("/emprendedor/{$emprendedor->id}", $this->headers());

        $response->assertStatus(200)
            ->assertJsonPath('data.id', $emprendedor->id)
            ->assertJson(['success' => true]);
    }

    public function test_update_emprendedor()
    {
        $emprendedor = Emprendedor::factory()->create();

        $data = [
            'asociacion_id' => $this->asociacion->id,
            'razon_social' => 'Empresa Actualizada S.A.',
            'address' => 'Nueva dirección',
        ];

        $response = $this->putJson("/emprendedor/{$emprendedor->id}", $data, $this->headers());

        $response->assertStatus(200)
            ->assertJsonPath('data.razon_social', $data['razon_social'])
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('emprendedors', ['razon_social' => $data['razon_social']]);
    }

    public function test_destroy_emprendedor()
    {
        $emprendedor = Emprendedor::factory()->create();

        $response = $this->deleteJson("/emprendedor/{$emprendedor->id}", [], $this->headers());

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertSoftDeleted('emprendedors', ['id' => $emprendedor->id]);
    }

    public function test_asignar_servicios_emprendedor()
    {
        $emprendedor = Emprendedor::factory()->create();
        $service1 = Service::factory()->create();
        $service2 = Service::factory()->create();

        $data = [
            'service_id' => [$service1->id, $service2->id],
            'cantidad' => [5, 10],
            'costo' => [150.50, 300.00],
            'costo_unidad' => [30.10, 30.00],
            'name' => ['Servicio A', 'Servicio B'],
            'description' => ['Descripcion A', 'Descripcion B'],
        ];

        $response = $this->postJson("/emprendedor/services/{$emprendedor->id}", $data, $this->headers());

        $response->assertStatus(200)
            ->assertJsonFragment(['message' => 'Servicios asignados exitosamente'])
            ->assertJsonCount(2, 'assigned_services');
    }
}
