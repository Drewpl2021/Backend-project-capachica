<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\Module;

class ModuleRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Obtén los roles
        $adminRole = Role::where('name', 'admin')->first();
        $adminFamilyRole = Role::where('name', 'admin_familia')->first();
        $userRole = Role::where('name', 'usuario')->first(); // No lo usaremos, ya que no le asignaremos módulos

        // Obtén módulos específicos para asignar SEGUN CODIGO
        $modulosParaAdmin = Module::all();  // El admin tiene todos los módulos, incluyendo padres y módulos
        $modulosParaAdminfamilia = Module::whereIn('code', ['09', '08', '10'])->get();  // Los módulos específicos para admin_familia
        $modulosParaUser = collect();  // El usuario no recibirá módulos, por lo que lo dejamos vacío

        // Asigna los módulos a los roles

        // Admin: Asigna todos los módulos
        foreach ($modulosParaAdmin as $modulo) {
            $modulo->roles()->syncWithoutDetaching([$adminRole->id]);
        }

        // Admin Familia: Asigna solo los módulos específicos
        foreach ($modulosParaAdminfamilia as $modulo) {
            $modulo->roles()->syncWithoutDetaching([$adminFamilyRole->id]);
        }

        // Usuario: No asigna módulos, ya que no quieres enviar nada
        // (Eliminamos esta parte, ya que no asignamos ningún módulo al rol usuario)
    }
}

