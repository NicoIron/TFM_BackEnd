<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Usuario;

class UsuarioSeeder extends Seeder
{
    public function run(): void
    {
        Usuario::create([
            'id_usuario'      => 'Google',
            'nombre'          => 'Admin',
            'apellido'        => 'Demo',
            'email'           => 'admin@demo.com',
            'password_hash'   => bcrypt('123456'),
            'username'        => 'admin',
            'id_rol'          => 1,
            'id_organizacion' => 'Google',
            'id_jerarquia'    => 1,
        ]);
    }
}
