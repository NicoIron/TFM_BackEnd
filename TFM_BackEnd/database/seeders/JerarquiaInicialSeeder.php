<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JerarquiaInicial;

class JerarquiaInicialSeeder extends Seeder
{
    public function run(): void
    {
        JerarquiaInicial::create([
            'id_organizacion' => "Google",
            'id_jerarquia' => '1',
            'cargo'           => 'Administrador General',
        ]);
    }
}
