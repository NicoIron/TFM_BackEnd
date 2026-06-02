<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JerarquiaRol;

class JerarquiaRolSeeder extends Seeder
{
    public function run(): void
    {
        $jerarquiaRoles = [
            ['id_jerarquia' => 2,  'id_rol' => 2,  'id_rol_superior' => null], // Comite Operativo
            ['id_jerarquia' => 3,  'id_rol' => 3,  'id_rol_superior' => 2],    // Jefe TI → Comite Operativo
            ['id_jerarquia' => 4,  'id_rol' => 4,  'id_rol_superior' => 3],    // Lider Infraestructura → Jefe TI
            ['id_jerarquia' => 5,  'id_rol' => 5,  'id_rol_superior' => 3],    // Lider Developer → Jefe TI
            ['id_jerarquia' => 6,  'id_rol' => 6,  'id_rol_superior' => 3],    // Lider IA → Jefe TI
            ['id_jerarquia' => 7,  'id_rol' => 7,  'id_rol_superior' => 3],    // Lider Calidad → Jefe TI
            ['id_jerarquia' => 8,  'id_rol' => 8,  'id_rol_superior' => 3],    // Lider Customer Service → Jefe TI
            ['id_jerarquia' => 9,  'id_rol' => 9,  'id_rol_superior' => null], // Gerente Proyecto → flexible
            ['id_jerarquia' => 10, 'id_rol' => 10, 'id_rol_superior' => 9],    // Lider Tecnico → Gerente Proyecto
            ['id_jerarquia' => 11, 'id_rol' => 11, 'id_rol_superior' => 9],    // Lider Qa → Gerente Proyecto
            ['id_jerarquia' => 12, 'id_rol' => 12, 'id_rol_superior' => 10],   // Developer → Lider Tecnico
            ['id_jerarquia' => 13, 'id_rol' => 13, 'id_rol_superior' => 10],   // Automatizador → Lider Tecnico
            ['id_jerarquia' => 14, 'id_rol' => 14, 'id_rol_superior' => 11],   // Funcional → Lider Qa
            ['id_jerarquia' => 15, 'id_rol' => 15, 'id_rol_superior' => 11],   // Scrum → Lider Qa
            ['id_jerarquia' => 16, 'id_rol' => 16, 'id_rol_superior' => 2],    // Jefe Contabilidad → Comite Operativo
            ['id_jerarquia' => 17, 'id_rol' => 17, 'id_rol_superior' => 16],   // Contabilidad → Jefe Contabilidad
        ];

        foreach ($jerarquiaRoles as $jr) {
            JerarquiaRol::create($jr);
        }
    }
}
