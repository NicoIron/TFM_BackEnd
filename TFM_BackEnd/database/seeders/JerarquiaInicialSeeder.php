<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JerarquiaInicial;

class JerarquiaInicialSeeder extends Seeder
{
    public function run(): void
    {
        JerarquiaInicial::query()->delete();

        $jerarquias = [
            ['num' => '1',  'cargo' => 'Administrador General'],
            ['num' => '2',  'cargo' => 'Comite Operativo'],
            ['num' => '3',  'cargo' => 'Jefe TI'],
            ['num' => '4',  'cargo' => 'Lider de clan Infraestructura'],
            ['num' => '5',  'cargo' => 'Lider de clan Developer'],
            ['num' => '6',  'cargo' => 'Lider de clan IA'],
            ['num' => '7',  'cargo' => 'Lider de clan Calidad'],
            ['num' => '8',  'cargo' => 'Lider de clan Customer Service'],
            ['num' => '9',  'cargo' => 'Gerente de Proyecto'],
            ['num' => '10', 'cargo' => 'Lider Tecnico'],
            ['num' => '11', 'cargo' => 'Lider Qa'],
            ['num' => '12', 'cargo' => 'Developer'],
            ['num' => '13', 'cargo' => 'Automatizador'],
            ['num' => '14', 'cargo' => 'Funcional'],
            ['num' => '15', 'cargo' => 'Scrum'],
            ['num' => '16', 'cargo' => 'Jefe de Contabilidad'],
            ['num' => '17', 'cargo' => 'Contabilidad'],
        ];

        // id_jerarquia es un código string ÚNICO GLOBALMENTE (unique() simple,
        // no compuesto con id_organizacion), por eso Facebook usa +100.

        // Google (codigo '1' al '17')
        foreach ($jerarquias as $item) {
            JerarquiaInicial::create([
                'id_jerarquia'    => (string) $item['num'],
                'id_organizacion' => 'Google',
                'cargo'           => $item['cargo'],
            ]);
        }

        // Facebook (codigo '101' al '117')
        foreach ($jerarquias as $item) {
            JerarquiaInicial::create([
                'id_jerarquia'    => (string) ((int) $item['num'] + 100),
                'id_organizacion' => 'Facebook',
                'cargo'           => $item['cargo'],
            ]);
        }
    }
}
