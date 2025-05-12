<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Section;
use Illuminate\Support\Str;

class SectionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sections = [
            [
                'id' => Str::uuid(),
                'code' => '01',
                'name' => 'Inicio',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null
            ],
            [
                'id' => Str::uuid(),
                'code' => '02',
                'name' => 'Acerca de',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null
            ],
            [
                'id' => Str::uuid(),
                'code' => '03',
                'name' => 'Lugares',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null
            ],
            [
                'id' => Str::uuid(),
                'code' => '04',
                'name' => 'Hoteles',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null
            ],
            [
                'id' => Str::uuid(),
                'code' => '05',
                'name' => 'Blog',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null
            ],
            [
                'id' => Str::uuid(),
                'code' => '06',
                'name' => 'Contacto',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null
            ]
        ];

        // Insertar las secciones en la base de datos
        foreach ($sections as $section) {
            Section::create($section);
        }
    }
}
