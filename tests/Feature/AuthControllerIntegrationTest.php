<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use GuzzleHttp\Psr7\UploadedFile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AuthControllerIntegrationTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    protected $user;
    protected $token;

    public function setUp(): void
    {
        parent::setUp();

        // Crea un usuario normal y un admin con roles para pruebas
        $this->artisan('db:seed', ['--class' => 'Database\\Seeders\\UserAdminSeeder']);
        $this->user = User::factory()->create([
            'username' => 'testuser',
            'email' => 'testuser@example.com',
            'password' => bcrypt('secret123'),
        ]);
        $role = Role::firstOrCreate(['name' => 'usuario']);
        $this->user->assignRole($role);
        $this->token = auth('api')->login($this->user);
    }

    private function headers($token = null)
    {
        return [
            'Authorization' => 'Bearer ' . ($token ?? $this->token),
            'Accept' => 'application/json',
        ];
    }

    public function test_register_creates_user_and_returns_token()
    {
        $unique = uniqid();
        $data = [
            'name' => 'Nuevo' . $unique,
            'last_name' => 'Usuario' . $unique,
            'username' => 'nuevo_usuario_' . $unique,
            'email' => 'nuevo' . $unique . '@example.com',
            'password' => 'password123',
        ];

        $response = $this->postJson('/register', $data);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'token',
                    'user' => ['id', 'username', 'email'],
                    'roles',
                ]
            ]);
        $this->assertDatabaseHas('users', ['username' => 'nuevo_usuario_' . $unique]);
    }

    /** @test */
    public function test_login_with_email_and_password_returns_token()
    {
        $response = $this->postJson('/login', [
            'email' => $this->user->email,
            'password' => 'secret123',
        ]);
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'token',
                    'expires_at',
                    'username',
                    'roles',
                    'permissions'
                ]
            ]);
    }

    /** @test */
    public function test_login_with_username_and_password_returns_token()
    {
        $response = $this->postJson('/login', [
            'username' => $this->user->username,
            'password' => 'secret123',
        ]);
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'token',
                    'expires_at',
                    'username',
                    'roles',
                    'permissions'
                ]
            ]);
    }

    /** @test */
    public function test_get_current_user_returns_authenticated_user()
    {
        $response = $this->getJson('/current-user', $this->headers());
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'token',
                    'username',
                    'roles',
                    'permissions'
                ]
            ]);
    }

    /** @test */
    public function test_perfil_returns_user_profile()
    {
        $response = $this->getJson('/perfil', $this->headers());
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'username',
                    'email'
                ]
            ]);
    }

    /** @test */
    public function test_logout_invalidates_token()
    {
        $response = $this->postJson('/logout', [], $this->headers());
        $response->assertStatus(200);
    }

    /** @test */
    public function test_update_profile_updates_user_data()
    {
        $data = [
            'name' => 'NuevoNombre',
            'last_name' => 'NuevoApellido',
            'username' => 'testuser', // mismo username, debe permitir
            'email' => 'testuser@example.com', // mismo email
            'imagen_url' => 'https://example.com/photo.jpg',
        ];
        $response = $this->putJson('/update-profile', $data, $this->headers());
        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'NuevoNombre']);
        $this->assertDatabaseHas('users', ['name' => 'NuevoNombre']);
    }

    /** @test */
    public function test_update_profile_can_change_password_and_returns_new_token()
    {
        $data = [
            'current_password' => 'secret123',
            'new_password' => 'nueva12345',
            'confirm_password' => 'nueva12345',
        ];
        $response = $this->putJson('/update-profile', $data, $this->headers());
        $response->assertStatus(200)
            ->assertJsonStructure(['data' => ['token', 'expires_at', 'user']]);
        // Verifica que el nuevo password funcione
        $login = $this->postJson('/login', [
            'username' => $this->user->username,
            'password' => 'nueva12345',
        ]);
        $login->assertStatus(200);
    }
}
