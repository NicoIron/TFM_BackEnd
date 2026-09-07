<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Usuario;
use App\Models\Roles;

class UsuarioSeeder extends Seeder
{
    public function run(): void
    {
        Usuario::query()->delete();

        // 'rol_codigo_google' es el código base 1..17 (el mismo que RolesSeeder
        // usa para Google). Para Facebook se calcula sumando 100 automáticamente.
        $definiciones = [
            // ===== GOOGLE: Admin y Transversal =====
            ['id_usuario' => 'USR-ADMIN',      'nombre' => 'Admin',     'apellido' => 'Demo',    'email' => 'admin@demo.com',           'username' => 'admin',           'rol_codigo_google' => 1,  'id_organizacion' => 'Google'],
            ['id_usuario' => 'USR-COMITE',     'nombre' => 'Ana',       'apellido' => 'Torres',  'email' => 'ana.torres@demo.com',      'username' => 'ana.torres',      'rol_codigo_google' => 2,  'id_organizacion' => 'Google'],
            ['id_usuario' => 'USR-JEFE-CONTA', 'nombre' => 'Ricardo',   'apellido' => 'Fuentes', 'email' => 'ricardo.fuentes@demo.com', 'username' => 'ricardo.fuentes', 'rol_codigo_google' => 16, 'id_organizacion' => 'Google'],
            ['id_usuario' => 'USR-CONTA-1',    'nombre' => 'Patricia',  'apellido' => 'Vega',    'email' => 'patricia.vega@demo.com',   'username' => 'patricia.vega',   'rol_codigo_google' => 17, 'id_organizacion' => 'Google'],
            ['id_usuario' => 'USR-CONTA-2',    'nombre' => 'Andres',    'apellido' => 'Pinto',   'email' => 'andres.pinto@demo.com',    'username' => 'andres.pinto',    'rol_codigo_google' => 17, 'id_organizacion' => 'Google'],

            // ===== GOOGLE: Proyecto A =====
            ['id_usuario' => 'USR-JEFETI-A',     'nombre' => 'Carlos',  'apellido' => 'Ramirez', 'email' => 'carlos.ramirez@demo.com', 'username' => 'carlos.ramirez', 'rol_codigo_google' => 3,  'id_organizacion' => 'Google'],
            ['id_usuario' => 'USR-LIDERINFRA-A', 'nombre' => 'Claudia', 'apellido' => 'Rios',    'email' => 'claudia.rios@demo.com',   'username' => 'claudia.rios',   'rol_codigo_google' => 4,  'id_organizacion' => 'Google'],
            ['id_usuario' => 'USR-LIDERDEV-A',   'nombre' => 'Maria',   'apellido' => 'Lopez',   'email' => 'maria.lopez@demo.com',    'username' => 'maria.lopez',    'rol_codigo_google' => 5,  'id_organizacion' => 'Google'],
            ['id_usuario' => 'USR-LIDERIA-A',    'nombre' => 'Fernando', 'apellido' => 'Mora',    'email' => 'fernando.mora@demo.com',  'username' => 'fernando.mora',  'rol_codigo_google' => 6,  'id_organizacion' => 'Google'],
            ['id_usuario' => 'USR-LIDERCAL-A',   'nombre' => 'Gloria',  'apellido' => 'Peña',    'email' => 'gloria.pena@demo.com',    'username' => 'gloria.pena',    'rol_codigo_google' => 7,  'id_organizacion' => 'Google'],
            ['id_usuario' => 'USR-LIDERCS-A',    'nombre' => 'Hector',  'apellido' => 'Suarez',  'email' => 'hector.suarez@demo.com',  'username' => 'hector.suarez',  'rol_codigo_google' => 8,  'id_organizacion' => 'Google'],
            ['id_usuario' => 'USR-GERENTE-A',    'nombre' => 'Isabel',  'apellido' => 'Cruz',    'email' => 'isabel.cruz@demo.com',    'username' => 'isabel.cruz',    'rol_codigo_google' => 9,  'id_organizacion' => 'Google'],
            ['id_usuario' => 'USR-LIDERTEC-A',   'nombre' => 'Jorge',   'apellido' => 'Vargas',  'email' => 'jorge.vargas@demo.com',   'username' => 'jorge.vargas',   'rol_codigo_google' => 10, 'id_organizacion' => 'Google'],
            ['id_usuario' => 'USR-LIDERQA-A',    'nombre' => 'Karen',   'apellido' => 'Blanco',  'email' => 'karen.blanco@demo.com',   'username' => 'karen.blanco',   'rol_codigo_google' => 11, 'id_organizacion' => 'Google'],
            ['id_usuario' => 'USR-DEV-A',        'nombre' => 'Juan',    'apellido' => 'Perez',   'email' => 'juan.perez@demo.com',     'username' => 'juan.perez',     'rol_codigo_google' => 12, 'id_organizacion' => 'Google'],
            ['id_usuario' => 'USR-AUTO-A',       'nombre' => 'Luis',    'apellido' => 'Martinez', 'email' => 'luis.martinez@demo.com',  'username' => 'luis.martinez',  'rol_codigo_google' => 13, 'id_organizacion' => 'Google'],
            ['id_usuario' => 'USR-FUNC-A',       'nombre' => 'Monica',  'apellido' => 'Leon',    'email' => 'monica.leon@demo.com',    'username' => 'monica.leon',    'rol_codigo_google' => 14, 'id_organizacion' => 'Google'],
            ['id_usuario' => 'USR-SCRUM-A',      'nombre' => 'Nicolas', 'apellido' => 'Ortiz',   'email' => 'nicolas.ortiz@demo.com',  'username' => 'nicolas.ortiz',  'rol_codigo_google' => 15, 'id_organizacion' => 'Google'],

            // ===== GOOGLE: Proyecto B =====
            ['id_usuario' => 'USR-JEFETI-B',   'nombre' => 'Roberto',   'apellido' => 'Silva',   'email' => 'roberto.silva@demo.com',   'username' => 'roberto.silva',   'rol_codigo_google' => 3,  'id_organizacion' => 'Google'],
            ['id_usuario' => 'USR-LIDERCAL-B', 'nombre' => 'Sofia',     'apellido' => 'Herrera', 'email' => 'sofia.herrera@demo.com',   'username' => 'sofia.herrera',   'rol_codigo_google' => 7,  'id_organizacion' => 'Google'],
            ['id_usuario' => 'USR-LIDERQA-B',  'nombre' => 'Luisa',     'apellido' => 'Diaz',    'email' => 'luisa.diaz@demo.com',      'username' => 'luisa.diaz',      'rol_codigo_google' => 11, 'id_organizacion' => 'Google'],
            ['id_usuario' => 'USR-SCRUM-B',    'nombre' => 'Valentina', 'apellido' => 'Mora',    'email' => 'valentina.mora@demo.com',  'username' => 'valentina.mora',  'rol_codigo_google' => 15, 'id_organizacion' => 'Google'],

            // ===== FACEBOOK: Admin y Transversal =====
            ['id_usuario' => 'USR-ADMIN-FB',      'nombre' => 'Admin',    'apellido' => 'Demo',    'email' => 'admin@facebook.com',           'username' => 'admin.facebook',           'rol_codigo_google' => 1,  'id_organizacion' => 'Facebook'],
            ['id_usuario' => 'USR-COMITE-FB',     'nombre' => 'Ana',      'apellido' => 'Torres',  'email' => 'ana.torres@facebook.com',      'username' => 'ana.torres.facebook',      'rol_codigo_google' => 2,  'id_organizacion' => 'Facebook'],
            ['id_usuario' => 'USR-JEFE-CONTA-FB', 'nombre' => 'Ricardo',  'apellido' => 'Fuentes', 'email' => 'ricardo.fuentes@facebook.com', 'username' => 'ricardo.fuentes.facebook', 'rol_codigo_google' => 16, 'id_organizacion' => 'Facebook'],
            ['id_usuario' => 'USR-CONTA-1-FB',    'nombre' => 'Patricia', 'apellido' => 'Vega',    'email' => 'patricia.vega@facebook.com',   'username' => 'patricia.vega.facebook',   'rol_codigo_google' => 17, 'id_organizacion' => 'Facebook'],
            ['id_usuario' => 'USR-CONTA-2-FB',    'nombre' => 'Andres',   'apellido' => 'Pinto',   'email' => 'andres.pinto@facebook.com',    'username' => 'andres.pinto.facebook',    'rol_codigo_google' => 17, 'id_organizacion' => 'Facebook'],

            // ===== FACEBOOK: Proyecto A =====
            ['id_usuario' => 'USR-JEFETI-A-FB',     'nombre' => 'Carlos',  'apellido' => 'Ramirez', 'email' => 'carlos.ramirez@facebook.com', 'username' => 'carlos.ramirez.facebook', 'rol_codigo_google' => 3,  'id_organizacion' => 'Facebook'],
            ['id_usuario' => 'USR-LIDERINFRA-A-FB', 'nombre' => 'Claudia', 'apellido' => 'Rios',    'email' => 'claudia.rios@facebook.com',   'username' => 'claudia.rios.facebook',   'rol_codigo_google' => 4,  'id_organizacion' => 'Facebook'],
            ['id_usuario' => 'USR-LIDERDEV-A-FB',   'nombre' => 'Maria',   'apellido' => 'Lopez',   'email' => 'maria.lopez@facebook.com',    'username' => 'maria.lopez.facebook',    'rol_codigo_google' => 5,  'id_organizacion' => 'Facebook'],
            ['id_usuario' => 'USR-LIDERIA-A-FB',    'nombre' => 'Fernando', 'apellido' => 'Mora',    'email' => 'fernando.mora@facebook.com',  'username' => 'fernando.mora.facebook',  'rol_codigo_google' => 6,  'id_organizacion' => 'Facebook'],
            ['id_usuario' => 'USR-LIDERCAL-A-FB',   'nombre' => 'Gloria',  'apellido' => 'Peña',    'email' => 'gloria.pena@facebook.com',    'username' => 'gloria.pena.facebook',    'rol_codigo_google' => 7,  'id_organizacion' => 'Facebook'],
            ['id_usuario' => 'USR-LIDERCS-A-FB',    'nombre' => 'Hector',  'apellido' => 'Suarez',  'email' => 'hector.suarez@facebook.com',  'username' => 'hector.suarez.facebook',  'rol_codigo_google' => 8,  'id_organizacion' => 'Facebook'],
            ['id_usuario' => 'USR-GERENTE-A-FB',    'nombre' => 'Isabel',  'apellido' => 'Cruz',    'email' => 'isabel.cruz@facebook.com',    'username' => 'isabel.cruz.facebook',    'rol_codigo_google' => 9,  'id_organizacion' => 'Facebook'],
            ['id_usuario' => 'USR-LIDERTEC-A-FB',   'nombre' => 'Jorge',   'apellido' => 'Vargas',  'email' => 'jorge.vargas@facebook.com',   'username' => 'jorge.vargas.facebook',   'rol_codigo_google' => 10, 'id_organizacion' => 'Facebook'],
            ['id_usuario' => 'USR-LIDERQA-A-FB',    'nombre' => 'Karen',   'apellido' => 'Blanco',  'email' => 'karen.blanco@facebook.com',   'username' => 'karen.blanco.facebook',   'rol_codigo_google' => 11, 'id_organizacion' => 'Facebook'],
            ['id_usuario' => 'USR-DEV-A-FB',        'nombre' => 'Juan',    'apellido' => 'Perez',   'email' => 'juan.perez@facebook.com',     'username' => 'juan.perez.facebook',     'rol_codigo_google' => 12, 'id_organizacion' => 'Facebook'],
            ['id_usuario' => 'USR-AUTO-A-FB',       'nombre' => 'Luis',    'apellido' => 'Martinez', 'email' => 'luis.martinez@facebook.com',  'username' => 'luis.martinez.facebook',  'rol_codigo_google' => 13, 'id_organizacion' => 'Facebook'],
            ['id_usuario' => 'USR-FUNC-A-FB',       'nombre' => 'Monica',  'apellido' => 'Leon',    'email' => 'monica.leon@facebook.com',    'username' => 'monica.leon.facebook',    'rol_codigo_google' => 14, 'id_organizacion' => 'Facebook'],
            ['id_usuario' => 'USR-SCRUM-A-FB',      'nombre' => 'Nicolas', 'apellido' => 'Ortiz',   'email' => 'nicolas.ortiz@facebook.com',  'username' => 'nicolas.ortiz.facebook',  'rol_codigo_google' => 15, 'id_organizacion' => 'Facebook'],

            // ===== FACEBOOK: Proyecto B =====
            ['id_usuario' => 'USR-JEFETI-B-FB',   'nombre' => 'Roberto',   'apellido' => 'Silva',   'email' => 'roberto.silva@facebook.com',   'username' => 'roberto.silva.facebook',   'rol_codigo_google' => 3,  'id_organizacion' => 'Facebook'],
            ['id_usuario' => 'USR-LIDERCAL-B-FB', 'nombre' => 'Sofia',     'apellido' => 'Herrera', 'email' => 'sofia.herrera@facebook.com',   'username' => 'sofia.herrera.facebook',   'rol_codigo_google' => 7,  'id_organizacion' => 'Facebook'],
            ['id_usuario' => 'USR-LIDERQA-B-FB',  'nombre' => 'Luisa',     'apellido' => 'Diaz',    'email' => 'luisa.diaz@facebook.com',      'username' => 'luisa.diaz.facebook',      'rol_codigo_google' => 11, 'id_organizacion' => 'Facebook'],
            ['id_usuario' => 'USR-SCRUM-B-FB',    'nombre' => 'Valentina', 'apellido' => 'Mora',    'email' => 'valentina.mora@facebook.com',  'username' => 'valentina.mora.facebook',  'rol_codigo_google' => 15, 'id_organizacion' => 'Facebook'],
        ];

        $usuarios = [];

        foreach ($definiciones as $def) {
            $codigoRol = ($def['id_organizacion'] === 'Facebook')
                ? (string) ($def['rol_codigo_google'] + 100)
                : (string) $def['rol_codigo_google'];

            // CAMBIO CLAVE: usuarios.id_rol y usuarios.id_jerarquia son FKs
            // hacia roles.id y jerarquia_inicial.id (PKs reales), no hacia
            // los códigos de negocio. Buscamos el registro de Roles (que ya
            // trae el id_jerarquia correcto resuelto en RolesSeeder) y
            // usamos sus valores de PK.
            $rolRegistro = Roles::where('id_rol', $codigoRol)
                ->where('id_organizacion', $def['id_organizacion'])
                ->first();

            if (!$rolRegistro) {
                throw new \RuntimeException("Rol no encontrado: codigo={$codigoRol}, org={$def['id_organizacion']}, usuario={$def['id_usuario']}");
            }

            $usuarios[] = [
                'id_usuario'      => $def['id_usuario'],
                'nombre'          => $def['nombre'],
                'apellido'        => $def['apellido'],
                'email'           => $def['email'],
                'password_hash'   => bcrypt('123456'),
                'username'        => $def['username'],
                'id_rol'          => $rolRegistro->id,             // PK real de roles
                'id_organizacion' => $def['id_organizacion'],
                'id_jerarquia'    => $rolRegistro->id_jerarquia,   // PK real de jerarquia_inicial
            ];
        }

        Usuario::insert($usuarios);
    }
}
