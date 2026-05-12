<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            OrganizacionSeeder::class,
            JerarquiaInicialSeeder::class,
            RolesSeeder::class,
            JerarquiaRolSeeder::class,
            UsuarioSeeder::class,
        ]);
    }
}
