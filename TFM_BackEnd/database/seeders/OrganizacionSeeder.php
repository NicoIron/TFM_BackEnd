<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Organizacion;

class OrganizacionSeeder extends Seeder
{
    public function run(): void
    {
        Organizacion::create([
            'id_organizacion'     => 'Google',
            'nombre_organizacion' => 'Organización Demo',
            'descripcion'         => 'Organización inicial para pruebas',
        ]);
    }
}
