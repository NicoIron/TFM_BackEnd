<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProyectoUsuario;

class ProyectoUsuariosSeeder extends Seeder
{
    public function run(): void
    {
        $proyectoUsuarios = [
            // ===== PROYECTO A (GOOGLE) =====
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

            // ===== PROYECTO B (GOOGLE) =====
            ['id_proyecto' => 'PROJ-B', 'id_usuario' => 'USR-COMITE',       'id_organizacion' => 'Google'],
            ['id_proyecto' => 'PROJ-B', 'id_usuario' => 'USR-JEFETI-B',     'id_organizacion' => 'Google'],
            ['id_proyecto' => 'PROJ-B', 'id_usuario' => 'USR-LIDERCAL-B',   'id_organizacion' => 'Google'],
            ['id_proyecto' => 'PROJ-B', 'id_usuario' => 'USR-LIDERQA-B',    'id_organizacion' => 'Google'],
            ['id_proyecto' => 'PROJ-B', 'id_usuario' => 'USR-SCRUM-B',      'id_organizacion' => 'Google'],

            // ===== PROYECTO A (FACEBOOK) =====
            ['id_proyecto' => 'PROJ-A-FB', 'id_usuario' => 'USR-COMITE-FB',       'id_organizacion' => 'Facebook'], // CAMBIO: antes 'PROJ-A'
            ['id_proyecto' => 'PROJ-A-FB', 'id_usuario' => 'USR-JEFETI-A-FB',     'id_organizacion' => 'Facebook'], // CAMBIO: antes 'PROJ-A'
            ['id_proyecto' => 'PROJ-A-FB', 'id_usuario' => 'USR-LIDERINFRA-A-FB', 'id_organizacion' => 'Facebook'], // CAMBIO: antes 'PROJ-A'
            ['id_proyecto' => 'PROJ-A-FB', 'id_usuario' => 'USR-LIDERDEV-A-FB',   'id_organizacion' => 'Facebook'], // CAMBIO: antes 'PROJ-A'
            ['id_proyecto' => 'PROJ-A-FB', 'id_usuario' => 'USR-LIDERIA-A-FB',    'id_organizacion' => 'Facebook'], // CAMBIO: antes 'PROJ-A'
            ['id_proyecto' => 'PROJ-A-FB', 'id_usuario' => 'USR-LIDERCAL-A-FB',   'id_organizacion' => 'Facebook'], // CAMBIO: antes 'PROJ-A'
            ['id_proyecto' => 'PROJ-A-FB', 'id_usuario' => 'USR-LIDERCS-A-FB',    'id_organizacion' => 'Facebook'], // CAMBIO: antes 'PROJ-A'
            ['id_proyecto' => 'PROJ-A-FB', 'id_usuario' => 'USR-GERENTE-A-FB',    'id_organizacion' => 'Facebook'], // CAMBIO: antes 'PROJ-A'
            ['id_proyecto' => 'PROJ-A-FB', 'id_usuario' => 'USR-LIDERTEC-A-FB',   'id_organizacion' => 'Facebook'], // CAMBIO: antes 'PROJ-A'
            ['id_proyecto' => 'PROJ-A-FB', 'id_usuario' => 'USR-LIDERQA-A-FB',    'id_organizacion' => 'Facebook'], // CAMBIO: antes 'PROJ-A'
            ['id_proyecto' => 'PROJ-A-FB', 'id_usuario' => 'USR-DEV-A-FB',        'id_organizacion' => 'Facebook'], // CAMBIO: antes 'PROJ-A'
            ['id_proyecto' => 'PROJ-A-FB', 'id_usuario' => 'USR-AUTO-A-FB',       'id_organizacion' => 'Facebook'], // CAMBIO: antes 'PROJ-A'
            ['id_proyecto' => 'PROJ-A-FB', 'id_usuario' => 'USR-FUNC-A-FB',       'id_organizacion' => 'Facebook'], // CAMBIO: antes 'PROJ-A'
            ['id_proyecto' => 'PROJ-A-FB', 'id_usuario' => 'USR-SCRUM-A-FB',      'id_organizacion' => 'Facebook'], // CAMBIO: antes 'PROJ-A'

            // ===== PROYECTO B (FACEBOOK) =====
            ['id_proyecto' => 'PROJ-B-FB', 'id_usuario' => 'USR-COMITE-FB',    'id_organizacion' => 'Facebook'], // CAMBIO: antes 'PROJ-B'
            ['id_proyecto' => 'PROJ-B-FB', 'id_usuario' => 'USR-JEFETI-B-FB',  'id_organizacion' => 'Facebook'], // CAMBIO: antes 'PROJ-B'
            ['id_proyecto' => 'PROJ-B-FB', 'id_usuario' => 'USR-LIDERCAL-B-FB', 'id_organizacion' => 'Facebook'], // CAMBIO: antes 'PROJ-B'
            ['id_proyecto' => 'PROJ-B-FB', 'id_usuario' => 'USR-LIDERQA-B-FB', 'id_organizacion' => 'Facebook'], // CAMBIO: antes 'PROJ-B'
            ['id_proyecto' => 'PROJ-B-FB', 'id_usuario' => 'USR-SCRUM-B-FB',   'id_organizacion' => 'Facebook'], // CAMBIO: antes 'PROJ-B'
        ];

        foreach ($proyectoUsuarios as $pu) {
            ProyectoUsuario::create($pu);
        }
    }
}
