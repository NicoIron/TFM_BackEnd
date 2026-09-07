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
            [
                'id_proyecto'     => 'PROJ-A-FB', // CAMBIO: revertido, antes 'PROJ-A' (violaba unique global)
                'id_organizacion' => 'Facebook',
                'nombre_proyecto' => 'Proyecto A Facebook',
                'descripcion'     => 'Demo FB A',
            ],
            [
                'id_proyecto'     => 'PROJ-B-FB', // CAMBIO: revertido, antes 'PROJ-B' (violaba unique global)
                'id_organizacion' => 'Facebook',
                'nombre_proyecto' => 'Proyecto B Facebook',
                'descripcion'     => 'Demo FB B',
            ],
        ];

        foreach ($proyectos as $proyecto) {
            Proyectos::create($proyecto);
        }
    }
}
