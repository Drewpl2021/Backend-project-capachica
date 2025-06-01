<?php

namespace Tests\Feature;

use App\Models\Municipalidad;
use App\Models\Slider_Muni;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SliderMuniIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected $token;
    protected $adminUser;
    protected $municipalidad;

    protected function setUp(): void
    {
        parent::setUp();

        // Ejecutar seeders para tener un usuario administrador
        $this->seed(\Database\Seeders\UserAdminSeeder::class);

        // Obtener usuario admin
        $this->adminUser = User::where('username', 'andres.montes')->first();

        // Generar token JWT
        $this->token = auth('api')->login($this->adminUser);

        // Crear municipalidad para la relación
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
    public function puede_listar_sliders()
    {
        Slider_Muni::factory()->count(3)->create([
            'municipalidad_id' => $this->municipalidad->id,
            'descripcion' => 'Texto corto',
        ]);

        $response = $this->getJson('/slider', $this->headers());

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    /** @test */
    public function puede_crear_un_slider()
    {
        $data = [
            'municipalidad_id' => $this->municipalidad->id,
            'titulo' => 'Slider de prueba',
            'descripcion' => 'Descripción de prueba',
            'url_images' => 'https://via.placeholder.com/400'
        ];

        $response = $this->postJson('/slider', $data, $this->headers());

        $response->assertStatus(201)
            ->assertJsonFragment(['titulo' => 'Slider de prueba']);

        $this->assertDatabaseHas('slider__munis', ['titulo' => 'Slider de prueba']);
    }

    /** @test */
    public function puede_mostrar_un_slider()
    {
        $slider = Slider_Muni::factory()->create([
            'municipalidad_id' => $this->municipalidad->id,
            'descripcion' => 'Texto corto',
        ]);

        $response = $this->getJson("/slider/{$slider->id}", $this->headers());

        $response->assertStatus(200)
            ->assertJsonFragment(['id' => $slider->id]);
    }

    /** @test */
    public function puede_actualizar_un_slider()
    {
        $slider = Slider_Muni::factory()->create([
            'municipalidad_id' => $this->municipalidad->id,
            'descripcion' => 'Texto corto',
        ]);

        $data = [
            'municipalidad_id' => $this->municipalidad->id,
            'titulo' => 'Slider actualizado',
            'descripcion' => 'Descripción actualizada',
            'url_images' => 'https://via.placeholder.com/400'
        ];

        $response = $this->putJson("/slider/{$slider->id}", $data, $this->headers());

        $response->assertStatus(200)
            ->assertJsonFragment(['message' => 'Slider actualizado exitosamente'])
            ->assertJsonFragment(['status' => true]);

        $this->assertDatabaseHas('slider__munis', ['titulo' => 'Slider actualizado']);
    }

    /** @test */
    public function puede_eliminar_un_slider()
    {
        $slider = Slider_Muni::factory()->create();

        $response = $this->deleteJson("/slider/{$slider->id}", [], $this->headers());

        $response->assertStatus(200)
            ->assertJsonFragment(['message' => 'Slider eliminado exitosamente'])
            ->assertJsonFragment(['status' => true]);

        $this->assertDatabaseMissing('slider__munis', ['id' => $slider->id]);
    }
}
