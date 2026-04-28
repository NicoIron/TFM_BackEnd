<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Roles;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        Roles::create([
            'id_rol'          => 'Google',
            'nombre_rol'      => 'Admin',
            'nivel'           => 1,
            'id_organizacion' => 'Google',
            'id_jerarquia'    => 1,
        ]);
    }
}
