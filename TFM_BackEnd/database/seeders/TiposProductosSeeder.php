<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TiposProductos;

class TiposProductosSeeder extends Seeder
{
    public function run(): void
    {
        $productos = [
            // Hardware
            [
                'id_producto'     => 'HW-001',
                'id_organizacion' => 'Google',
                'nombre_producto' => 'Laptop Dell XPS',
                'descripcion'     => 'Laptop de alto rendimiento para desarrollo',
            ],
            [
                'id_producto'     => 'HW-002',
                'id_organizacion' => 'Google',
                'nombre_producto' => 'Monitor 4K LG',
                'descripcion'     => 'Monitor 27 pulgadas resolución 4K',
            ],
            [
                'id_producto'     => 'HW-003',
                'id_organizacion' => 'Google',
                'nombre_producto' => 'Teclado Mecánico Logitech',
                'descripcion'     => 'Teclado mecánico inalámbrico para programadores',
            ],
            [
                'id_producto'     => 'HW-004',
                'id_organizacion' => 'Google',
                'nombre_producto' => 'Headset Jabra Evolve',
                'descripcion'     => 'Auriculares profesionales con cancelación de ruido',
            ],

            // Software
            [
                'id_producto'     => 'SW-001',
                'id_organizacion' => 'Google',
                'nombre_producto' => 'Licencia JetBrains',
                'descripcion'     => 'Suite completa de IDEs para desarrollo',
            ],
            [
                'id_producto'     => 'SW-002',
                'id_organizacion' => 'Google',
                'nombre_producto' => 'Licencia Adobe Creative Cloud',
                'descripcion'     => 'Suite de diseño y edición multimedia',
            ],
            [
                'id_producto'     => 'SW-003',
                'id_organizacion' => 'Google',
                'nombre_producto' => 'Licencia Microsoft 365',
                'descripcion'     => 'Suite Office 365 con Teams, Word, Excel y PowerPoint',
            ],
            [
                'id_producto'     => 'SW-004',
                'id_organizacion' => 'Google',
                'nombre_producto' => 'Licencia Microsoft Visual Studio Enterprise',
                'descripcion'     => 'IDE completo para desarrollo .NET y Azure DevOps',
            ],
            [
                'id_producto'     => 'SW-005',
                'id_organizacion' => 'Google',
                'nombre_producto' => 'Licencia Windows 11 Pro',
                'descripcion'     => 'Sistema operativo Windows 11 versión profesional',
            ],
            [
                'id_producto'     => 'SW-006',
                'id_organizacion' => 'Google',
                'nombre_producto' => 'Licencia BrowserStack Automate',
                'descripcion'     => 'Plataforma de testing automatizado en navegadores reales',
            ],
            [
                'id_producto'     => 'SW-007',
                'id_organizacion' => 'Google',
                'nombre_producto' => 'Licencia BrowserStack Live',
                'descripcion'     => 'Testing manual en dispositivos y navegadores reales',
            ],

            // Servicios Cloud
            [
                'id_producto'     => 'SV-001',
                'id_organizacion' => 'Google',
                'nombre_producto' => 'Azure Virtual Machines',
                'descripcion'     => 'Máquinas virtuales en la nube de Microsoft Azure',
            ],
            [
                'id_producto'     => 'SV-002',
                'id_organizacion' => 'Google',
                'nombre_producto' => 'Azure DevOps',
                'descripcion'     => 'Herramientas CI/CD y gestión de proyectos en Azure',
            ],
            [
                'id_producto'     => 'SV-003',
                'id_organizacion' => 'Google',
                'nombre_producto' => 'Azure Kubernetes Service',
                'descripcion'     => 'Servicio gestionado de contenedores en Azure',
            ],
            [
                'id_producto'     => 'SV-004',
                'id_organizacion' => 'Google',
                'nombre_producto' => 'Azure SQL Database',
                'descripcion'     => 'Base de datos relacional gestionada en Azure',
            ],
            [
                'id_producto'     => 'SV-005',
                'id_organizacion' => 'Google',
                'nombre_producto' => 'Azure Active Directory',
                'descripcion'     => 'Servicio de identidad y acceso en la nube',
            ],

            // Servicios generales
            [
                'id_producto'     => 'SV-006',
                'id_organizacion' => 'Google',
                'nombre_producto' => 'Consultoría Externa TI',
                'descripcion'     => 'Servicio de consultoría especializada en tecnología',
            ],
            [
                'id_producto'     => 'SV-007',
                'id_organizacion' => 'Google',
                'nombre_producto' => 'Capacitación y Formación',
                'descripcion'     => 'Cursos y certificaciones para el equipo técnico',
            ],
        ];

        foreach ($productos as $producto) {
            TiposProductos::create($producto);
        }
    }
}
