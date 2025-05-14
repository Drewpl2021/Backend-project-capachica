<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ServiceSedeer extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Service::create([
            'id' => Str::uuid(),
            'name' => 'Hospedaje',
            'code' => '01'
        ]);
        Service::create([
            'id' => Str::uuid(),
            'name' => 'Alimentos',
            'code' => '02'
        ]);
        Service::create([
            'id' => Str::uuid(),
            'name' => 'Artesanias',
            'code' => '03'
        ]);
        Service::create([
            'id' => Str::uuid(),
            'name' => 'Turismo',
            'code' => '04'
        ]);
    }
}
