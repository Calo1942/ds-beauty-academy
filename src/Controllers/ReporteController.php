<?php

namespace DsBeautyAcademy\Controllers;

use DsBeautyAcademy\Models\ReporteModel;

$model = new ReporteModel();

// -------------------------------------------------------------
// 1. DESPACHO DE REPORTES EN FORMATO PDF
// -------------------------------------------------------------
if (isset($_GET['action']) && $_GET['action'] === 'pdf') {
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
