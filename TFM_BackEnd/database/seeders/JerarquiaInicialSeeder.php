<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JerarquiaInicial;

class JerarquiaInicialSeeder extends Seeder
{
    public function run(): void
    {
        $jerarquias = [
            ['id_jerarquia' => 1,  'cargo' => 'Administrador General'],
            ['id_jerarquia' => 2,  'cargo' => 'Comite Operativo'],
            ['id_jerarquia' => 3,  'cargo' => 'Jefe TI'],
            ['id_jerarquia' => 4,  'cargo' => 'Lider de clan Infraestructura'],
            ['id_jerarquia' => 5,  'cargo' => 'Lider de clan Developer'],
            ['id_jerarquia' => 6,  'cargo' => 'Lider de clan IA'],
            ['id_jerarquia' => 7,  'cargo' => 'Lider de clan Calidad'],
            ['id_jerarquia' => 8,  'cargo' => 'Lider de clan Customer Service'],
            ['id_jerarquia' => 9,  'cargo' => 'Gerente de Proyecto'],
            ['id_jerarquia' => 10, 'cargo' => 'Lider Tecnico'],
            ['id_jerarquia' => 11, 'cargo' => 'Lider Qa'],
            ['id_jerarquia' => 12, 'cargo' => 'Lider funcional'],
            ['id_jerarquia' => 13, 'cargo' => 'Developer'],
            ['id_jerarquia' => 14, 'cargo' => 'Automatizador'],
            ['id_jerarquia' => 15, 'cargo' => 'Funcional'],
            ['id_jerarquia' => 16, 'cargo' => 'Scrum'],
        ];

        foreach ($jerarquias as $item) {
            JerarquiaInicial::create([
                'id_jerarquia'    => $item['id_jerarquia'],
                'id_organizacion' => 'Google',
                'cargo'           => $item['cargo'],
            ]);
        }
    }
}
