<?php

namespace DsBeautyAcademy\Models;

use DsBeautyAcademy\config\connect\DBConnect;
use DsBeautyAcademy\Helpers\ApiResponse;

class DashboardModel extends DBConnect
{
    use ApiResponse;

    /**
     * Retorna estadísticas simuladas para el dashboard
     */
    public function getStatistics()
    {
        return [
            'estudiantes_activos' => '2,150',
            'ingresos_mensuales' => '$12,450.00',
            'ingresos_tendencia' => 'up',
            'cursos_en_curso' => '28',
            'progreso_cursos' => 75, // Porcentaje
            'instructores_registrados' => '14'
        ];
    }

    /**
     * Retorna lista de matrículas recientes simuladas
     */
    public function getRecentEnrollments()
    {
        return [
            [
                'estudiante' => 'Alek Santana',
                'avatar' => 'https://ui-avatars.com/api/?name=Alek+Santana&background=random',
                'curso' => 'Beauty Academy',
                'fecha' => '22/06/2023',
                'estado' => 'Matrícula'
            ],
            [
                'estudiante' => 'Alek Santana',
                'avatar' => 'https://ui-avatars.com/api/?name=Alek+Santana&background=random',
                'curso' => 'Beauty Academy Am',
                'fecha' => '22/06/2022',
                'estado' => 'Matrícula'
            ],
            [
                'estudiante' => 'Alek Santana',
                'avatar' => 'https://ui-avatars.com/api/?name=Alek+Santana&background=random',
                'curso' => 'Beauty Academy',
                'fecha' => '05/06/2022',
                'estado' => 'Matrículas'
            ],
            [
                'estudiante' => 'Alek Santana',
                'avatar' => 'https://ui-avatars.com/api/?name=Alek+Santana&background=random',
                'curso' => 'Beauty Academy',
                'fecha' => '08/08/2022',
                'estado' => 'Matrícula'
            ],
            [
                'estudiante' => 'Alek Santana',
                'avatar' => 'https://ui-avatars.com/api/?name=Alek+Santana&background=random',
                'curso' => 'Beauty Academy',
                'fecha' => '23/08/2022',
                'estado' => 'Matrículas'
            ],
            [
                'estudiante' => 'Alek Santana',
                'avatar' => 'https://ui-avatars.com/api/?name=Alek+Santana&background=random',
                'curso' => 'Beauty Academy',
                'fecha' => '22/08/2022',
                'estado' => 'Matrícula'
            ]
        ];
    }

    /**
     * Retorna próximos pagos simulados
     */
    public function getUpcomingPayments()
    {
        return [
            [
                'concepto' => 'Mejor de Pagos', // Tomado de la imagen literal
                'monto' => '$12,450.00',
                'tipo' => 'Apredora',
                'monto_tipo' => '$12,450.00'
            ],
            [
                'concepto' => 'Mejor de Pagos',
                'monto' => '',
                'tipo' => '',
                'monto_tipo' => '$2,850.00'
            ]
        ];
    }

    /**
     * Retorna las últimas certificaciones simuladas
     */
    public function getLatestCertifications()
    {
        return [
            [
                'nombre' => 'Alek Santana',
                'rol' => 'Student',
                'fecha' => '13/09/2023',
                'avatar' => 'https://ui-avatars.com/api/?name=Alek+Santana&background=random'
            ],
            [
                'nombre' => 'Alek Santana',
                'rol' => 'Student',
                'fecha' => '29/03/2022',
                'avatar' => 'https://ui-avatars.com/api/?name=Alek+Santana&background=random'
            ],
            [
                'nombre' => 'Alexsa Andanna',
                'rol' => 'Student',
                'fecha' => '24/06/2022',
                'avatar' => 'https://ui-avatars.com/api/?name=Alexsa+Andanna&background=random'
            ]
        ];
    }
}
