<?php

namespace DsBeautyAcademy\Models;

use DsBeautyAcademy\config\connect\DBConnect;
use DsBeautyAcademy\Helpers\FpdfTrait;
use PDO;
use Exception;

/**
 * ---------------------------------------------------------
 * Proyecto: DS Beauty Academy
 * Archivo: src/Models/ReporteModel.php
 * Descripción:
 * Modelo de datos para la generación de reportes contables,
 * financieros, admisiones y certificaciones académicas.
 * Incorpora el trait FpdfTrait para la compilación de documentos PDF.
 * ---------------------------------------------------------
 */
class ReporteModel extends DBConnect
{
    use FpdfTrait;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Obtiene el listado de bancos registrados y activos
     */
    public function obtenerBancos(): array
    {
        try {
            $stmt = $this->con->query("SELECT id_banco, nombre_banco FROM banco WHERE estatus_banco = 1 ORDER BY nombre_banco ASC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error en ReporteModel::obtenerBancos: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtiene el catálogo de cursos registrados
     */
    public function obtenerCursos(): array
    {
        try {
            $stmt = $this->con->query("SELECT id_curso, nombre_curso FROM cursos WHERE estatus_curso = 1 ORDER BY nombre_curso ASC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error en ReporteModel::obtenerCursos: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Reporte Financiero de Pagos y Conciliación Bancaria
     */
    public function obtenerReporteCaja(array $filtros): array
    {
        try {
            $sql = "
                SELECT 
                    cp.id_comprobante_pago,
                    cp.referencia_transaccion,
                    cp.monto,
                    cp.fecha_pago,
                    cp.estatus_pago AS estatus_comprobante,
                    b.nombre_banco,
                    CONCAT(e.primer_nombre_estudiante, ' ', e.primer_apellido_estudiante) AS nombre_estudiante,
                    e.cedula_estudiante,
                    c.nombre_curso,
                    m.id_matricula,
                    m.estatus_pago AS estatus_matricula
                FROM comprobante_pagos cp
                INNER JOIN matriculas m ON cp.id_matricula = m.id_matricula
                INNER JOIN estudiantes e ON m.id_estudiante = e.id_estudiante
                INNER JOIN banco b ON cp.id_banco = b.id_banco
                INNER JOIN instructor_curso ic ON m.id_instructor_curso = ic.id_instructor_curso
                INNER JOIN cursos c ON ic.id_curso = c.id_curso
                WHERE 1 = 1
            ";

            $params = [];

            if (!empty($filtros['fecha_desde'])) {
                $sql .= " AND DATE(cp.fecha_pago) >= :fecha_desde";
                $params[':fecha_desde'] = $filtros['fecha_desde'];
            }

            if (!empty($filtros['fecha_hasta'])) {
                $sql .= " AND DATE(cp.fecha_pago) <= :fecha_hasta";
                $params[':fecha_hasta'] = $filtros['fecha_hasta'];
            }

            if (!empty($filtros['id_banco'])) {
                $sql .= " AND cp.id_banco = :id_banco";
                $params[':id_banco'] = $filtros['id_banco'];
            }

            if (!empty($filtros['estatus_comprobante'])) {
                $sql .= " AND cp.estatus_pago = :estatus";
                $params[':estatus'] = $filtros['estatus_comprobante'];
            }

            $sql .= " ORDER BY cp.fecha_pago DESC, cp.id_comprobante_pago DESC";

            $stmt = $this->con->prepare($sql);
            $stmt->execute($params);
            $filas = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Estadísticas resumidas
            $totalMonto = 0.0;
            $verificados = 0;
            $pendientes = 0;
            $rechazados = 0;

            foreach ($filas as $f) {
                if ($f['estatus_comprobante'] === 'Verificado') {
                    $totalMonto += (float) $f['monto'];
                    $verificados++;
                } elseif ($f['estatus_comprobante'] === 'Pendiente') {
                    $pendientes++;
                } elseif ($f['estatus_comprobante'] === 'Rechazado') {
                    $rechazados++;
                }
            }

            return [
                'filas' => $filas,
                'resumen' => [
                    'total_monto'       => $totalMonto,
                    'total_registros'   => count($filas),
                    'total_verificados' => $verificados,
                    'total_pendientes'  => $pendientes,
                    'total_rechazados'  => $rechazados
                ]
            ];

        } catch (Exception $e) {
            error_log("Error en ReporteModel::obtenerReporteCaja: " . $e->getMessage());
            return ['filas' => [], 'resumen' => ['total_monto' => 0, 'total_registros' => 0, 'total_verificados' => 0, 'total_pendientes' => 0, 'total_rechazados' => 0]];
        }
    }

    /**
     * Reporte Académico de Matrículas y Aforo
     */
    public function obtenerReporteMatriculas(array $filtros): array
    {
        try {
            $sql = "
                SELECT 
                    m.id_matricula,
                    m.precio_acordado,
                    m.estatus_pago,
                    m.estatus_cupo,
                    m.fecha_solicitud,
                    CONCAT(e.primer_nombre_estudiante, ' ', e.primer_apellido_estudiante) AS nombre_estudiante,
                    e.cedula_estudiante,
                    e.telefono_principal_estudiante AS telefono,
                    e.correo_estudiante,
                    c.nombre_curso,
                    CONCAT(i.primer_nombre_instructor, ' ', i.primer_apellido_instructor) AS nombre_instructor
                FROM matriculas m
                INNER JOIN estudiantes e ON m.id_estudiante = e.id_estudiante
                INNER JOIN instructor_curso ic ON m.id_instructor_curso = ic.id_instructor_curso
                INNER JOIN cursos c ON ic.id_curso = c.id_curso
                INNER JOIN instructores i ON ic.id_instructor = i.id_instructor
                WHERE 1 = 1
            ";

            $params = [];

            if (!empty($filtros['id_curso'])) {
                $sql .= " AND c.id_curso = :id_curso";
                $params[':id_curso'] = $filtros['id_curso'];
            }

            if (!empty($filtros['estatus_cupo'])) {
                $sql .= " AND m.estatus_cupo = :estatus_cupo";
                $params[':estatus_cupo'] = $filtros['estatus_cupo'];
            }

            if (!empty($filtros['estatus_pago'])) {
                $sql .= " AND m.estatus_pago = :estatus_pago";
                $params[':estatus_pago'] = $filtros['estatus_pago'];
            }

            $sql .= " ORDER BY c.nombre_curso ASC, m.id_matricula DESC";

            $stmt = $this->con->prepare($sql);
            $stmt->execute($params);
            $filas = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $solventes = 0;
            $deudoras = 0;
            $confirmadas = 0;
            $reservadas = 0;

            foreach ($filas as $f) {
                if ($f['estatus_pago'] === 'Solvente') $solventes++;
                if ($f['estatus_pago'] === 'Deudora') $deudoras++;
                if ($f['estatus_cupo'] === 'Confirmado') $confirmadas++;
                if ($f['estatus_cupo'] === 'Reservado') $reservadas++;
            }

            return [
                'filas' => $filas,
                'resumen' => [
                    'total'       => count($filas),
                    'solventes'   => $solventes,
                    'deudoras'    => $deudoras,
                    'confirmadas' => $confirmadas,
                    'reservadas'  => $reservadas
                ]
            ];

        } catch (Exception $e) {
            error_log("Error en ReporteModel::obtenerReporteMatriculas: " . $e->getMessage());
            return ['filas' => [], 'resumen' => ['total' => 0, 'solventes' => 0, 'deudoras' => 0, 'confirmadas' => 0, 'reservadas' => 0]];
        }
    }

    /**
     * Reporte de Certificados y Logros Académicos
     */
    public function obtenerReporteCertificados(array $filtros): array
    {
        try {
            $sql = "
                SELECT 
                    cer.id_certificado,
                    cer.titulo_certificado,
                    cer.categoria_certificado,
                    cer.estatus_certificado,
                    cer.fecha_creacion AS fecha_emision,
                    CONCAT(e.primer_nombre_estudiante, ' ', e.primer_apellido_estudiante) AS nombre_estudiante,
                    e.cedula_estudiante,
                    c.nombre_curso,
                    m.id_matricula
                FROM certificados cer
                INNER JOIN matriculas m ON cer.id_matricula = m.id_matricula
                INNER JOIN estudiantes e ON m.id_estudiante = e.id_estudiante
                INNER JOIN instructor_curso ic ON m.id_instructor_curso = ic.id_instructor_curso
                INNER JOIN cursos c ON ic.id_curso = c.id_curso
                WHERE cer.estatus_certificado != 'Anulado'
            ";

            $params = [];

            if (!empty($filtros['id_curso'])) {
                $sql .= " AND c.id_curso = :id_curso";
                $params[':id_curso'] = $filtros['id_curso'];
            }

            if (!empty($filtros['estatus_certificado'])) {
                $sql .= " AND cer.estatus_certificado = :estatus_certificado";
                $params[':estatus_certificado'] = $filtros['estatus_certificado'];
            }

            $sql .= " ORDER BY cer.id_certificado DESC";

            $stmt = $this->con->prepare($sql);
            $stmt->execute($params);
            $filas = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return [
                'filas' => $filas,
                'total' => count($filas)
            ];

        } catch (Exception $e) {
            error_log("Error en ReporteModel::obtenerReporteCertificados: " . $e->getMessage());
            return ['filas' => [], 'total' => 0];
        }
    }

    /**
     * Obtiene los datos detallados de un certificado individual para generar su PDF
     */
    public function obtenerDatosCertificadoIndividual(int $id_certificado)
    {
        try {
            $sql = "
                SELECT 
                    cer.id_certificado,
                    cer.titulo_certificado,
                    cer.descripcion_certificado,
                    cer.categoria_certificado,
                    cer.estatus_certificado,
                    cer.fecha_creacion AS fecha_emision,
                    CONCAT(e.primer_nombre_estudiante, ' ', e.primer_apellido_estudiante) AS nombre_estudiante,
                    e.cedula_estudiante,
                    c.nombre_curso,
                    c.descripcion_curso,
                    CONCAT(i.primer_nombre_instructor, ' ', i.primer_apellido_instructor) AS nombre_instructor
                FROM certificados cer
                INNER JOIN matriculas m ON cer.id_matricula = m.id_matricula
                INNER JOIN estudiantes e ON m.id_estudiante = e.id_estudiante
                INNER JOIN instructor_curso ic ON m.id_instructor_curso = ic.id_instructor_curso
                INNER JOIN cursos c ON ic.id_curso = c.id_curso
                LEFT JOIN instructores i ON ic.id_instructor = i.id_instructor
                WHERE cer.id_certificado = :id
                LIMIT 1
            ";
            $stmt = $this->con->prepare($sql);
            $stmt->bindValue(':id', $id_certificado, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error en ReporteModel::obtenerDatosCertificadoIndividual: " . $e->getMessage());
            return null;
        }
    }
}
