<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Roles;
use App\Models\JerarquiaInicial;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['id_rol' => 1,  'nombre_rol' => 'Admin',                         'nivel' => 1, 'id_jerarquia' => 1],
            ['id_rol' => 2,  'nombre_rol' => 'Comite Operativo',               'nivel' => 1, 'id_jerarquia' => 2],
            ['id_rol' => 3,  'nombre_rol' => 'Jefe TI',                        'nivel' => 2, 'id_jerarquia' => 3],
            ['id_rol' => 4,  'nombre_rol' => 'Lider de clan Infraestructura',  'nivel' => 3, 'id_jerarquia' => 4],
            ['id_rol' => 5,  'nombre_rol' => 'Lider de clan Developer',        'nivel' => 3, 'id_jerarquia' => 5],
            ['id_rol' => 6,  'nombre_rol' => 'Lider de clan IA',               'nivel' => 3, 'id_jerarquia' => 6],
            ['id_rol' => 7,  'nombre_rol' => 'Lider de clan Calidad',          'nivel' => 3, 'id_jerarquia' => 7],
            ['id_rol' => 8,  'nombre_rol' => 'Lider de clan Customer Service', 'nivel' => 3, 'id_jerarquia' => 8],
            ['id_rol' => 9,  'nombre_rol' => 'Gerente de Proyecto',            'nivel' => 4, 'id_jerarquia' => 9],
            ['id_rol' => 10, 'nombre_rol' => 'Lider Tecnico',                  'nivel' => 5, 'id_jerarquia' => 10],
            ['id_rol' => 11, 'nombre_rol' => 'Lider Qa',                       'nivel' => 5, 'id_jerarquia' => 11],
            ['id_rol' => 12, 'nombre_rol' => 'Developer',                      'nivel' => 6, 'id_jerarquia' => 12],
            ['id_rol' => 13, 'nombre_rol' => 'Automatizador',                  'nivel' => 6, 'id_jerarquia' => 13],
            ['id_rol' => 14, 'nombre_rol' => 'Funcional',                      'nivel' => 6, 'id_jerarquia' => 14],
            ['id_rol' => 15, 'nombre_rol' => 'Scrum',                          'nivel' => 6, 'id_jerarquia' => 15],
            ['id_rol' => 16, 'nombre_rol' => 'Jefe de Contabilidad',           'nivel' => 7, 'id_jerarquia' => 16],
            ['id_rol' => 17, 'nombre_rol' => 'Contabilidad',                   'nivel' => 8, 'id_jerarquia' => 17],
        ];

        $organizaciones = ['Google', 'Facebook'];

        foreach ($organizaciones as $org) {
            foreach ($roles as $rol) {
                // Código de negocio del rol: 1..17 en Google, 101..117 en Facebook
                $codigoRol = ($org === 'Facebook')
                    ? (string) ($rol['id_rol'] + 100)
                    : (string) $rol['id_rol'];

                // Código de negocio de la jerarquía: mismo esquema que JerarquiaInicialSeeder
                $codigoJerarquia = ($org === 'Facebook')
                    ? (string) ($rol['id_jerarquia'] + 100)
                    : (string) $rol['id_jerarquia'];

                // CAMBIO CLAVE: roles.id_jerarquia es FK hacia jerarquia_inicial.id (PK),
                // así que buscamos el registro por su código + organización y usamos ->id
                $jerarquiaRegistro = JerarquiaInicial::where('id_jerarquia', $codigoJerarquia)
                    ->where('id_organizacion', $org)
                    ->first();

                if (!$jerarquiaRegistro) {
                    // Si esto se dispara, revisa que JerarquiaInicialSeeder corrió antes
                    // y que los códigos coinciden.
                    throw new \RuntimeException("JerarquiaInicial no encontrada: codigo={$codigoJerarquia}, org={$org}");
                }

                Roles::create([
                    'id_rol'          => $codigoRol,
                    'nombre_rol'      => $rol['nombre_rol'],
                    'nivel'           => $rol['nivel'],
                    'id_organizacion' => $org,
                    'id_jerarquia'    => $jerarquiaRegistro->id, // CAMBIO: antes se guardaba el código plano
                ]);
            }
        }
    }
}
