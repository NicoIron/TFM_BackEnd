<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Usuario;

class UsuarioSeeder extends Seeder
{
    public function run(): void
    {
        $usuarios = [
            // Admin
            [
                'id_usuario'      => 'USR-ADMIN',
                'nombre'          => 'Admin',
                'apellido'        => 'Demo',
                'email'           => 'admin@demo.com',
                'password_hash'   => bcrypt('123456'),
                'username'        => 'admin',
                'id_rol'          => 1,  // Admin
                'id_organizacion' => 'Google',
                'id_jerarquia'    => 1,
            ],
            // Proyecto A
            [
                'id_usuario'      => 'USR-JEFETI-A',
                'nombre'          => 'Carlos',
                'apellido'        => 'Ramirez',
                'email'           => 'carlos.ramirez@demo.com',
                'password_hash'   => bcrypt('123456'),
                'username'        => 'carlos.ramirez',
                'id_rol'          => 3,  // Jefe TI
                'id_organizacion' => 'Google',
                'id_jerarquia'    => 3,
            ],
            [
                'id_usuario'      => 'USR-LIDERDEV-A',
                'nombre'          => 'Maria',
                'apellido'        => 'Lopez',
                'email'           => 'maria.lopez@demo.com',
                'password_hash'   => bcrypt('123456'),
                'username'        => 'maria.lopez',
                'id_rol'          => 5,  // Lider de clan Developer
                'id_organizacion' => 'Google',
                'id_jerarquia'    => 5,
            ],
            [
                'id_usuario'      => 'USR-LIDERFUNC-A',
                'nombre'          => 'Pedro',
                'apellido'        => 'Gomez',
                'email'           => 'pedro.gomez@demo.com',
                'password_hash'   => bcrypt('123456'),
                'username'        => 'pedro.gomez',
                'id_rol'          => 12, // Lider funcional
                'id_organizacion' => 'Google',
                'id_jerarquia'    => 12,
            ],
            [
                'id_usuario'      => 'USR-DEV-A',
                'nombre'          => 'Juan',
                'apellido'        => 'Perez',
                'email'           => 'juan.perez@demo.com',
                'password_hash'   => bcrypt('123456'),
                'username'        => 'juan.perez',
                'id_rol'          => 13, // Developer
                'id_organizacion' => 'Google',
                'id_jerarquia'    => 13,
            ],
            [
                'id_usuario'      => 'USR-AUTO-A',
                'nombre'          => 'Luis',
                'apellido'        => 'Martinez',
                'email'           => 'luis.martinez@demo.com',
                'password_hash'   => bcrypt('123456'),
                'username'        => 'luis.martinez',
                'id_rol'          => 14, // Automatizador
                'id_organizacion' => 'Google',
                'id_jerarquia'    => 14,
            ],
            // Proyecto B
            [
                'id_usuario'      => 'USR-COMITE-B',
                'nombre'          => 'Ana',
                'apellido'        => 'Torres',
                'email'           => 'ana.torres@demo.com',
                'password_hash'   => bcrypt('123456'),
                'username'        => 'ana.torres',
                'id_rol'          => 2,  // Comite Operativo
                'id_organizacion' => 'Google',
                'id_jerarquia'    => 2,
            ],
            [
                'id_usuario'      => 'USR-JEFETI-B',
                'nombre'          => 'Roberto',
                'apellido'        => 'Silva',
                'email'           => 'roberto.silva@demo.com',
                'password_hash'   => bcrypt('123456'),
                'username'        => 'roberto.silva',
                'id_rol'          => 3,  // Jefe TI
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
                'id_rol'          => 7,  // Lider de clan Calidad
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
                'id_rol'          => 11, // Lider Qa
                'id_organizacion' => 'Google',
                'id_jerarquia'    => 11,
            ],
            [
                'id_usuario'      => 'USR-LIDERFUNC-B',
                'nombre'          => 'Diego',
                'apellido'        => 'Castro',
                'email'           => 'diego.castro@demo.com',
                'password_hash'   => bcrypt('123456'),
                'username'        => 'diego.castro',
                'id_rol'          => 12, // Lider funcional
                'id_organizacion' => 'Google',
                'id_jerarquia'    => 12,
            ],
            [
                'id_usuario'      => 'USR-SCRUM-B',
                'nombre'          => 'Valentina',
                'apellido'        => 'Mora',
                'email'           => 'valentina.mora@demo.com',
                'password_hash'   => bcrypt('123456'),
                'username'        => 'valentina.mora',
                'id_rol'          => 16, // Scrum
                'id_organizacion' => 'Google',
                'id_jerarquia'    => 16,
            ],
        ];

        foreach ($usuarios as $usuario) {
            Usuario::create($usuario);
        }
    }
}
