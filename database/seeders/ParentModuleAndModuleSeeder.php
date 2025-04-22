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
        // Parent Modules
        $this->seedParentModule('heroicons_outline:cog-6-tooth', '/example', 1, 'usuario roles y permisos', 'Configuración', 'collapsable', '01');
        $this->seedParentModule('heroicons_outline:currency-dollar', '/example', 3, 'Contabilidad', 'Contabilidad', 'collapsable', '03');
        $this->seedParentModule('heroicons_outline:shopping-bag', '/example', 6, 'Ventas', 'Ventas', 'collapsable', '06');
        $this->seedParentModule('heroicons_outline:home', '/tourism', 2, 'Turismo', 'Atracciones', 'collapsable', '02');
        $this->seedParentModule('heroicons_outline:map', '/destinations', 4, 'Destinos', 'Geografía', 'collapsable', '04');
        $this->seedParentModule('heroicons_outline:document-report', '/reports', 7, 'Reportes', 'Análisis', 'collapsable', '07');
        $this->seedParentModule('heroicons_outline:user-group', '/userManagement', 5, 'Usuarios', 'Administración', 'collapsable', '05');
        $this->seedParentModule('heroicons_outline:shopping-bag', '/sales', 6, 'Ventas', 'Comercial', 'collapsable', '06');
        $this->seedParentModule('heroicons_outline:home', '/tourism', 2, 'Turismo', 'Atracciones', 'collapsable', '02');
        $this->seedParentModule('heroicons_outline:map', '/destinations', 4, 'Destinos', 'Geografía', 'collapsable', '04');
        $this->seedParentModule('heroicons_outline:document-report', '/reports', 7, 'Reportes', 'Análisis', 'collapsable', '07');
        $this->seedParentModule('heroicons_outline:user-group', '/userManagement', 5, 'Usuarios', 'Administración', 'collapsable', '05');
        // Agrega más Padres Módulos aquí...

        // Modules
        $this->seedModule('heroicons_outline:user-group', '/homeScreen/setup/user', 1, 'Usuarios', 'basic', '01', '01');
        $this->seedModule('heroicons_outline:clipboard-document-check', '/homeScreen/setup/module', 4, 'Modulos', 'basic', '04', '01');
        $this->seedModule('heroicons_outline:banknotes', '/homeScreen/sales/prices', 2, 'Lista de Precios', 'basic', '30', '06');
        $this->seedModule('heroicons_outline:chart-bar', '/homeScreen/accounting/accoutingPlan', 5, 'Plan Contable', 'basic', '13', '03');
        $this->seedModule('heroicons_outline:user-group', '/userManagement/roles', 1, 'Roles y Permisos', 'basic', '01', '05');
        $this->seedModule('heroicons_outline:users', '/userManagement/users', 2, 'Usuarios', 'basic', '02', '05');
        $this->seedModule('heroicons_outline:currency-dollar', '/sales/prices', 3, 'Lista de Precios', 'basic', '01', '06');
        $this->seedModule('heroicons_outline:shopping-cart', '/sales/orders', 4, 'Pedidos', 'basic', '02', '06');
        $this->seedModule('heroicons_outline:banknotes', '/accounting/plan', 5, 'Plan Contable', 'basic', '01', '03');
        $this->seedModule('heroicons_outline:clipboard-check', '/accounting/transactions', 6, 'Transacciones', 'basic', '02', '03');
        $this->seedModule('heroicons_outline:map', '/tourism/attractions', 7, 'Atracciones', 'basic', '01', '02');
        $this->seedModule('heroicons_outline:camera', '/tourism/gallery', 8, 'Galería', 'basic', '02', '02');
        $this->seedModule('heroicons_outline:location-marker', '/destinations/tours', 9, 'Tours', 'basic', '01', '04');
        $this->seedModule('heroicons_outline:map-pin', '/destinations/places', 10, 'Lugares Turísticos', 'basic', '02', '04');
        $this->seedModule('heroicons_outline:document-report', '/reports/tourism', 11, 'Reportes de Turismo', 'basic', '01', '07');
        $this->seedModule('heroicons_outline:chart-bar', '/reports/sales', 12, 'Reportes de Ventas', 'basic', '02', '07');
        // Agrega más Módulos aquí...
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
