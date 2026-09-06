<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Usuario;
use App\Models\Roles;

class UsuarioSeeder extends Seeder
{
    public function run(): void
    {
        $usuariosRaw = [
            // ==========================================
            // ORGANIZACIÓN: GOOGLE (id_rol del 1 al 17)
            // ==========================================

            // Admin
            [
                'id_usuario'      => 'USR-ADMIN',
                'nombre'          => 'Admin',
                'apellido'        => 'Demo',
                'email'           => 'admin@demo.com',
                'password_hash'   => bcrypt('123456'),
                'username'        => 'admin',
                'id_rol_codigo'   => '1',
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
                'id_rol_codigo'   => '2',
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
                'id_rol_codigo'   => '16', // Jefe de Contabilidad
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
                'id_rol_codigo'   => '17', // Contabilidad
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
                'id_rol_codigo'   => '17', // Contabilidad
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
                'id_rol_codigo'   => '3',
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
                'id_rol_codigo'   => '4',
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
                'id_rol_codigo'   => '5',
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
                'id_rol_codigo'   => '6',
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
                'id_rol_codigo'   => '7',
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
                'id_rol_codigo'   => '8',
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
                'id_rol_codigo'   => '9', // Gerente proyecto
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
                'id_rol_codigo'   => '10', // Lider Tecnico
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
                'id_rol_codigo'   => '11', // Lider Qa
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
                'id_rol_codigo'   => '12', // Developer
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
                'id_rol_codigo'   => '13', // Automatizador
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
                'id_rol_codigo'   => '14', // Funcional
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
                'id_rol_codigo'   => '15', // Scrum
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
                'id_rol_codigo'   => '3',
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
                'id_rol_codigo'   => '7',
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
                'id_rol_codigo'   => '11',
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
                'id_rol_codigo'   => '15', // Scrum
                'id_organizacion' => 'Google',
                'id_jerarquia'    => 15,
            ],

            // ==========================================
            // ORGANIZACIÓN: FACEBOOK (id_rol del 101 al 117)
            // ==========================================

            // Admin
            [
                'id_usuario'      => 'USR-ADMIN-FB',
                'nombre'          => 'Admin',
                'apellido'        => 'Demo',
                'email'           => 'admin.facebook@demo.com',
                'password_hash'   => bcrypt('123456'),
                'username'        => 'admin.facebook',
                'id_rol_codigo'   => '101', // Admin Facebook
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
                'id_rol_codigo'   => '102', // Comite Operativo Facebook
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
                'id_rol_codigo'   => '116', // Jefe de Contabilidad Facebook
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
                'id_rol_codigo'   => '117', // Contabilidad Facebook
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
                'id_rol_codigo'   => '117', // Contabilidad Facebook
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
                'id_rol_codigo'   => '103', // Jefe TI Facebook
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
                'id_rol_codigo'   => '104', // Lider Infraestructura Facebook
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
                'id_rol_codigo'   => '105', // Lider Developer Facebook
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
                'id_rol_codigo'   => '106', // Lider IA Facebook
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
                'id_rol_codigo'   => '107', // Lider Calidad Facebook
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
                'id_rol_codigo'   => '108', // Lider CS Facebook
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
                'id_rol_codigo'   => '109', // Gerente proyecto Facebook
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
                'id_rol_codigo'   => '110', // Lider Tecnico Facebook
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
                'id_rol_codigo'   => '111', // Lider Qa Facebook
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
                'id_rol_codigo'   => '112', // Developer Facebook
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
                'id_rol_codigo'   => '113', // Automatizador Facebook
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
                'id_rol_codigo'   => '114', // Funcional Facebook
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
                'id_rol_codigo'   => '115', // Scrum Facebook
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
                'id_rol_codigo'   => '103', // Jefe TI Facebook
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
                'id_rol_codigo'   => '107', // Lider Calidad Facebook
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
                'id_rol_codigo'   => '111', // Lider QA Facebook
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
                'id_rol_codigo'   => '115', // Scrum Facebook
                'id_organizacion' => 'Facebook',
                'id_jerarquia'    => 15,
            ],
        ];

        foreach ($usuariosRaw as $uRaw) {
            // Se busca la clave primaria autoincremental de roles
            $rolRegistro = Roles::where('id_rol', (string)$uRaw['id_rol_codigo'])
                ->where('id_organizacion', $uRaw['id_organizacion'])
                ->first();

            if ($rolRegistro) {
                $datosFinales = $uRaw;
                $datosFinales['id_rol'] = $rolRegistro->id; // Guarda la clave autoincremental (bigint)
                unset($datosFinales['id_rol_codigo']);

                Usuario::create($datosFinales);
            }
        }
    }
}
