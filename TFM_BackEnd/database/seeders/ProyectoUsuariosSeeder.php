<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProyectoUsuario;

class ProyectoUsuariosSeeder extends Seeder
{
    public function run(): void
    {
        $proyectoUsuarios = [
            // Proyecto A
            ['id_proyecto' => 'PROJ-A', 'id_usuario' => 'USR-JEFETI-A',    'id_organizacion' => 'Google'],
            ['id_proyecto' => 'PROJ-A', 'id_usuario' => 'USR-LIDERDEV-A',  'id_organizacion' => 'Google'],
            ['id_proyecto' => 'PROJ-A', 'id_usuario' => 'USR-LIDERFUNC-A', 'id_organizacion' => 'Google'],
            ['id_proyecto' => 'PROJ-A', 'id_usuario' => 'USR-DEV-A',       'id_organizacion' => 'Google'],
            ['id_proyecto' => 'PROJ-A', 'id_usuario' => 'USR-AUTO-A',      'id_organizacion' => 'Google'],

            // Proyecto B
            ['id_proyecto' => 'PROJ-B', 'id_usuario' => 'USR-COMITE-B',    'id_organizacion' => 'Google'],
            ['id_proyecto' => 'PROJ-B', 'id_usuario' => 'USR-JEFETI-B',    'id_organizacion' => 'Google'],
            ['id_proyecto' => 'PROJ-B', 'id_usuario' => 'USR-LIDERCAL-B',  'id_organizacion' => 'Google'],
            ['id_proyecto' => 'PROJ-B', 'id_usuario' => 'USR-LIDERQA-B',   'id_organizacion' => 'Google'],
            ['id_proyecto' => 'PROJ-B', 'id_usuario' => 'USR-LIDERFUNC-B', 'id_organizacion' => 'Google'],
            ['id_proyecto' => 'PROJ-B', 'id_usuario' => 'USR-SCRUM-B',     'id_organizacion' => 'Google'],
        ];

        foreach ($proyectoUsuarios as $pu) {
            ProyectoUsuario::create($pu);
        }
    }
}
