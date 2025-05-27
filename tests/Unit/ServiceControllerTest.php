<?php

namespace Tests\Feature;

use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServiceControllerTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();

        // Crear un servicio base para usar en las pruebas
        Service::create([
            'name' => 'Servicio Base',
            'code' => 'SVC001',
            'description' => 'Descripción del servicio base',
            'category' => 'Categoria A',
            'status' => true,
        ]);
    }

    /** @test */
    public function it_can_create_a_service()
    {
        $response = $this->postJson('/service/test', [
            'name' => 'Nuevo Servicio',
            'code' => 'SVC002',
            'description' => 'Descripción del nuevo servicio',
            'category' => 'Categoria B',
            'status' => true,
        ]);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'name' => 'Nuevo Servicio',
                'code' => 'SVC002',
                'description' => 'Descripción del nuevo servicio',
                'category' => 'Categoria B',
                'status' => true,
            ]);

        $this->assertDatabaseHas('services', ['code' => 'SVC002']);
    }

    /** @test */
    public function it_can_show_a_single_service()
    {
        $service = Service::first();

        $response = $this->getJson("/service/test/{$service->id}");

        $response->assertStatus(200)
            ->assertJson([
                'id' => $service->id,
                'name' => $service->name,
                'code' => $service->code,
                'description' => $service->description,
                'category' => $service->category,
                'status' => $service->status,
            ]);
    }

    /** @test */
    public function it_returns_404_when_showing_nonexistent_service()
    {
        $response = $this->getJson('/service/test/999999');

        $response->assertStatus(404)
            ->assertJson(['error' => 'Servicio no encontrado']);
    }

    /** @test */
    public function it_can_update_a_service()
    {
        $service = Service::first();

        $response = $this->putJson("/service/test/{$service->id}", [
            'name' => 'Servicio Actualizado',
            'code' => 'SVC001',  // Mismo código para evitar conflicto unique
            'description' => 'Descripción actualizada',
            'category' => 'Categoria Actualizada',
            'status' => false,
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'name' => 'Servicio Actualizado',
                'code' => 'SVC001',
                'description' => 'Descripción actualizada',
                'category' => 'Categoria Actualizada',
                'status' => false,
            ]);

        $this->assertDatabaseHas('services', ['name' => 'Servicio Actualizado']);
    }

    /** @test */
    public function it_returns_404_when_updating_nonexistent_service()
    {
        $response = $this->putJson('/service/test/999999', [
            'name' => 'No Existe',
            'code' => 'NOEXIST',
        ]);

        $response->assertStatus(404)
            ->assertJson(['error' => 'Servicio no encontrado']);
    }

    /** @test */
    public function it_can_soft_delete_a_service()
    {
        $service = Service::first();

        $response = $this->deleteJson("/service/test/{$service->id}");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Servicio eliminado correctamente']);

        $this->assertSoftDeleted('services', ['id' => $service->id]);
    }

    /** @test */
    public function it_returns_404_when_deleting_nonexistent_service()
    {
        $response = $this->deleteJson('/service/test/999999');

        $response->assertStatus(404)
            ->assertJson(['error' => 'Servicio no encontrado']);
    }

    /** @test */
    public function it_validates_fields_when_creating_service()
    {
        $response = $this->postJson('/service/test', [
            'name' => '',
            'code' => '',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'code']);
    }

    /** @test */
    public function it_validates_fields_when_updating_service()
    {
        $service = Service::first();

        $response = $this->putJson("/service/test/{$service->id}", [
            'name' => '',
            'code' => '',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'code']);
    }
}
