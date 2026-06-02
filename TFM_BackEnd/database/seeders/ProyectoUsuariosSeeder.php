<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProyectoUsuario;

class ProyectoUsuariosSeeder extends Seeder
{
    public function run(): void
    {
        $proyectoUsuarios = [
            // ===== PROYECTO A =====
            ['id_proyecto' => 'PROJ-A', 'id_usuario' => 'USR-COMITE',       'id_organizacion' => 'Google'],
            ['id_proyecto' => 'PROJ-A', 'id_usuario' => 'USR-JEFETI-A',     'id_organizacion' => 'Google'],
            ['id_proyecto' => 'PROJ-A', 'id_usuario' => 'USR-LIDERINFRA-A', 'id_organizacion' => 'Google'],
            ['id_proyecto' => 'PROJ-A', 'id_usuario' => 'USR-LIDERDEV-A',   'id_organizacion' => 'Google'],
            ['id_proyecto' => 'PROJ-A', 'id_usuario' => 'USR-LIDERIA-A',    'id_organizacion' => 'Google'],
            ['id_proyecto' => 'PROJ-A', 'id_usuario' => 'USR-LIDERCAL-A',   'id_organizacion' => 'Google'],
            ['id_proyecto' => 'PROJ-A', 'id_usuario' => 'USR-LIDERCS-A',    'id_organizacion' => 'Google'],
            ['id_proyecto' => 'PROJ-A', 'id_usuario' => 'USR-GERENTE-A',    'id_organizacion' => 'Google'],
            ['id_proyecto' => 'PROJ-A', 'id_usuario' => 'USR-LIDERTEC-A',   'id_organizacion' => 'Google'],
            ['id_proyecto' => 'PROJ-A', 'id_usuario' => 'USR-LIDERQA-A',    'id_organizacion' => 'Google'],
            ['id_proyecto' => 'PROJ-A', 'id_usuario' => 'USR-DEV-A',        'id_organizacion' => 'Google'],
            ['id_proyecto' => 'PROJ-A', 'id_usuario' => 'USR-AUTO-A',       'id_organizacion' => 'Google'],
            ['id_proyecto' => 'PROJ-A', 'id_usuario' => 'USR-FUNC-A',       'id_organizacion' => 'Google'],
            ['id_proyecto' => 'PROJ-A', 'id_usuario' => 'USR-SCRUM-A',      'id_organizacion' => 'Google'],

            // ===== PROYECTO B =====
            ['id_proyecto' => 'PROJ-B', 'id_usuario' => 'USR-COMITE',       'id_organizacion' => 'Google'],
            ['id_proyecto' => 'PROJ-B', 'id_usuario' => 'USR-JEFETI-B',     'id_organizacion' => 'Google'],
            ['id_proyecto' => 'PROJ-B', 'id_usuario' => 'USR-LIDERCAL-B',   'id_organizacion' => 'Google'],
            ['id_proyecto' => 'PROJ-B', 'id_usuario' => 'USR-LIDERQA-B',    'id_organizacion' => 'Google'],
            ['id_proyecto' => 'PROJ-B', 'id_usuario' => 'USR-SCRUM-B',      'id_organizacion' => 'Google'],
        ];

        foreach ($proyectoUsuarios as $pu) {
            ProyectoUsuario::create($pu);
        }
    }
}
