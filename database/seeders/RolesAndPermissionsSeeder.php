<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Crear permisos si no existen
        $permisos = [
            'ver_usuarios',
            'crear_usuarios',
            'editar_usuarios',
            'eliminar_usuarios',
            'editar_perfil',
        ];

        // Crear los permisos para el guard `api`
        foreach ($permisos as $permiso) {
            Permission::firstOrCreate(['name' => $permiso, 'guard_name' => 'api']);
        }

        // Crear roles si no existen
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'api']);
        $admin->givePermissionTo(Permission::all());

        $adminFam = Role::firstOrCreate(['name' => 'admin_familia', 'guard_name' => 'api']);
        $adminFam->givePermissionTo([
            'ver_usuarios',
            'editar_usuarios',
            'editar_perfil'
        ]);

        $usuario = Role::firstOrCreate(['name' => 'usuario', 'guard_name' => 'api']);
        $usuario->givePermissionTo([
            'editar_perfil'
        ]);
    }
}
