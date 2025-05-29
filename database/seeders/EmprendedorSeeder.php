<?php

namespace Database\Seeders;


use App\Models\Asociacion;
use App\Models\Emprendedor;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class EmprendedorSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        $users = User::all();
        $asociaciones = Asociacion::all();

        if ($users->isEmpty()) {
            $this->command->error('No hay usuarios en la base de datos.');
            return;
        }
        if ($asociaciones->isEmpty()) {
            $this->command->error('No hay asociaciones en la base de datos.');
            return;
        }

        foreach ($asociaciones as $asociacion) {
            $selectedUser = $users->random();
            $emprendedorData = [
                'id' => (string) Str::uuid(),
                'asociacion_id' => $asociacion->id,
                'razon_social' => 'Emprendimiento en ' . $asociacion->nombre,
                'name_family' => 'Familia ' . strtoupper(Str::random(2)),
                'status' => true,
                'address' => $faker->streetAddress . ', ' . $asociacion->lugar,
                'code' => 'EMP-' . strtoupper(Str::random(5)),
                'ruc' => $faker->unique()->numerify('###########'), // 11 dígitos
                'description' => "Emprendimiento dedicado a " . $faker->randomElement(['artesanía', 'agricultura', 'textiles', 'alimentos']) . " en " . $asociacion->nombre,
                'lugar' => $asociacion->lugar,
                'img_logo' => "emprendimientos/logos/" . Str::random(10) . ".png",
            ];

            $emprendedor = Emprendedor::create($emprendedorData);

            foreach ($selectedUser as $user) {
                DB::table('emprendedor_user')->updateOrInsert(
                    [
                        'user_id' => $selectedUser->id,
                        'emprendedor_id' => $emprendedor->id,
                    ],
                    [
                        'id' => (string) Str::uuid(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }
    }
}
