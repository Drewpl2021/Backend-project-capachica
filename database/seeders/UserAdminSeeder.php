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
                'username' => 'Administrador',
                'password' => Hash::make('admin123'),
            ]
        );

        // Asignar rol admin
        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole && !$admin->hasRole('admin')) {
            $admin->assignRole($adminRole);
        }

        // Crear usuario ADMIN_FAMILIA si no existe
        $adminFam = User::firstOrCreate(
            ['email' => 'familia@example.com'],
            [
                'username' => 'Admin Familia',
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
