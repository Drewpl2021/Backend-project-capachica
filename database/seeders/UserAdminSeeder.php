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
                'username' => 'admin_familia',
                'password' => Hash::make('familia123'),
            ]
        );

        // Asignar rol admin_familia
        $adminFamRole = Role::where('name', 'admin_familia')->first();
        if ($adminFamRole && !$adminFam->hasRole('admin_familia')) {
            $adminFam->assignRole($adminFamRole);
        }
    }
}
