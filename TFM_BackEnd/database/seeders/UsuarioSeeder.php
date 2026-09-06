<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Usuario;

class UsuarioSeeder extends Seeder
{
    public function run(): void
    {
        $usuarios = [
            // ==========================================
            // ORGANIZACIÓN: GOOGLE
            // ==========================================

            // Admin
            [
                'id_usuario'      => 'USR-ADMIN',
                'nombre'          => 'Admin',
                'apellido'        => 'Demo',
                'email'           => 'admin@demo.com',
                'password_hash'   => bcrypt('123456'),
                'username'        => 'admin',
                'id_rol'          => 1,
                'id_organizacion' => 'Google',
                'id_jerarquia'    => 1,
            ],

            // Transversal
            [
                'id_usuario'      => 'USR-COMITE',
                'nombre'          => 'Ana',
                'apellido'        => 'Torres',
                'email'           => 'ana.torres@demo.com',
                'password_hash'   => bcrypt('123456'),
                'username'        => 'ana.torres',
                'id_rol'          => 2,
                'id_organizacion' => 'Google',
                'id_jerarquia'    => 2,
            ],
            [
                'id_usuario'      => 'USR-JEFE-CONTA',
                'nombre'          => 'Ricardo',
                'apellido'        => 'Fuentes',
                'email'           => 'ricardo.fuentes@demo.com',
                'password_hash'   => bcrypt('123456'),
                'username'        => 'ricardo.fuentes',
                'id_rol'          => 16, // Jefe de Contabilidad
                'id_organizacion' => 'Google',
                'id_jerarquia'    => 16,
            ],
            [
                'id_usuario'      => 'USR-CONTA-1',
                'nombre'          => 'Patricia',
                'apellido'        => 'Vega',
                'email'           => 'patricia.vega@demo.com',
                'password_hash'   => bcrypt('123456'),
                'username'        => 'patricia.vega',
                'id_rol'          => 17, // Contabilidad
                'id_organizacion' => 'Google',
                'id_jerarquia'    => 17,
            ],
            [
                'id_usuario'      => 'USR-CONTA-2',
                'nombre'          => 'Andres',
                'apellido'        => 'Pinto',
                'email'           => 'andres.pinto@demo.com',
                'password_hash'   => bcrypt('123456'),
                'username'        => 'andres.pinto',
                'id_rol'          => 17, // Contabilidad
                'id_organizacion' => 'Google',
                'id_jerarquia'    => 17,
            ],

            // ===== PROYECTO A =====
            [
                'id_usuario'      => 'USR-JEFETI-A',
                'nombre'          => 'Carlos',
                'apellido'        => 'Ramirez',
                'email'           => 'carlos.ramirez@demo.com',
                'password_hash'   => bcrypt('123456'),
                'username'        => 'carlos.ramirez',
                'id_rol'          => 3,
                'id_organizacion' => 'Google',
                'id_jerarquia'    => 3,
            ],
            [
                'id_usuario'      => 'USR-LIDERINFRA-A',
                'nombre'          => 'Claudia',
                'apellido'        => 'Rios',
                'email'           => 'claudia.rios@demo.com',
                'password_hash'   => bcrypt('123456'),
                'username'        => 'claudia.rios',
                'id_rol'          => 4,
                'id_organizacion' => 'Google',
                'id_jerarquia'    => 4,
            ],
            [
                'id_usuario'      => 'USR-LIDERDEV-A',
                'nombre'          => 'Maria',
                'apellido'        => 'Lopez',
                'email'           => 'maria.lopez@demo.com',
                'password_hash'   => bcrypt('123456'),
                'username'        => 'maria.lopez',
                'id_rol'          => 5,
                'id_organizacion' => 'Google',
                'id_jerarquia'    => 5,
            ],
            [
                'id_usuario'      => 'USR-LIDERIA-A',
                'nombre'          => 'Fernando',
                'apellido'        => 'Mora',
                'email'           => 'fernando.mora@demo.com',
                'password_hash'   => bcrypt('123456'),
                'username'        => 'fernando.mora',
                'id_rol'          => 6,
                'id_organizacion' => 'Google',
                'id_jerarquia'    => 6,
            ],
            [
                'id_usuario'      => 'USR-LIDERCAL-A',
                'nombre'          => 'Gloria',
                'apellido'        => 'Peña',
                'email'           => 'gloria.pena@demo.com',
                'password_hash'   => bcrypt('123456'),
                'username'        => 'gloria.pena',
                'id_rol'          => 7,
                'id_organizacion' => 'Google',
                'id_jerarquia'    => 7,
            ],
            [
                'id_usuario'      => 'USR-LIDERCS-A',
                'nombre'          => 'Hector',
                'apellido'        => 'Suarez',
                'email'           => 'hector.suarez@demo.com',
                'password_hash'   => bcrypt('123456'),
                'username'        => 'hector.suarez',
                'id_rol'          => 8,
                'id_organizacion' => 'Google',
                'id_jerarquia'    => 8,
            ],
            [
                'id_usuario'      => 'USR-GERENTE-A',
                'nombre'          => 'Isabel',
                'apellido'        => 'Cruz',
                'email'           => 'isabel.cruz@demo.com',
                'password_hash'   => bcrypt('123456'),
                'username'        => 'isabel.cruz',
                'id_rol'          => 9, // Gerente proyecto
                'id_organizacion' => 'Google',
                'id_jerarquia'    => 9,
            ],
            [
                'id_usuario'      => 'USR-LIDERTEC-A',
                'nombre'          => 'Jorge',
                'apellido'        => 'Vargas',
                'email'           => 'jorge.vargas@demo.com',
                'password_hash'   => bcrypt('123456'),
                'username'        => 'jorge.vargas',
                'id_rol'          => 10, // Lider Tecnico
                'id_organizacion' => 'Google',
                'id_jerarquia'    => 10,
            ],
            [
                'id_usuario'      => 'USR-LIDERQA-A',
                'nombre'          => 'Karen',
                'apellido'        => 'Blanco',
                'email'           => 'karen.blanco@demo.com',
                'password_hash'   => bcrypt('123456'),
                'username'        => 'karen.blanco',
                'id_rol'          => 11, // Lider Qa
                'id_organizacion' => 'Google',
                'id_jerarquia'    => 11,
            ],
            [
                'id_usuario'      => 'USR-DEV-A',
                'nombre'          => 'Juan',
                'apellido'        => 'Perez',
                'email'           => 'juan.perez@demo.com',
                'password_hash'   => bcrypt('123456'),
                'username'        => 'juan.perez',
                'id_rol'          => 12, // Developer
                'id_organizacion' => 'Google',
                'id_jerarquia'    => 12,
            ],
            [
                'id_usuario'      => 'USR-AUTO-A',
                'nombre'          => 'Luis',
                'apellido'        => 'Martinez',
                'email'           => 'luis.martinez@demo.com',
                'password_hash'   => bcrypt('123456'),
                'username'        => 'luis.martinez',
                'id_rol'          => 13, // Automatizador
                'id_organizacion' => 'Google',
                'id_jerarquia'    => 13,
            ],
            [
                'id_usuario'      => 'USR-FUNC-A',
                'nombre'          => 'Monica',
                'apellido'        => 'Leon',
                'email'           => 'monica.leon@demo.com',
                'password_hash'   => bcrypt('123456'),
                'username'        => 'monica.leon',
                'id_rol'          => 14, // Funcional
                'id_organizacion' => 'Google',
                'id_jerarquia'    => 14,
            ],
            [
                'id_usuario'      => 'USR-SCRUM-A',
                'nombre'          => 'Nicolas',
                'apellido'        => 'Ortiz',
                'email'           => 'nicolas.ortiz@demo.com',
                'password_hash'   => bcrypt('123456'),
                'username'        => 'nicolas.ortiz',
                'id_rol'          => 15, // Scrum
                'id_organizacion' => 'Google',
                'id_jerarquia'    => 15,
            ],

            // ===== PROYECTO B =====
            [
                'id_usuario'      => 'USR-JEFETI-B',
                'nombre'          => 'Roberto',
                'apellido'        => 'Silva',
                'email'           => 'roberto.silva@demo.com',
                'password_hash'   => bcrypt('123456'),
                'username'        => 'roberto.silva',
                'id_rol'          => 3,
                'id_organizacion' => 'Google',
                'id_jerarquia'    => 3,
            ],
            [
                'id_usuario'      => 'USR-LIDERCAL-B',
                'nombre'          => 'Sofia',
                'apellido'        => 'Herrera',
                'email'           => 'sofia.herrera@demo.com',
                'password_hash'   => bcrypt('123456'),
                'username'        => 'sofia.herrera',
                'id_rol'          => 7,
                'id_organizacion' => 'Google',
                'id_jerarquia'    => 7,
            ],
            [
                'id_usuario'      => 'USR-LIDERQA-B',
                'nombre'          => 'Luisa',
                'apellido'        => 'Diaz',
                'email'           => 'luisa.diaz@demo.com',
                'password_hash'   => bcrypt('123456'),
                'username'        => 'luisa.diaz',
                'id_rol'          => 11,
                'id_organizacion' => 'Google',
                'id_jerarquia'    => 11,
            ],
            [
                'id_usuario'      => 'USR-SCRUM-B',
                'nombre'          => 'Valentina',
                'apellido'        => 'Mora',
                'email'           => 'valentina.mora@demo.com',
                'password_hash'   => bcrypt('123456'),
                'username'        => 'valentina.mora',
                'id_rol'          => 15, // Scrum
                'id_organizacion' => 'Google',
                'id_jerarquia'    => 15,
            ],

            // ==========================================
            // ORGANIZACIÓN: FACEBOOK
            // ==========================================

            // Admin
            [
                'id_usuario'      => 'USR-ADMIN-FB',
                'nombre'          => 'Admin',
                'apellido'        => 'Demo',
                'email'           => 'admin.facebook@demo.com',
                'password_hash'   => bcrypt('123456'),
                'username'        => 'admin.facebook',
                'id_rol'          => 1,
                'id_organizacion' => 'Facebook',
                'id_jerarquia'    => 1,
            ],

            // Transversal
            [
                'id_usuario'      => 'USR-COMITE-FB',
                'nombre'          => 'Ana',
                'apellido'        => 'Torres',
                'email'           => 'ana.torres.facebook@demo.com',
                'password_hash'   => bcrypt('123456'),
                'username'        => 'ana.torres.facebook',
                'id_rol'          => 2,
                'id_organizacion' => 'Facebook',
                'id_jerarquia'    => 2,
            ],
            [
                'id_usuario'      => 'USR-JEFE-CONTA-FB',
                'nombre'          => 'Ricardo',
                'apellido'        => 'Fuentes',
                'email'           => 'ricardo.fuentes.facebook@demo.com',
                'password_hash'   => bcrypt('123456'),
                'username'        => 'ricardo.fuentes.facebook',
                'id_rol'          => 16, // Jefe de Contabilidad
                'id_organizacion' => 'Facebook',
                'id_jerarquia'    => 16,
            ],
            [
                'id_usuario'      => 'USR-CONTA-1-FB',
                'nombre'          => 'Patricia',
                'apellido'        => 'Vega',
                'email'           => 'patricia.vega.facebook@demo.com',
                'password_hash'   => bcrypt('123456'),
                'username'        => 'patricia.vega.facebook',
                'id_rol'          => 17, // Contabilidad
                'id_organizacion' => 'Facebook',
                'id_jerarquia'    => 17,
            ],
            [
                'id_usuario'      => 'USR-CONTA-2-FB',
                'nombre'          => 'Andres',
                'apellido'        => 'Pinto',
                'email'           => 'andres.pinto.facebook@demo.com',
                'password_hash'   => bcrypt('123456'),
                'username'        => 'andres.pinto.facebook',
                'id_rol'          => 17, // Contabilidad
                'id_organizacion' => 'Facebook',
                'id_jerarquia'    => 17,
            ],

            // ===== PROYECTO A =====
            [
                'id_usuario'      => 'USR-JEFETI-A-FB',
                'nombre'          => 'Carlos',
                'apellido'        => 'Ramirez',
                'email'           => 'carlos.ramirez.facebook@demo.com',
                'password_hash'   => bcrypt('123456'),
                'username'        => 'carlos.ramirez.facebook',
                'id_rol'          => 3,
                'id_organizacion' => 'Facebook',
                'id_jerarquia'    => 3,
            ],
            [
                'id_usuario'      => 'USR-LIDERINFRA-A-FB',
                'nombre'          => 'Claudia',
                'apellido'        => 'Rios',
                'email'           => 'claudia.rios.facebook@demo.com',
                'password_hash'   => bcrypt('123456'),
                'username'        => 'claudia.rios.facebook',
                'id_rol'          => 4,
                'id_organizacion' => 'Facebook',
                'id_jerarquia'    => 4,
            ],
            [
                'id_usuario'      => 'USR-LIDERDEV-A-FB',
                'nombre'          => 'Maria',
                'apellido'        => 'Lopez',
                'email'           => 'maria.lopez.facebook@demo.com',
                'password_hash'   => bcrypt('123456'),
                'username'        => 'maria.lopez.facebook',
                'id_rol'          => 5,
                'id_organizacion' => 'Facebook',
                'id_jerarquia'    => 5,
            ],
            [
                'id_usuario'      => 'USR-LIDERIA-A-FB',
                'nombre'          => 'Fernando',
                'apellido'        => 'Mora',
                'email'           => 'fernando.mora.facebook@demo.com',
                'password_hash'   => bcrypt('123456'),
                'username'        => 'fernando.mora.facebook',
                'id_rol'          => 6,
                'id_organizacion' => 'Facebook',
                'id_jerarquia'    => 6,
            ],
            [
                'id_usuario'      => 'USR-LIDERCAL-A-FB',
                'nombre'          => 'Gloria',
                'apellido'        => 'Peña',
                'email'           => 'gloria.pena.facebook@demo.com',
                'password_hash'   => bcrypt('123456'),
                'username'        => 'gloria.pena.facebook',
                'id_rol'          => 7,
                'id_organizacion' => 'Facebook',
                'id_jerarquia'    => 7,
            ],
            [
                'id_usuario'      => 'USR-LIDERCS-A-FB',
                'nombre'          => 'Hector',
                'apellido'        => 'Suarez',
                'email'           => 'hector.suarez.facebook@demo.com',
                'password_hash'   => bcrypt('123456'),
                'username'        => 'hector.suarez.facebook',
                'id_rol'          => 8,
                'id_organizacion' => 'Facebook',
                'id_jerarquia'    => 8,
            ],
            [
                'id_usuario'      => 'USR-GERENTE-A-FB',
                'nombre'          => 'Isabel',
                'apellido'        => 'Cruz',
                'email'           => 'isabel.cruz.facebook@demo.com',
                'password_hash'   => bcrypt('123456'),
                'username'        => 'isabel.cruz.facebook',
                'id_rol'          => 9, // Gerente proyecto
                'id_organizacion' => 'Facebook',
                'id_jerarquia'    => 9,
            ],
            [
                'id_usuario'      => 'USR-LIDERTEC-A-FB',
                'nombre'          => 'Jorge',
                'apellido'        => 'Vargas',
                'email'           => 'jorge.vargas.facebook@demo.com',
                'password_hash'   => bcrypt('123456'),
                'username'        => 'jorge.vargas.facebook',
                'id_rol'          => 10, // Lider Tecnico
                'id_organizacion' => 'Facebook',
                'id_jerarquia'    => 10,
            ],
            [
                'id_usuario'      => 'USR-LIDERQA-A-FB',
                'nombre'          => 'Karen',
                'apellido'        => 'Blanco',
                'email'           => 'karen.blanco.facebook@demo.com',
                'password_hash'   => bcrypt('123456'),
                'username'        => 'karen.blanco.facebook',
                'id_rol'          => 11, // Lider Qa
                'id_organizacion' => 'Facebook',
                'id_jerarquia'    => 11,
            ],
            [
                'id_usuario'      => 'USR-DEV-A-FB',
                'nombre'          => 'Juan',
                'apellido'        => 'Perez',
                'email'           => 'juan.perez.facebook@demo.com',
                'password_hash'   => bcrypt('123456'),
                'username'        => 'juan.perez.facebook',
                'id_rol'          => 12, // Developer
                'id_organizacion' => 'Facebook',
                'id_jerarquia'    => 12,
            ],
            [
                'id_usuario'      => 'USR-AUTO-A-FB',
                'nombre'          => 'Luis',
                'apellido'        => 'Martinez',
                'email'           => 'luis.martinez.facebook@demo.com',
                'password_hash'   => bcrypt('123456'),
                'username'        => 'luis.martinez.facebook',
                'id_rol'          => 13, // Automatizador
                'id_organizacion' => 'Facebook',
                'id_jerarquia'    => 13,
            ],
            [
                'id_usuario'      => 'USR-FUNC-A-FB',
                'nombre'          => 'Monica',
                'apellido'        => 'Leon',
                'email'           => 'monica.leon.facebook@demo.com',
                'password_hash'   => bcrypt('123456'),
                'username'        => 'monica.leon.facebook',
                'id_rol'          => 14, // Funcional
                'id_organizacion' => 'Facebook',
                'id_jerarquia'    => 14,
            ],
            [
                'id_usuario'      => 'USR-SCRUM-A-FB',
                'nombre'          => 'Nicolas',
                'apellido'        => 'Ortiz',
                'email'           => 'nicolas.ortiz.facebook@demo.com',
                'password_hash'   => bcrypt('123456'),
                'username'        => 'nicolas.ortiz.facebook',
                'id_rol'          => 15, // Scrum
                'id_organizacion' => 'Facebook',
                'id_jerarquia'    => 15,
            ],

            // ===== PROYECTO B =====
            [
                'id_usuario'      => 'USR-JEFETI-B-FB',
                'nombre'          => 'Roberto',
                'apellido'        => 'Silva',
                'email'           => 'roberto.silva.facebook@demo.com',
                'password_hash'   => bcrypt('123456'),
                'username'        => 'roberto.silva.facebook',
                'id_rol'          => 3,
                'id_organizacion' => 'Facebook',
                'id_jerarquia'    => 3,
            ],
            [
                'id_usuario'      => 'USR-LIDERCAL-B-FB',
                'nombre'          => 'Sofia',
                'apellido'        => 'Herrera',
                'email'           => 'sofia.herrera.facebook@demo.com',
                'password_hash'   => bcrypt('123456'),
                'username'        => 'sofia.herrera.facebook',
                'id_rol'          => 7,
                'id_organizacion' => 'Facebook',
                'id_jerarquia'    => 7,
            ],
            [
                'id_usuario'      => 'USR-LIDERQA-B-FB',
                'nombre'          => 'Luisa',
                'apellido'        => 'Diaz',
                'email'           => 'luisa.diaz.facebook@demo.com',
                'password_hash'   => bcrypt('123456'),
                'username'        => 'luisa.diaz.facebook',
                'id_rol'          => 11,
                'id_organizacion' => 'Facebook',
                'id_jerarquia'    => 11,
            ],
            [
                'id_usuario'      => 'USR-SCRUM-B-FB',
                'nombre'          => 'Valentina',
                'apellido'        => 'Mora',
                'email'           => 'valentina.mora.facebook@demo.com',
                'password_hash'   => bcrypt('123456'),
                'username'        => 'valentina.mora.facebook',
                'id_rol'          => 15, // Scrum
                'id_organizacion' => 'Facebook',
                'id_jerarquia'    => 15,
            ],
        ];

        foreach ($usuarios as $usuario) {
            Usuario::create($usuario);
        }
    }
}
