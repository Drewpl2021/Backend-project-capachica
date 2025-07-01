<?php

namespace Tests\Feature;

use App\Models\ParentModule;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class ParentModuleControllerIntegrationTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

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

    /** @test */
    public function test_list_paginate_returns_paginated_data()
    {
        ParentModule::factory()->count(15)->create();

        $response = $this->getJson('/parent-module?page=0&size=10', $this->headers());

        $response->assertStatus(200)
            ->assertJsonStructure([
                'content',
                'totalElements',
                'currentPage',
                'totalPages',
            ]);

        $this->assertCount(10, $response->json('content'));
    }

    /** @test */
    public function test_list_returns_all_parent_modules()
    {
        ParentModule::factory()->count(3)->create();

        $response = $this->getJson('/parent-module/list', $this->headers());

        $response->assertStatus(200)
            ->assertJsonStructure([
                [
                    'id',
                    'title',
                    'code',
                    'subtitle',
                    'type',
                    'icon',
                    'status',
                    'moduleOrder',
                    'link',
                    'createdAt',
                    'updatedAt',
                    'deletedAt'
                ]
            ]);
    }

    /** @test */
    public function test_listar_returns_all_parent_modules()
    {
        ParentModule::factory()->count(2)->create();

        $response = $this->getJson('/parent-module/listar', $this->headers());

        $response->assertStatus(200)
            ->assertJsonStructure([
                [
                    'id',
                    'title',
                    'code',
                    'subtitle',
                    'type',
                    'icon',
                    'status',
                    'moduleOrder',
                    'link',
                    'createdAt',
                    'updatedAt',
                    'deletedAt'
                ]
            ]);
    }

    /** @test */
    public function test_list_detail_module_list_returns_parent_with_modules()
    {
        $parent = ParentModule::factory()->create();
        // Si tienes relación con modules, puedes crearlos aquí con factory.

        $response = $this->getJson('/parent-module/list-detail-module-list', $this->headers());

        $response->assertStatus(200)
            ->assertJsonFragment(['id' => $parent->id]);
    }

    /** @test */
    public function test_store_creates_parent_module()
    {
        $data = [
            'title' => 'Padre Test',
            'code' => 'PA001',
            'subtitle' => 'Padre Sub',
            'type' => 'Tipo',
            'icon' => 'icon-test',
            'status' => true,
            'moduleOrder' => 1,
            'link' => '/padre-link',
        ];

        $response = $this->postJson('/parent-module', $data, $this->headers());

        $response->assertStatus(200)
            ->assertJsonFragment(['title' => 'Padre Test']);

        $this->assertDatabaseHas('parent_modules', ['title' => 'Padre Test']);
    }

    /** @test */
    public function test_show_returns_parent_module()
    {
        $parent = ParentModule::factory()->create();

        $response = $this->getJson("/parent-module/{$parent->id}", $this->headers());

        $response->assertStatus(200)
            ->assertJsonFragment(['id' => $parent->id]);
    }

    /** @test */
    public function test_update_modifies_parent_module()
    {
        $parent = ParentModule::factory()->create();

        $data = [
            'title' => 'Padre Actualizado',
            'code' => 'PA002',
            'subtitle' => 'Padre Sub Actualizado',
            'type' => 'Nuevo Tipo',
            'icon' => 'new-icon',
            'status' => false,
            'moduleOrder' => 3,
            'link' => '/nuevo-link',
        ];

        $response = $this->putJson("/parent-module/{$parent->id}", $data, $this->headers());

        $response->assertStatus(200)
            ->assertJsonFragment(['title' => 'Padre Actualizado']);

        $this->assertDatabaseHas('parent_modules', ['title' => 'Padre Actualizado']);
    }

    /** @test */
    public function test_destroy_soft_deletes_parent_module()
    {
        $parent = ParentModule::factory()->create();

        $response = $this->deleteJson("/parent-module/{$parent->id}", [], $this->headers());

        $response->assertStatus(200);
        $this->assertSoftDeleted('parent_modules', ['id' => $parent->id]);
    }
}
