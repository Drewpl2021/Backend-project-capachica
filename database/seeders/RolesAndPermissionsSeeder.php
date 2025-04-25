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
            'editar_municipalidad',
            'eliminar_municipalidad',
            'crear_municipalidad',
            'visualizar_municipalidad',
            'editar_roles'
            // CRUD
        ];

        // Crear los permisos para el guard `api`
        foreach ($permisos as $permiso) {
            Permission::firstOrCreate(['name' => $permiso, 'guard_name' => 'api']);
        }

        // Crear roles si no existen
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'api']);
        // Asignar todos los permisos al rol admin
        $admin->syncPermissions(Permission::all());

        $adminFam = Role::firstOrCreate(['name' => 'admin_familia', 'guard_name' => 'api']);
        $adminFam->syncPermissions([
            'ver_usuarios',
            'editar_usuarios',
            'editar_perfil',
            'editar_municipalidad',
            'visualizar_municipalidad',
        ]);

        $usuario = Role::firstOrCreate(['name' => 'usuario', 'guard_name' => 'api']);
        $usuario->syncPermissions([
            'editar_perfil',
            'editar_municipalidad',
            'visualizar_municipalidad',
        ]);
    }
}
