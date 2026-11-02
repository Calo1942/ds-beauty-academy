<?php

namespace DsBeautyAcademy\Helpers;

use FPDF;

/**
 * ---------------------------------------------------------
 * Proyecto: DS Beauty Academy
 * Archivo: src/Helpers/FpdfTrait.php
 * Descripción:
 * Trait para inyectar capacidades de generación de reportes
 * y diplomas PDF (FPDF) en los modelos del sistema (ReporteModel),
 * garantizando la ausencia de clases independientes no permitidas
 * por la normativa académica universitaria.
 * ---------------------------------------------------------
 */
trait FpdfTrait
{
    /**
     * Convierte cadenas UTF-8 a ISO-8859-1 para compatibilidad estricta con FPDF
     */
    public function utfPdf(string $texto): string
    {
        return iconv('UTF-8', 'windows-1252//TRANSLIT', $texto) ?: utf8_decode($texto);
    }

    /**
     * Construye una instancia de reporte institucional con cabecera y pie de página oficiales.
     * Utiliza una clase anónima interna para no registrar clases adicionales en el diagrama.
     */
    public function crearReportePdf(string $orientacion = 'P', string $titulo = 'Reporte Oficial', string $subtitulo = '', string $filtros = '')
    {
        return new class($orientacion, 'mm', 'Letter', $titulo, $subtitulo, $filtros) extends FPDF {
            private $tituloReporte;
            private $subtituloReporte;
            private $filtrosTexto;

            public function __construct($orientacion, $unidad, $tamano, $titulo, $subtitulo, $filtros)
            {
                parent::__construct($orientacion, $unidad, $tamano);
                $this->tituloReporte    = $titulo;
                $this->subtituloReporte = $subtitulo;
                $this->filtrosTexto     = $filtros;
                $this->AliasNbPages();
                $this->SetAutoPageBreak(true, 20);
            }

            public function utf(string $texto): string
            {
                return iconv('UTF-8', 'windows-1252//TRANSLIT', $texto) ?: utf8_decode($texto);
            }

            public function Header()
            {
                $pageWidth = $this->GetPageWidth();

                // 1. Barra superior Glow Pink (#E83382)
                $this->SetFillColor(232, 51, 130);
                $this->Rect(0, 0, $pageWidth, 5, 'F');

                // 2. Encabezado principal
                $this->SetY(10);
                $this->SetFont('Arial', 'B', 15);
                $this->SetTextColor(24, 24, 32);
                $this->Cell(0, 6, $this->utf('DS BEAUTY ACADEMY'), 0, 1, 'L');

                // 3. Título del reporte
                $this->SetFont('Arial', 'B', 11);
                $this->SetTextColor(232, 51, 130);
                $this->Cell(0, 5, $this->utf($this->tituloReporte), 0, 1, 'L');

                // 4. Subtítulo
                if (!empty($this->subtituloReporte)) {
                    $this->SetFont('Arial', '', 9);
                    $this->SetTextColor(110, 110, 125);
                    $this->Cell(0, 4.5, $this->utf($this->subtituloReporte), 0, 1, 'L');
                }

                // 5. Metadatos (Fecha y hora)
                $this->SetY(10);
                $this->SetFont('Arial', '', 8.5);
                $this->SetTextColor(120, 120, 135);
                $this->Cell(0, 5, $this->utf('Emisión: ') . date('d/m/Y - h:i A'), 0, 1, 'R');
                $this->Cell(0, 4.5, $this->utf('Sistema de Gestión Académica (ERP)'), 0, 1, 'R');

                // 6. Línea divisoria
                $this->Ln(3);
                $this->SetDrawColor(225, 225, 235);
                $this->SetLineWidth(0.3);
                $this->Line(10, $this->GetY(), $pageWidth - 10, $this->GetY());
                $this->Ln(4);

                // 7. Filtros aplicados
                if (!empty($this->filtrosTexto)) {
                    $this->SetFont('Arial', 'I', 8);
                    $this->SetTextColor(130, 130, 145);
                    $this->Cell(0, 4, $this->utf('Filtros aplicados: ' . $this->filtrosTexto), 0, 1, 'L');
                    $this->Ln(2);
                }
            }

            public function Footer()
            {
                $this->SetY(-15);
                $pageWidth = $this->GetPageWidth();

                $this->SetDrawColor(230, 230, 238);
                $this->Line(10, $this->GetY(), $pageWidth - 10, $this->GetY());

                $this->SetFont('Arial', 'I', 8);
                $this->SetTextColor(135, 135, 150);
                $this->Cell(0, 10, $this->utf('DS Beauty Academy • Confidencial y de uso administrativo'), 0, 0, 'L');
                $this->Cell(0, 10, $this->utf('Página ') . $this->PageNo() . ' de {nb}', 0, 0, 'R');
            }

            public function renderTableHeader(array $headers, array $widths, array $aligns = [])
            {
                $this->SetFillColor(30, 30, 42);
                $this->SetTextColor(255, 255, 255);
                $this->SetDrawColor(45, 45, 60);
                $this->SetFont('Arial', 'B', 8.5);

                foreach ($headers as $i => $title) {
                    $w = $widths[$i];
                    $a = $aligns[$i] ?? 'L';
                    $this->Cell($w, 7, $this->utf($title), 1, 0, $a, true);
                }
                $this->Ln();
            }

            public function renderTableRow(array $row, array $widths, array $aligns = [], bool $isEven = false)
            {
                $this->SetFillColor($isEven ? 248 : 255, $isEven ? 248 : 255, $isEven ? 252 : 255);
                $this->SetTextColor(40, 40, 50);
                $this->SetDrawColor(230, 230, 240);
                $this->SetFont('Arial', '', 8);

                foreach ($row as $i => $val) {
                    $w = $widths[$i];
                    $a = $aligns[$i] ?? 'L';
                    $this->Cell($w, 6, $this->utf((string)$val), 1, 0, $a, true);
                }
                $this->Ln();
            }
        };
    }

    /**
     * Construye un diploma/certificado oficial en orientación apaisada con marco ornamental.
     */
    public function crearDiplomaCertificadoPdf(array $datos): FPDF
    {
        $pdf = new FPDF('L', 'mm', 'Letter');
        $pdf->SetAutoPageBreak(false);
        $pdf->AddPage();

        $w = $pdf->GetPageWidth();
        $h = $pdf->GetPageHeight();

        // 1. Marco ornamental perimetral
        $pdf->SetDrawColor(35, 35, 45);
        $pdf->SetLineWidth(0.8);
        $pdf->Rect(8, 8, $w - 16, $h - 16);

        $pdf->SetDrawColor(232, 51, 130);
        $pdf->SetLineWidth(1.5);
        $pdf->Rect(11, 11, $w - 22, $h - 22);

        $pdf->SetDrawColor(200, 200, 215);
        $pdf->SetLineWidth(0.4);
        $pdf->Rect(13, 13, $w - 26, $h - 26);

        $pdf->SetFillColor(232, 51, 130);
        $pdf->Rect(10, 10, 4, 4, 'F');
        $pdf->Rect($w - 14, 10, 4, 4, 'F');
        $pdf->Rect(10, $h - 14, 4, 4, 'F');
        $pdf->Rect($w - 14, $h - 14, 4, 4, 'F');

        // 2. Cabecera Institucional
        $pdf->SetY(20);
        $pdf->SetFont('Arial', '', 10);
        $pdf->SetTextColor(120, 120, 135);
        $pdf->Cell(0, 5, $this->utfPdf('REPÚBLICA BOLIVARIANA DE VENEZUELA'), 0, 1, 'C');

        $pdf->SetFont('Arial', 'B', 22);
        $pdf->SetTextColor(24, 24, 32);
        $pdf->Cell(0, 9, $this->utfPdf('DS BEAUTY ACADEMY'), 0, 1, 'C');

        $pdf->SetFont('Arial', 'I', 9.5);
        $pdf->SetTextColor(100, 100, 115);
        $pdf->Cell(0, 5, $this->utfPdf('CENTRO INTEGRAL DE FORMACIÓN Y ESPECIALIZACIÓN EN ESTÉTICA PROFESIONAL'), 0, 1, 'C');

        $pdf->Ln(3);
        $pdf->SetDrawColor(232, 51, 130);
        $pdf->SetLineWidth(0.8);
        $pdf->Line($w / 2 - 40, $pdf->GetY(), $w / 2 + 40, $pdf->GetY());
        $pdf->Ln(4);

        // 3. Título del Documento
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->SetTextColor(232, 51, 130);
        $pdf->Cell(0, 7, $this->utfPdf('CONFIERE EL PRESENTE'), 0, 1, 'C');

        $pdf->SetFont('Arial', 'B', 20);
        $pdf->SetTextColor(20, 20, 28);
        $pdf->Cell(0, 9, $this->utfPdf('CERTIFICADO DE APROBACIÓN'), 0, 1, 'C');
        $pdf->Ln(3);

        // 4. Presentación del Alumno
        $pdf->SetFont('Arial', 'I', 11);
        $pdf->SetTextColor(90, 90, 105);
        $pdf->Cell(0, 5, $this->utfPdf('A favor de:'), 0, 1, 'C');

        $nombreAlumno = mb_strtoupper($datos['nombre_estudiante'] ?? 'Estudiante de Belleza');
        $pdf->SetFont('Arial', 'B', 20);
        $pdf->SetTextColor(232, 51, 130);
        $pdf->Cell(0, 10, $this->utfPdf($nombreAlumno), 0, 1, 'C');

        if (!empty($datos['cedula_estudiante'])) {
            $pdf->SetFont('Arial', '', 10);
            $pdf->SetTextColor(80, 80, 95);
            $pdf->Cell(0, 5, $this->utfPdf('Titular de la Cédula de Identidad: ') . $datos['cedula_estudiante'], 0, 1, 'C');
        }

        $pdf->Ln(3);

        // 5. Texto de Acreditación
        $pdf->SetFont('Arial', '', 10.5);
        $pdf->SetTextColor(60, 60, 75);
        $textoCuerpo = 'Por haber completado y aprobado satisfactoriamente todos los módulos teórico-prácticos correspondientes al programa de formación profesional en:';
        $pdf->SetX(30);
        $pdf->MultiCell($w - 60, 5.5, $this->utfPdf($textoCuerpo), 0, 'C');

        $pdf->Ln(2);
        $nombreCurso = mb_strtoupper($datos['nombre_curso'] ?? 'Curso Profesional');
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->SetTextColor(24, 24, 32);
        $pdf->Cell(0, 7, $this->utfPdf('"' . $nombreCurso . '"'), 0, 1, 'C');

        if (!empty($datos['descripcion_certificado'])) {
            $pdf->SetFont('Arial', 'I', 9);
            $pdf->SetTextColor(110, 110, 125);
            $pdf->SetX(40);
            $pdf->MultiCell($w - 80, 4.5, $this->utfPdf($datos['descripcion_certificado']), 0, 'C');
        }

        // 6. Fecha formal
        $pdf->Ln(4);
        $pdf->SetFont('Arial', '', 9.5);
        $pdf->SetTextColor(80, 80, 95);
        $meses = [1 => 'enero', 2 => 'febrero', 3 => 'marzo', 4 => 'abril', 5 => 'mayo', 6 => 'junio', 7 => 'julio', 8 => 'agosto', 9 => 'septiembre', 10 => 'octubre', 11 => 'noviembre', 12 => 'diciembre'];
        $mesTxt = $meses[(int)date('n')] ?? 'enero';
        $fechaTxt = 'Emitido formalmente a los ' . date('d') . ' días del mes de ' . $mesTxt . ' de ' . date('Y') . '.';
        $pdf->Cell(0, 5, $this->utfPdf($fechaTxt), 0, 1, 'C');

        // 7. Firmas
        $pdf->SetY($h - 46);
        $colWidth = 70;
        $x1 = ($w / 2) - $colWidth - 15;
        $x2 = ($w / 2) + 15;

        $pdf->SetDrawColor(150, 150, 165);
        $pdf->SetLineWidth(0.4);
        $pdf->Line($x1, $pdf->GetY() + 10, $x1 + $colWidth, $pdf->GetY() + 10);
        $pdf->Line($x2, $pdf->GetY() + 10, $x2 + $colWidth, $pdf->GetY() + 10);

        $pdf->SetY($pdf->GetY() + 12);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetTextColor(40, 40, 50);

        $instructorNombre = $datos['nombre_instructor'] ?? 'Instructora Especialista';
        $pdf->SetX($x1);
        $pdf->Cell($colWidth, 4, $this->utfPdf($instructorNombre), 0, 0, 'C');

        $pdf->SetX($x2);
        $pdf->Cell($colWidth, 4, $this->utfPdf('Dilenmar Sánchez'), 0, 1, 'C');

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetTextColor(120, 120, 135);
        $pdf->SetX($x1);
        $pdf->Cell($colWidth, 3.5, $this->utfPdf('Docente / Máster Certificada'), 0, 0, 'C');

        $pdf->SetX($x2);
        $pdf->Cell($colWidth, 3.5, $this->utfPdf('Dirección Académica General'), 0, 1, 'C');

        // 8. Serial único de verificación
        $idCertificado = $datos['id_certificado'] ?? 1;
        $serial = sprintf('DSBA-%s-CERT-%05d', date('Y'), $idCertificado);

        $pdf->SetY($h - 17);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetTextColor(232, 51, 130);
        $pdf->Cell(0, 4, $this->utfPdf('Serial Único de Verificación Académica: ') . $serial, 0, 1, 'C');

        return $pdf;
    }
}
