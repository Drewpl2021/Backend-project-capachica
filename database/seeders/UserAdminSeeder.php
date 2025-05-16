<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserAdminSeeder extends Seeder
{
    public function run()
    {
        // Crear usuario ADMIN si no existe
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

        // Asignar rol admin
        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole && !$admin->hasRole('admin')) {
            $admin->assignRole($adminRole);
        }

        // Crear usuario Andres Montes si no existe
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

        // Asignar rol user
        $andresRole = Role::where('name', 'admin')->first();
        if ($andresRole && !$andres->hasRole('admin')) {
            $andres->assignRole($andresRole);
        }

        //Rol user
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

        // Asignar rol admin_familia
        $usuarios = Role::where('name', 'usuario')->first();
        if ($usuarios && !$usuario->hasRole('usuario')) {
            $usuario->assignRole($usuarios);
        }

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

        // Asignar rol admin_familia
        $adminFamiliaRole = Role::where('name', 'admin_familia')->first();
        if ($adminFamiliaRole && !$familiaUsuario->hasRole('admin_familia')) {
            $familiaUsuario->assignRole($adminFamiliaRole);
        }
    }
}
