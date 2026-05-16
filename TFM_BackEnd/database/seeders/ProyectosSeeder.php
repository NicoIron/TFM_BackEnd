<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Proyectos;

class ProyectosSeeder extends Seeder
{
    public function run(): void
    {
        $proyectos = [
            [
                'id_proyecto'     => 'PROJ-A',
                'id_organizacion' => 'Google',
                'nombre_proyecto' => 'Proyecto A',
                'descripcion'     => 'Proyecto de prueba A',
            ],
            [
                'id_proyecto'     => 'PROJ-B',
                'id_organizacion' => 'Google',
                'nombre_proyecto' => 'Proyecto B',
                'descripcion'     => 'Proyecto de prueba B',
            ],
        ];

        foreach ($proyectos as $proyecto) {
            Proyectos::create($proyecto);
        }
    }
}
