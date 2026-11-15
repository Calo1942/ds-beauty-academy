<?php

namespace DsBeautyAcademy\Controllers;

use DsBeautyAcademy\Models\ReporteModel;

$model = new ReporteModel();

// -------------------------------------------------------------
// 1. DESPACHO DE REPORTES EN FORMATO PDF
// -------------------------------------------------------------
if (isset($_GET['action']) && in_array($_GET['action'], ['pdf', 'generar'])) {
    $tipo = $_GET['tipo'] ?? 'caja';

    switch ($tipo) {
        case 'caja':
            $filtros = [
                'fecha_desde'         => $_GET['fecha_desde'] ?? '',
                'fecha_hasta'         => $_GET['fecha_hasta'] ?? '',
                'id_banco'            => $_GET['id_banco'] ?? '',
                'estatus_comprobante' => $_GET['estatus_comprobante'] ?? ''
            ];

            $datos = $model->obtenerReporteCaja($filtros);

            $subtitulo = 'Auditoría y conciliación de movimientos de caja';
            $filtrosTxt = [];
            if (!empty($filtros['fecha_desde'])) $filtrosTxt[] = 'Desde: ' . $filtros['fecha_desde'];
            if (!empty($filtros['fecha_hasta'])) $filtrosTxt[] = 'Hasta: ' . $filtros['fecha_hasta'];
            if (!empty($filtros['estatus_comprobante'])) $filtrosTxt[] = 'Estatus: ' . $filtros['estatus_comprobante'];
            $filtrosStr = implode(' | ', $filtrosTxt);

            $pdf = $model->crearReportePdf('P', 'Reporte de Caja y Conciliación Bancaria', $subtitulo, $filtrosStr);
            $pdf->AddPage();

            // Bloque de Resumen KPI
            $pdf->SetFillColor(245, 245, 250);
            $pdf->SetDrawColor(220, 220, 235);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->SetTextColor(30, 30, 45);

            $res = $datos['resumen'];
            $pdf->Cell(48, 8, $pdf->utf(' Total Recaudado: $' . number_format($res['total_monto'], 2)), 1, 0, 'L', true);
            $pdf->Cell(48, 8, $pdf->utf(' Verificados: ' . $res['total_verificados']), 1, 0, 'L', true);
            $pdf->Cell(48, 8, $pdf->utf(' Pendientes: ' . $res['total_pendientes']), 1, 0, 'L', true);
            $pdf->Cell(52, 8, $pdf->utf(' Rechazados: ' . $res['total_rechazados']), 1, 1, 'L', true);
            $pdf->Ln(4);

            // Tabla
            $headers = ['Ref.', 'Estudiante', 'Curso', 'Banco', 'Monto ($)', 'Fecha', 'Estado'];
            $widths  = [26, 42, 42, 28, 20, 20, 18];
            $aligns  = ['L', 'L', 'L', 'L', 'R', 'C', 'C'];

            $pdf->renderTableHeader($headers, $widths, $aligns);

            if (empty($datos['filas'])) {
                $pdf->SetFont('Arial', 'I', 9);
                $pdf->SetTextColor(120, 120, 130);
                $pdf->Cell(array_sum($widths), 10, $pdf->utf('No se encontraron registros de pago con los criterios seleccionados.'), 1, 1, 'C');
            } else {
                foreach ($datos['filas'] as $idx => $f) {
                    $row = [
                        $f['referencia_transaccion'],
                        $f['nombre_estudiante'],
                        $f['nombre_curso'],
                        $f['nombre_banco'],
                        '$' . number_format($f['monto'], 2),
                        date('d/m/Y', strtotime($f['fecha_pago'])),
                        $f['estatus_comprobante']
                    ];
                    $pdf->renderTableRow($row, $widths, $aligns, ($idx % 2 === 0));
                }
            }

            $pdf->Output('I', 'Reporte_Caja_' . date('Ymd_His') . '.pdf');
            exit;

        case 'matriculas':
            $filtros = [
                'id_curso'     => $_GET['id_curso'] ?? '',
                'estatus_cupo' => $_GET['estatus_cupo'] ?? '',
                'estatus_pago' => $_GET['estatus_pago'] ?? ''
            ];

            $datos = $model->obtenerReporteMatriculas($filtros);

            $subtitulo = 'Control de aforo, admisiones y solvencias por curso';
            $filtrosTxt = [];
            if (!empty($filtros['estatus_cupo'])) $filtrosTxt[] = 'Cupo: ' . $filtros['estatus_cupo'];
            if (!empty($filtros['estatus_pago'])) $filtrosTxt[] = 'Pago: ' . $filtros['estatus_pago'];
            $filtrosStr = implode(' | ', $filtrosTxt);

            // Orientación apaisada (Landscape) para reporte de matrículas
            $pdf = $model->crearReportePdf('L', 'Reporte Académico de Matrículas y Aforo', $subtitulo, $filtrosStr);
            $pdf->AddPage();

            // Bloque de Resumen KPI
            $pdf->SetFillColor(245, 245, 250);
            $pdf->SetDrawColor(220, 220, 235);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->SetTextColor(30, 30, 45);

            $res = $datos['resumen'];
            $pdf->Cell(50, 8, $pdf->utf(' Total Inscritas: ' . $res['total']), 1, 0, 'L', true);
            $pdf->Cell(52, 8, $pdf->utf(' Solventes: ' . $res['solventes']), 1, 0, 'L', true);
            $pdf->Cell(52, 8, $pdf->utf(' Deudoras: ' . $res['deudoras']), 1, 0, 'L', true);
            $pdf->Cell(52, 8, $pdf->utf(' Confirmadas: ' . $res['confirmadas']), 1, 0, 'L', true);
            $pdf->Cell(53, 8, $pdf->utf(' Reservadas: ' . $res['reservadas']), 1, 1, 'L', true);
            $pdf->Ln(4);

            // Tabla horizontal
            $headers = ['ID', 'Estudiante', 'Cédula', 'Teléfono', 'Curso', 'Instructor', 'Precio ($)', 'Pago', 'Cupo'];
            $widths  = [12, 45, 24, 28, 48, 45, 20, 20, 17];
            $aligns  = ['C', 'L', 'C', 'C', 'L', 'L', 'R', 'C', 'C'];

            $pdf->renderTableHeader($headers, $widths, $aligns);

            if (empty($datos['filas'])) {
                $pdf->SetFont('Arial', 'I', 9);
                $pdf->SetTextColor(120, 120, 130);
                $pdf->Cell(array_sum($widths), 10, $pdf->utf('No se encontraron matrículas con los criterios seleccionados.'), 1, 1, 'C');
            } else {
                foreach ($datos['filas'] as $idx => $f) {
                    $row = [
                        '#' . $f['id_matricula'],
                        $f['nombre_estudiante'],
                        $f['cedula_estudiante'],
                        $f['telefono'],
                        $f['nombre_curso'],
                        $f['nombre_instructor'],
                        '$' . number_format($f['precio_acordado'], 2),
                        $f['estatus_pago'],
                        $f['estatus_cupo']
                    ];
                    $pdf->renderTableRow($row, $widths, $aligns, ($idx % 2 === 0));
                }
            }

            $pdf->Output('I', 'Reporte_Matriculas_' . date('Ymd_His') . '.pdf');
            exit;

        case 'certificados':
            $filtros = [
                'id_curso'            => $_GET['id_curso'] ?? '',
                'estatus_certificado' => $_GET['estatus_certificado'] ?? ''
            ];

            $datos = $model->obtenerReporteCertificados($filtros);

            $subtitulo = 'Registro histórico de certificaciones emitidas a estudiantes';
            $pdf = $model->crearReportePdf('P', 'Listado de Certificados Académicos', $subtitulo, '');
            $pdf->AddPage();

            $headers = ['ID', 'Estudiante', 'Cédula', 'Curso', 'Categoría', 'Estado'];
            $widths  = [14, 46, 26, 50, 32, 28];
            $aligns  = ['C', 'L', 'C', 'L', 'L', 'C'];

            $pdf->renderTableHeader($headers, $widths, $aligns);

            if (empty($datos['filas'])) {
                $pdf->SetFont('Arial', 'I', 9);
                $pdf->SetTextColor(120, 120, 130);
                $pdf->Cell(array_sum($widths), 10, $pdf->utf('No se encontraron certificados registrados.'), 1, 1, 'C');
            } else {
                foreach ($datos['filas'] as $idx => $f) {
                    $row = [
                        '#' . $f['id_certificado'],
                        $f['nombre_estudiante'],
                        $f['cedula_estudiante'],
                        $f['nombre_curso'],
                        $f['categoria_certificado'] ?: 'General',
                        $f['estatus_certificado']
                    ];
                    $pdf->renderTableRow($row, $widths, $aligns, ($idx % 2 === 0));
                }
            }

            $pdf->Output('I', 'Reporte_Certificados_' . date('Ymd_His') . '.pdf');
            exit;
        case 'estudiantes':
            $datos = $model->obtenerReporteEstudiantes();
            $pdf = $model->crearReportePdf('L', 'Reporte General de Estudiantes Registrados', 'Padrón académico oficial de alumnas matriculadas', '');
            $pdf->AddPage();

            $headers = ['ID', 'Estudiante', 'Cédula', 'Teléfono', 'Correo Electrónico', 'Dirección', 'Estado'];
            $widths  = [14, 52, 28, 30, 60, 56, 20];
            $aligns  = ['C', 'L', 'C', 'C', 'L', 'L', 'C'];

            $pdf->renderTableHeader($headers, $widths, $aligns);

            if (empty($datos)) {
                $pdf->SetFont('Arial', 'I', 9);
                $pdf->SetTextColor(120, 120, 130);
                $pdf->Cell(array_sum($widths), 10, $pdf->utf('No se encontraron estudiantes registrados.'), 1, 1, 'C');
            } else {
                foreach ($datos as $idx => $f) {
                    $row = [
                        '#' . $f['id_estudiante'],
                        $f['nombre_completo'],
                        $f['cedula_estudiante'] ?: 'N/A',
                        $f['telefono_estudiante'] ?: 'N/A',
                        $f['correo_estudiante'] ?: 'N/A',
                        $f['direccion_estudiante'] ?: 'N/A',
                        ($f['estatus_estudiante'] == 1 ? 'Activo' : 'Inactivo')
                    ];
                    $pdf->renderTableRow($row, $widths, $aligns, ($idx % 2 === 0));
                }
            }
            $pdf->Output('I', 'Reporte_Estudiantes_' . date('Ymd_His') . '.pdf');
            exit;

        case 'cursos':
            $datos = $model->obtenerReporteCursos();
            $pdf = $model->crearReportePdf('L', 'Catálogo Oficial de Cursos y Programas', 'Oferta académica y aranceles institucionales', '');
            $pdf->AddPage();

            $headers = ['ID', 'Nombre del Curso', 'Modalidad', 'Precio Reg.', 'Precio Prev.', 'Fecha Inicio', 'Fecha Fin', 'Cupos', 'Estado'];
            $widths  = [12, 65, 30, 26, 26, 28, 28, 20, 25];
            $aligns  = ['C', 'L', 'C', 'R', 'R', 'C', 'C', 'C', 'C'];

            $pdf->renderTableHeader($headers, $widths, $aligns);

            if (empty($datos)) {
                $pdf->SetFont('Arial', 'I', 9);
                $pdf->SetTextColor(120, 120, 130);
                $pdf->Cell(array_sum($widths), 10, $pdf->utf('No se encontraron cursos registrados.'), 1, 1, 'C');
            } else {
                foreach ($datos as $idx => $f) {
                    $row = [
                        '#' . $f['id_curso'],
                        $f['nombre_curso'],
                        $f['tipo_curso'] ?: 'Presencial',
                        '$' . number_format($f['precio_normal'], 2),
                        $f['precio_preventa'] ? '$' . number_format($f['precio_preventa'], 2) : 'N/A',
                        $f['fecha_inicio_curso'] ? date('d/m/Y', strtotime($f['fecha_inicio_curso'])) : 'N/A',
                        $f['fecha_fin_curso'] ? date('d/m/Y', strtotime($f['fecha_fin_curso'])) : 'N/A',
                        $f['limite_cupos'] ?: 'Ilimitado',
                        $f['estatus_curso']
                    ];
                    $pdf->renderTableRow($row, $widths, $aligns, ($idx % 2 === 0));
                }
            }
            $pdf->Output('I', 'Reporte_Cursos_' . date('Ymd_His') . '.pdf');
            exit;

        case 'instructores':
            $datos = $model->obtenerReporteInstructores();
            $pdf = $model->crearReportePdf('P', 'Nómina Oficial de Instructores y Facilitadores', 'Cuerpo docente y áreas de especialidad', '');
            $pdf->AddPage();

            $headers = ['ID', 'Instructor', 'Cédula', 'Teléfono', 'Especialidad', 'Estado'];
            $widths  = [12, 52, 26, 30, 48, 22];
            $aligns  = ['C', 'L', 'C', 'C', 'L', 'C'];

            $pdf->renderTableHeader($headers, $widths, $aligns);

            if (empty($datos)) {
                $pdf->SetFont('Arial', 'I', 9);
                $pdf->SetTextColor(120, 120, 130);
                $pdf->Cell(array_sum($widths), 10, $pdf->utf('No se encontraron instructores registrados.'), 1, 1, 'C');
            } else {
                foreach ($datos as $idx => $f) {
                    $row = [
                        '#' . $f['id_instructor'],
                        $f['nombre_completo'],
                        $f['cedula_instructor'] ?: 'N/A',
                        $f['telefono_instructor'] ?: 'N/A',
                        $f['nombre_especialidad'] ?: 'General',
                        ($f['estatus_instructor'] == 1 ? 'Activo' : 'Inactivo')
                    ];
                    $pdf->renderTableRow($row, $widths, $aligns, ($idx % 2 === 0));
                }
            }
            $pdf->Output('I', 'Reporte_Instructores_' . date('Ymd_His') . '.pdf');
            exit;

        case 'bancos':
            $datos = $model->obtenerReporteBancos();
            $pdf = $model->crearReportePdf('P', 'Reporte de Instituciones Bancarias', 'Cuentas y bancos autorizados para recaudación', '');
            $pdf->AddPage();

            $headers = ['ID', 'Nombre de la Entidad Bancaria', 'Estado Operativo'];
            $widths  = [25, 125, 40];
            $aligns  = ['C', 'L', 'C'];

            $pdf->renderTableHeader($headers, $widths, $aligns);

            if (empty($datos)) {
                $pdf->SetFont('Arial', 'I', 9);
                $pdf->SetTextColor(120, 120, 130);
                $pdf->Cell(array_sum($widths), 10, $pdf->utf('No se encontraron bancos registrados.'), 1, 1, 'C');
            } else {
                foreach ($datos as $idx => $f) {
                    $row = [
                        '#' . $f['id_banco'],
                        $f['nombre_banco'],
                        ($f['estatus_banco'] == 1 ? 'Activo' : 'Inactivo')
                    ];
                    $pdf->renderTableRow($row, $widths, $aligns, ($idx % 2 === 0));
                }
            }
            $pdf->Output('I', 'Reporte_Bancos_' . date('Ymd_His') . '.pdf');
            exit;

        case 'especialidades':
            $datos = $model->obtenerReporteEspecialidades();
            $pdf = $model->crearReportePdf('P', 'Catálogo de Especialidades y Áreas Técnicas', 'Líneas formativas impartidas en la academia', '');
            $pdf->AddPage();

            $headers = ['ID', 'Especialidad', 'Descripción', 'Estado'];
            $widths  = [18, 55, 87, 30];
            $aligns  = ['C', 'L', 'L', 'C'];

            $pdf->renderTableHeader($headers, $widths, $aligns);

            if (empty($datos)) {
                $pdf->SetFont('Arial', 'I', 9);
                $pdf->SetTextColor(120, 120, 130);
                $pdf->Cell(array_sum($widths), 10, $pdf->utf('No se encontraron especialidades registradas.'), 1, 1, 'C');
            } else {
                foreach ($datos as $idx => $f) {
                    $row = [
                        '#' . $f['id_especialidad'],
                        $f['nombre_especialidad'],
                        $f['descripcion_especialidad'] ?: 'Sin descripción',
                        ($f['estatus_especialidad'] == 1 ? 'Activo' : 'Inactivo')
                    ];
                    $pdf->renderTableRow($row, $widths, $aligns, ($idx % 2 === 0));
                }
            }
            $pdf->Output('I', 'Reporte_Especialidades_' . date('Ymd_His') . '.pdf');
            exit;

        case 'clases':
            $datos = $model->obtenerReporteClases();
            $pdf = $model->crearReportePdf('P', 'Malla y Programa de Clases', 'Listado estructurado de sesiones y lecciones por curso', '');
            $pdf->AddPage();

            $headers = ['ID', 'Título de la Lección', 'Curso', 'Instructor', 'Orden', 'Estado'];
            $widths  = [14, 55, 48, 43, 14, 16];
            $aligns  = ['C', 'L', 'L', 'L', 'C', 'C'];

            $pdf->renderTableHeader($headers, $widths, $aligns);

            if (empty($datos)) {
                $pdf->SetFont('Arial', 'I', 9);
                $pdf->SetTextColor(120, 120, 130);
                $pdf->Cell(array_sum($widths), 10, $pdf->utf('No se encontraron clases registradas.'), 1, 1, 'C');
            } else {
                foreach ($datos as $idx => $f) {
                    $row = [
                        '#' . $f['id_clase'],
                        $f['titulo_clase'],
                        $f['nombre_curso'] ?: 'N/A',
                        $f['nombre_instructor'] ?: 'N/A',
                        $f['orden_clase'] ?: '1',
                        ($f['estatus_clase'] == 1 ? 'Activo' : 'Inactivo')
                    ];
                    $pdf->renderTableRow($row, $widths, $aligns, ($idx % 2 === 0));
                }
            }
            $pdf->Output('I', 'Reporte_Clases_' . date('Ymd_His') . '.pdf');
            exit;

        case 'recursos':
            $datos = $model->obtenerReporteRecursos();
            $pdf = $model->crearReportePdf('P', 'Inventario de Material Didáctico y Recursos', 'Guías, materiales y recursos de apoyo educativo', '');
            $pdf->AddPage();

            $headers = ['ID', 'Nombre del Recurso', 'Descripción', 'Archivo / Enlace'];
            $widths  = [15, 60, 65, 50];
            $aligns  = ['C', 'L', 'L', 'L'];

            $pdf->renderTableHeader($headers, $widths, $aligns);

            if (empty($datos)) {
                $pdf->SetFont('Arial', 'I', 9);
                $pdf->SetTextColor(120, 120, 130);
                $pdf->Cell(array_sum($widths), 10, $pdf->utf('No se encontraron recursos registrados.'), 1, 1, 'C');
            } else {
                foreach ($datos as $idx => $f) {
                    $row = [
                        '#' . $f['id_recurso'],
                        $f['nombre_recurso'],
                        $f['descripcion_recurso'] ?: 'Sin descripción',
                        $f['url_archivo_recurso'] ?: 'Sin adjunto'
                    ];
                    $pdf->renderTableRow($row, $widths, $aligns, ($idx % 2 === 0));
                }
            }
            $pdf->Output('I', 'Reporte_Recursos_' . date('Ymd_His') . '.pdf');
            exit;

        case 'evaluaciones':
            $datos = $model->obtenerReporteEvaluaciones();
            $pdf = $model->crearReportePdf('P', 'Cronograma de Evaluaciones Académicas', 'Pruebas teóricas, prácticas y asignaciones', '');
            $pdf->AddPage();

            $headers = ['ID', 'Título de Evaluación', 'Curso', 'Tipo', 'Orden', 'Estado'];
            $widths  = [14, 60, 52, 32, 14, 18];
            $aligns  = ['C', 'L', 'L', 'C', 'C', 'C'];

            $pdf->renderTableHeader($headers, $widths, $aligns);

            if (empty($datos)) {
                $pdf->SetFont('Arial', 'I', 9);
                $pdf->SetTextColor(120, 120, 130);
                $pdf->Cell(array_sum($widths), 10, $pdf->utf('No se encontraron evaluaciones registradas.'), 1, 1, 'C');
            } else {
                foreach ($datos as $idx => $f) {
                    $row = [
                        '#' . $f['id_evaluacion'],
                        $f['titulo_evaluacion'],
                        $f['nombre_curso'] ?: 'N/A',
                        $f['tipo_evaluacion'] ?: 'Práctica',
                        $f['orden_evaluacion'] ?: '1',
                        ($f['estatus_evaluacion'] == 1 ? 'Activo' : 'Inactivo')
                    ];
                    $pdf->renderTableRow($row, $widths, $aligns, ($idx % 2 === 0));
                }
            }
            $pdf->Output('I', 'Reporte_Evaluaciones_' . date('Ymd_His') . '.pdf');
            exit;

        case 'entregas':
            $datos = $model->obtenerReporteEntregas();
            $pdf = $model->crearReportePdf('L', 'Reporte de Calificaciones y Entregas', 'Control de tareas y calificaciones por estudiante', '');
            $pdf->AddPage();

            $headers = ['ID', 'Estudiante', 'Evaluación', 'Curso', 'Nota', 'Estatus', 'Fecha'];
            $widths  = [14, 55, 60, 55, 24, 28, 24];
            $aligns  = ['C', 'L', 'L', 'L', 'C', 'C', 'C'];

            $pdf->renderTableHeader($headers, $widths, $aligns);

            if (empty($datos)) {
                $pdf->SetFont('Arial', 'I', 9);
                $pdf->SetTextColor(120, 120, 130);
                $pdf->Cell(array_sum($widths), 10, $pdf->utf('No se encontraron entregas registradas.'), 1, 1, 'C');
            } else {
                foreach ($datos as $idx => $f) {
                    $row = [
                        '#' . $f['id_entrega_evaluacion'],
                        $f['nombre_estudiante'] ?: 'N/A',
                        $f['titulo_evaluacion'] ?: 'N/A',
                        $f['nombre_curso'] ?: 'N/A',
                        $f['calificacion_entrega'] !== null ? number_format($f['calificacion_entrega'], 1) . ' pts' : 'Sin calificar',
                        $f['estatus_entrega'] ?: 'Pendiente',
                        $f['fecha_entrega'] ? date('d/m/Y', strtotime($f['fecha_entrega'])) : 'N/A'
                    ];
                    $pdf->renderTableRow($row, $widths, $aligns, ($idx % 2 === 0));
                }
            }
            $pdf->Output('I', 'Reporte_Entregas_' . date('Ymd_His') . '.pdf');
            exit;

        case 'diplomas':
            $datos = $model->obtenerReporteDiplomas();
            $pdf = $model->crearReportePdf('P', 'Catálogo de Plantillas y Diplomas', 'Títulos y credenciales emitidas por la institución', '');
            $pdf->AddPage();

            $headers = ['ID', 'Nombre del Diploma', 'Categoría', 'Instructor Titular', 'Estado'];
            $widths  = [15, 65, 40, 48, 22];
            $aligns  = ['C', 'L', 'L', 'L', 'C'];

            $pdf->renderTableHeader($headers, $widths, $aligns);

            if (empty($datos)) {
                $pdf->SetFont('Arial', 'I', 9);
                $pdf->SetTextColor(120, 120, 130);
                $pdf->Cell(array_sum($widths), 10, $pdf->utf('No se encontraron diplomas registrados.'), 1, 1, 'C');
            } else {
                foreach ($datos as $idx => $f) {
                    $row = [
                        '#' . $f['id_diploma'],
                        $f['nombre_diploma'],
                        $f['categoria_diploma'] ?: 'General',
                        $f['nombre_instructor'] ?: 'N/A',
                        ($f['estatus_diploma'] == 1 ? 'Activo' : 'Inactivo')
                    ];
                    $pdf->renderTableRow($row, $widths, $aligns, ($idx % 2 === 0));
                }
            }
            $pdf->Output('I', 'Reporte_Diplomas_' . date('Ymd_His') . '.pdf');
            exit;

        case 'usuarios':
            $datos = $model->obtenerReporteUsuarios();
            $pdf = $model->crearReportePdf('P', 'Auditoría de Cuentas y Accesos de Usuario', 'Registro de cuentas activas y perfiles asignados', '');
            $pdf->AddPage();

            $headers = ['ID', 'Correo Institucional / Login', 'Rol / Privilegio', 'Usuario Asociado', 'Estado'];
            $widths  = [15, 65, 38, 48, 24];
            $aligns  = ['C', 'L', 'C', 'L', 'C'];

            $pdf->renderTableHeader($headers, $widths, $aligns);

            if (empty($datos)) {
                $pdf->SetFont('Arial', 'I', 9);
                $pdf->SetTextColor(120, 120, 130);
                $pdf->Cell(array_sum($widths), 10, $pdf->utf('No se encontraron usuarios registrados.'), 1, 1, 'C');
            } else {
                foreach ($datos as $idx => $f) {
                    $row = [
                        '#' . $f['id_usuario'],
                        $f['email_usuario'],
                        $f['nombre_rol'] ?: 'Sin rol',
                        $f['nombre_asociado'] ?: 'Sistema',
                        ($f['estatus_usuario'] == 1 ? 'Activo' : 'Inactivo')
                    ];
                    $pdf->renderTableRow($row, $widths, $aligns, ($idx % 2 === 0));
                }
            }
            $pdf->Output('I', 'Reporte_Usuarios_' . date('Ymd_His') . '.pdf');
            exit;

    }
}

// -------------------------------------------------------------
// 2. GENERACIÓN INDIVIDUAL DE CERTIFICADO / DIPLOMA EN PDF
// -------------------------------------------------------------
if (isset($_GET['action']) && $_GET['action'] === 'certificado') {
    $id = (int) ($_GET['id'] ?? 0);
    if ($id > 0) {
        $datosCert = $model->obtenerDatosCertificadoIndividual($id);
        if ($datosCert) {
            $pdfCert = $model->crearDiplomaCertificadoPdf($datosCert);
            $pdfCert->Output('I', 'Certificado_' . $id . '.pdf');
            exit;
        }
    }
    echo "<h1>Certificado no encontrado</h1>";
    exit;
}

// -------------------------------------------------------------
// 3. RENDERIZACIÓN DE LA VISTA DEL MÓDULO DE REPORTES
// -------------------------------------------------------------
$bancos = $model->obtenerBancos();
$cursos = $model->obtenerCursos();

include __DIR__ . '/../views/reporte/reporte.php';
exit;
