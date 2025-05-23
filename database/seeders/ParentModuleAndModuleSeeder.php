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
        $this->seedParentModule('heroicons_outline:cog-6-tooth', '/example', 1, 'Configuracion Completa de Dashboard y Roles', 'Configuración', 'collapsable', '01');
        $this->seedParentModule('heroicons_outline:banknotes', '/example', 2, 'Ventas y Pagos', 'Ventas', 'collapsable', '02'); //YO
        $this->seedParentModule('heroicons_outline:shopping-cart', '/example', 3, 'Prodcutos y Reservas', 'Productos', 'collapsable', '03'); //YO
        //$this->seedParentModule('heroicons_outline:cog-6-tooth', '/example', 1, '', 'Configuración', 'collapsable', '01');
        //$this->seedParentModule('heroicons_outline:currency-dollar', '/example', 3, 'Contabilidad', 'Contabilidad', 'collapsable', '03');
        //$this->seedParentModule('heroicons_outline:shopping-bag', '/example', 6, 'Ventas', 'Ventas', 'collapsable', '06');
        //$this->seedParentModule('heroicons_outline:home', '/tourism', 2, 'Turismo', 'Atracciones', 'collapsable', '02');
        //$this->seedParentModule('heroicons_outline:map', '/destinations', 4, 'Destinos', 'Geografía', 'collapsable', '04');
        //$this->seedParentModule('heroicons_outline:document-report', '/reports', 7, 'Reportes', 'Análisis', 'collapsable', '07');
        //$this->seedParentModule('heroicons_outline:user-group', '/userManagement', 5, 'Usuarios', 'Administración', 'collapsable', '05');
        //$this->seedParentModule('heroicons_outline:shopping-bag', '/sales', 6, 'Ventas', 'Comercial', 'collapsable', '06');
        //$this->seedParentModule('heroicons_outline:home', '/tourism', 2, 'Turismo', 'Atracciones', 'collapsable', '02');
        //$this->seedParentModule('heroicons_outline:map', '/destinations', 4, 'Destinos', 'Geografía', 'collapsable', '04');
        //$this->seedParentModule('heroicons_outline:document-report', '/reports', 7, 'Reportes', 'Análisis', 'collapsable', '07');
        //$this->seedParentModule('heroicons_outline:user-group', '/userManagement', 5, 'Usuarios', 'Administración', 'collapsable', '05');
        // Agrega más Padres Módulos aquí...



        // Modules configuración
        $this->seedModule('heroicons_outline:user-group', '/homeScreen/setup/user', 1, 'Usuarios', 'basic', '01', '01');
        $this->seedModule('heroicons_outline:folder-open', '/homeScreen/setup/module', 2, 'Modulos', 'basic', '02', '01');
        $this->seedModule('heroicons_outline:clipboard-document', '/homeScreen/setup/parent-module', 3, 'Modulos Padres', 'basic', '03', '01');
        $this->seedModule('heroicons_outline:home-modern', '/homeScreen/setup/municipalidad', 4, 'Municipalidad', 'basic', '04', '01');
        $this->seedModule('heroicons_outline:cog', '/homeScreen/setup/sections', 5, 'Configuración Página', 'basic', '05', '01');
        $this->seedModule('heroicons_outline:user-group', '/homeScreen/setup/role', 6, 'Roles', 'basic', '06', '01');
        $this->seedModule('heroicons_outline:user-group', '/homeScreen/setup/asociaciones', 7, 'Asociaciones', 'basic', '07', '01');
        $this->seedModule('heroicons_outline:arrow-trending-up', '/homeScreen/setup/service', 8, 'Tipo de Servicios', 'basic', '08', '01'); //SAPO
        $this->seedModule('heroicons_outline:currency-dollar', '/homeScreen/sales/payment', 1, 'Pagos', 'basic', '09', '02'); //YO
        $this->seedModule('heroicons_outline:queue-list', '/homeScreen/product/product', 1, 'Productos Ofrecidos', 'basic', '10', '03'); //YO
        $this->seedModule('heroicons_outline:shield-check', '/homeScreen/product/reservas', 2, 'Reservas', 'basic', '11', '03'); //YO

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
