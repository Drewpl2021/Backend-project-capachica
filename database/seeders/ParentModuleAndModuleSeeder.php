<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ParentModule;
use App\Models\Module;
use Illuminate\Support\Str;

class ParentModuleAndModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->seedParentModule('heroicons_outline:cog-6-tooth', '/example', 1, 'usuario roles y permisos', 'Configuración', 'collapsable', '01');
        $this->seedParentModule('heroicons_outline:currency-dollar', '/example', 3, 'Contabilidad', 'Contabilidad', 'collapsable', '03');
        $this->seedParentModule('heroicons_outline:shopping-bag', '/example', 6, 'Ventas', 'Ventas', 'collapsable', '06');

        // Agrega más módulos padres aquí...

        // Módulos
        $this->seedModule('heroicons_outline:user-group', '/homeScreen/setup/user', 1, 'Usuarios', 'basic', '01', '01');
        $this->seedModule('heroicons_outline:clipboard-document-check', '/homeScreen/setup/module', 4, 'Modulos', 'basic', '04', '01');
        $this->seedModule('heroicons_outline:banknotes', '/homeScreen/sales/prices', 2, 'Lista de Precios', 'basic', '30', '06');
        $this->seedModule('heroicons_outline:chart-bar', '/homeScreen/accounting/accoutingPlan', 5, 'Plan Contable', 'basic', '13', '03');

        // Agrega más módulos aquí...
    }

    private function seedParentModule($icon, $link, $order, $subtitle, $title, $type, $code)
    {
        if (!ParentModule::where('code', $code)->exists()) {
            ParentModule::create([
                'id' => Str::uuid(),
                'icon' => $icon,
                'link' => $link,
                'moduleOrder' => $order,
                'subtitle' => $subtitle,
                'title' => $title,
                'type' => $type,
                'code' => $code,
                'status' => true,
            ]);
        }
    }

    private function seedModule($icon, $link, $order, $title, $type, $code, $parentCode)
    {
        $parent = ParentModule::where('code', $parentCode)->first();

        if ($parent && !Module::where('code', $code)->exists()) {
            Module::create([
                'id' => Str::uuid(),
                'icon' => $icon,
                'link' => $link,
                'moduleOrder' => $order,
                'title' => $title,
                'subtitle' => $title,
                'type' => $type,
                'code' => $code,
                'parent_module_id' => $parent->id,
                'status' => true,
            ]);
        }
    }
}
