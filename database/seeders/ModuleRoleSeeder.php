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

        $adminRole = Role::where('name', 'admin')->first();
        $adminFamilyRole = Role::where('name', 'admin_familia')->first();
        $userRole = Role::where('name', 'usuario')->first();

        // Obtén módulos específicos para asignar
        $modulosParaAdmin = Module::whereIn('code', ['01', '02', '03'])->get();
        $modulosParaAdminfamilia = Module::whereIn('code', ['01', '02', '03'])->get();  // usa los códigos que definiste
        $modulosParaUser = Module::whereIn('code', ['02', '03'])->get();

        // Asigna los módulos a los roles
        foreach ($modulosParaAdmin as $modulo) {
            $modulo->roles()->syncWithoutDetaching([$adminRole->id]);
        }

        foreach ($modulosParaUser as $modulo) {
            $modulo->roles()->syncWithoutDetaching([$userRole->id]);
        }
        foreach ($modulosParaAdminfamilia as $modulo) {
            $modulo->roles()->syncWithoutDetaching([$userRole->id]);
        }
    }
}
