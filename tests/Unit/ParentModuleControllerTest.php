<?php

namespace Tests\Feature;

use App\Models\ParentModule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ParentModuleControllerTest extends TestCase
{
    use RefreshDatabase;

    // Crear un módulo padre para usar en las pruebas
    public function setUp(): void
    {
        parent::setUp();

        ParentModule::create([
            'title' => 'Parent Module 1',
            'code' => 'PM001',
            'subtitle' => 'Subtitle of Parent Module',
            'type' => 'parent',
            'icon' => 'icon1',
            'status' => true,
            'moduleOrder' => 1,
            'link' => 'http://parentmodule1.com',
        ]);
    }

    /** @test */
    public function it_can_create_a_parent_module()
    {
        // Realizar la solicitud POST
        $response = $this->postJson('/parent-module/test', [
            'title' => 'New Parent Module',
            'code' => 'PM002',
            'subtitle' => 'New Subtitle',
            'type' => 'parent',
            'icon' => 'icon2',
            'status' => true,
            'moduleOrder' => 2,
            'link' => 'http://newparentmodule.com',
        ]);

        // Verificar que el código de estado sea 200
        $response->assertStatus(200)
            ->assertJsonStructure([
                'id', 'title', 'subtitle', 'type', 'code', 'icon', 'status', 'moduleOrder', 'link'
            ]);
    }




    /** @test */
    public function it_can_get_single_parent_module()
    {
        $parentModule = ParentModule::first();

        $response = $this->getJson("/parent-module/test/{$parentModule->id}");

        $response->assertStatus(200)
            ->assertJson([
                'id' => $parentModule->id,
                'title' => $parentModule->title,
                'code' => $parentModule->code,
                'subtitle' => $parentModule->subtitle,
                'type' => $parentModule->type,
                'icon' => $parentModule->icon,
                'status' => $parentModule->status,
                'moduleOrder' => $parentModule->moduleOrder,
                'link' => $parentModule->link,
            ]);
    }


    /** @test */
    public function it_can_update_parent_module()
    {
        $parentModule = ParentModule::first();

        $response = $this->putJson("/parent-module/test/{$parentModule->id}", [
            'title' => 'Updated Parent Module Title',
            'subtitle' => 'Updated Subtitle',
            'type' => 'parent',
            'code' => 'PM003',
            'icon' => 'icon3',
            'status' => true,
            'moduleOrder' => 3,
            'link' => 'http://updatedparentmodule.com',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'id' => $parentModule->id,  // Esto asegura que estamos actualizando el mismo módulo
                'title' => 'Updated Parent Module Title',
                'subtitle' => 'Updated Subtitle',
                'type' => 'parent',
                'code' => 'PM003',
                'icon' => 'icon3',
                'status' => true,
                'moduleOrder' => 3,
                'link' => 'http://updatedparentmodule.com',
            ]);
    }


    /** @test */
    public function it_can_force_delete_parent_module()
    {
        // Crear un nuevo "ParentModule"
        $parentModule = ParentModule::create([
            'title' => 'New Parent Module',
            'code' => 'PM003',
            'subtitle' => 'New Subtitle',
            'type' => 'parent',
            'icon' => 'icon2',
            'status' => true,
            'moduleOrder' => 2,
            'link' => 'http://newparentmodule.com',
        ]);

        // Eliminar físicamente el registro usando forceDelete
        $parentModule->forceDelete();

        // Verificar que el registro fue completamente eliminado (ya no está en la base de datos)
        $this->assertDatabaseMissing('parent_modules', [
            'id' => $parentModule->id
        ]);
    }


}
