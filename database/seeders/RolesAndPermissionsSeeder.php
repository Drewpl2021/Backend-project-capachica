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
        // Limpiar caché de permisos
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Crear permisos con guard 'api'
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
        ];

        // Crear permisos
        foreach ($permisos as $permiso) {
            Permission::firstOrCreate(['name' => $permiso, 'guard_name' => 'api']);
        }

        // Crear roles y asignarles permisos
        $admin = Role::firstOrCreate(
            ['name' => 'admin', 'guard_name' => 'api'], // Asegúrate de usar 'api' aquí
            ['description' => 'Administrador con todos los permisos del sistema']
        );
        $admin->syncPermissions(Permission::all()); // Sincroniza todos los permisos con este rol

        $adminFam = Role::firstOrCreate(
            ['name' => 'admin_familia', 'guard_name' => 'api'], // Asegúrate de usar 'api' aquí
            ['description' => 'Administrador responsable de la familia y municipios']
        );
        $adminFam->syncPermissions([
            'ver_usuarios',
            'editar_usuarios',
            'editar_perfil',
            'editar_municipalidad',
            'visualizar_municipalidad',
        ]);

        $usuario = Role::firstOrCreate(
            ['name' => 'usuario', 'guard_name' => 'api'], // Asegúrate de usar 'api' aquí
            ['description' => 'Usuario con permisos básicos del sistema']
        );
        $usuario->syncPermissions([
            'ver_usuarios',
            'editar_perfil',
            'visualizar_municipalidad',
        ]);
    }
}
