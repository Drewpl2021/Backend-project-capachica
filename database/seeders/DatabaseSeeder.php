<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            UserAdminSeeder::class,
        ]);
        $this->call([
            ParentModuleAndModuleSeeder::class,
        ]);
        $this->call([
            MunicipalidadSeeder::class,
            MunicipalidadDescripcionSeeder::class,
            SliderMuniSeeder::class,
            SectionsSeeder::class,
            SectionsDetailSeeder::class,
            SectionDetailEndSeeder::class,
            SliderMuniSeeder::class,
        ]);

        $this->call([
            AsociacionSeeder::class,
            ImgAsociacionSeeder::class,
            EmprendedorSeeder::class,
            DestinosTuriscosSeeder::class,
            ServiceSedeer::class,
            EmprendimientoServiceSeeder::class,
            ReservaSeeder::class,
            ReservaDetailSeeder::class,
            PaymentSeeder::class,
            SaleSeeder::class,
            SaleDetailSeeder::class,
            ImgService::class,
            ImgEmprendedor::class,
            ImgEmprendedorSeeder::class,
            ModuleRoleSeeder::class
        ]);
    }
}
