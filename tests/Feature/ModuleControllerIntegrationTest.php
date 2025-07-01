<?php

namespace Tests\Feature;

use App\Models\Module;
use App\Models\ParentModule;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ModuleControllerIntegrationTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    protected $token;
    protected $adminUser;
    protected $parentModule;

    public function setUp(): void
    {
        parent::setUp();

        // Seed roles and permissions if needed
        $this->artisan('db:seed', ['--class' => 'Database\\Seeders\\UserAdminSeeder']);

        $this->adminUser = User::where('username', 'andres.montes')->first();

        // Asignar rol necesario para pasar el middleware
        $role = Role::firstOrCreate(['name' => 'admin']);
        $this->adminUser->assignRole($role);

        $this->token = auth('api')->login($this->adminUser);

        $this->parentModule = ParentModule::factory()->create();
    }

    private function headers()
    {
        return [
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json',
        ];
    }

    /** @test */
    public function test_index_modules_returns_paginated_data()
    {
        Module::factory()->count(12)->create(['parent_module_id' => $this->parentModule->id]);

        $response = $this->getJson('/module?size=10', $this->headers());

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
    public function test_store_creates_module()
    {
        $data = [
            'title' => 'Modulo Test',
            'subtitle' => 'Subtitulo Test',
            'type' => 'Tipo Test',
            'code' => 'MOD001',
            'icon' => 'icon-test',
            'status' => true,
            'moduleOrder' => 1,
            'link' => '/test-link',
            'parentModuleId' => $this->parentModule->id,
        ];

        $response = $this->postJson('/module', $data, $this->headers());

        $response->assertStatus(200)
            ->assertJsonFragment(['title' => 'Modulo Test']);

        $this->assertDatabaseHas('modules', ['title' => 'Modulo Test']);
    }

    /** @test */
    public function test_show_returns_module_with_parent()
    {
        $module = Module::factory()->create(['parent_module_id' => $this->parentModule->id]);

        $response = $this->getJson("/module/{$module->id}", $this->headers());

        $response->assertStatus(200)
            ->assertJsonFragment(['id' => $module->id])
            ->assertJsonFragment(['id' => $this->parentModule->id]);
    }

    /** @test */
    public function test_update_modifies_module()
    {
        $module = Module::factory()->create(['parent_module_id' => $this->parentModule->id]);

        $data = [
            'title' => 'Modulo Actualizado',
            'subtitle' => 'Subtitulo Actualizado',
            'type' => 'Tipo Actualizado',
            'code' => 'MOD002',
            'icon' => 'icon-actualizado',
            'status' => false,
            'moduleOrder' => 2,
            'link' => '/actualizado',
            'parentModuleId' => $this->parentModule->id,
        ];

        $response = $this->putJson("/module/{$module->id}", $data, $this->headers());

        $response->assertStatus(200)
            ->assertJsonFragment(['title' => 'Modulo Actualizado']);

        $this->assertDatabaseHas('modules', ['title' => 'Modulo Actualizado']);
    }

    /** @test */
    public function test_destroy_deletes_module()
    {
        $module = Module::factory()->create(['parent_module_id' => $this->parentModule->id]);

        $response = $this->deleteJson("/module/{$module->id}", [], $this->headers());

        $response->assertStatus(200);
        $this->assertSoftDeleted('modules', ['id' => $module->id]);
    }

    /** @test */
    public function test_menu_returns_structure_for_authenticated_user()
    {
        // Crear un módulo hijo y asociar roles
        $module = Module::factory()->create(['parent_module_id' => $this->parentModule->id]);
        $role = Role::where('name', 'admin')->first();
        $module->roles()->attach($role);

        $response = $this->getJson('/module/menu', $this->headers());

        $response->assertStatus(200)
            ->assertJsonStructure([
                [
                    'id',
                    'title',
                    'subtitle',
                    'type',
                    'icon',
                    'link',
                    'moduleOrder',
                    'createdAt',
                    'updatedAt',
                    'deletedAt',
                    'children',
                ]
            ]);
    }
}
