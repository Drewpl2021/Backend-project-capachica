<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserAdminSeeder extends Seeder
{
    public function run()
    {
        // Crear permisos con guard "api"
        $permisoVerAsociacion = Permission::firstOrCreate(
            ['name' => 'view asociacion', 'guard_name' => 'api']
        );

        // Crear roles con guard "api"
        $adminRole = Role::firstOrCreate(
            ['name' => 'admin', 'guard_name' => 'api']
        );

        $usuarioRole = Role::firstOrCreate(
            ['name' => 'usuario', 'guard_name' => 'api']
        );

        $adminFamiliaRole = Role::firstOrCreate(
            ['name' => 'admin_familia', 'guard_name' => 'api']
        );

        // Crear usuario ADMIN
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Administrador',
                'last_name' => 'Sistema',
                'code' => 'ADM001',
                'imagen_url' => 'ADM001.jpg',
                'username' => 'admin',
                'password' => Hash::make('admin123'),
            ]
        );

        // Asignar rol y permisos al admin
        $admin->assignRole($adminRole);
        $admin->givePermissionTo($permisoVerAsociacion);

        // Crear usuario Andres Montes
        $andres = User::firstOrCreate(
            ['email' => 'andres.montes@example.com'],
            [
                'name' => 'Andres',
                'last_name' => 'Montes',
                'code' => 'USR001',
                'imagen_url' => 'USR001.jpg',
                'username' => 'andres.montes',
                'password' => Hash::make('12345'),
            ]
        );

        // Asignar rol y permisos a Andres Montes
        $andres->assignRole($adminRole);
        $andres->givePermissionTo($permisoVerAsociacion);

        // Crear usuario Marleny Torres
        $usuario = User::firstOrCreate(
            ['email' => 'usuario@example.com'],
            [
                'name' => 'Marleny',
                'last_name' => 'Torres',
                'code' => 'UM001',
                'imagen_url' => 'asdasd.jpg',
                'username' => 'marleny.torres',
                'password' => Hash::make('12345'),
            ]
        );

        // Asignar rol a Marleny Torres
        $usuario->assignRole($usuarioRole);

        // Crear usuario con rol admin_familia
        $familiaUsuario = User::firstOrCreate(
            ['email' => 'nuevo.familia@example.com'],
            [
                'name' => 'Nuevo',
                'last_name' => 'Familia',
                'code' => 'FAM002',
                'imagen_url' => 'FAM002.jpg',
                'username' => 'admin.familia',
                'password' => Hash::make('12345'),
            ]
        );

        // Asignar rol a Nuevo Familia
        $familiaUsuario->assignRole($adminFamiliaRole);
    }
}
