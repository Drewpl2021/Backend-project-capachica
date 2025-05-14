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

        // Crear usuario ADMIN_FAMILIA si no existe
        $adminFam = User::firstOrCreate(
            ['email' => 'familia@example.com'],
            [
                'name' => 'Admin',
                'last_name' => 'Familia',
                'code' => 'FAM001',
                'imagen_url' => 'FAM001.jpg',
                'username' => 'admin_familia',
                'password' => Hash::make('familia123'),
            ]
        );

        // Asignar rol admin_familia
        $adminFamRole = Role::where('name', 'admin_familia')->first();
        if ($adminFamRole && !$adminFam->hasRole('admin_familia')) {
            $adminFam->assignRole($adminFamRole);
        }

        // Crear usuario ADMIN_FAMILIA si no existe
        $adminFam = User::firstOrCreate(
            ['email' => 'usuario@example.com'],
            [
                'name' => 'Usuario',
                'last_name' => 'Usuario',
                'code' => 'US001',
                'imagen_url' => 'US001.jpg',
                'username' => 'usuario',
                'password' => Hash::make('12345'),
            ]
        );

        // Asignar rol admin_familia
        $adminFamRole = Role::where('name', 'usuario')->first();
        if ($adminFamRole && !$adminFam->hasRole('usuario')) {
            $adminFam->assignRole($adminFamRole);
        }
    }
}
