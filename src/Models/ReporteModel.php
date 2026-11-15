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

    /**
     * Reporte General de Estudiantes
     */
    public function obtenerReporteEstudiantes(): array
    {
        try {
             = ->con->query("
                SELECT id_estudiante,
                       CONCAT(primer_nombre_estudiante, ' ', COALESCE(primer_apellido_estudiante, '')) AS nombre_completo,
                       cedula_estudiante, telefono_estudiante, correo_estudiante, direccion_estudiante,
                       estatus_estudiante
                FROM estudiantes
                ORDER BY id_estudiante DESC
            ");
            return ->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception ) {
            error_log('Error en obtenerReporteEstudiantes: ' . ->getMessage());
            return [];
        }
    }

    /**
     * Reporte General de Cursos
     */
    public function obtenerReporteCursos(): array
    {
        try {
             = ->con->query("
                SELECT id_curso, nombre_curso, tipo_curso, precio_normal, precio_preventa,
                       fecha_inicio_curso, fecha_fin_curso, limite_cupos, estatus_curso
                FROM cursos
                ORDER BY id_curso DESC
            ");
            return ->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception ) {
            error_log('Error en obtenerReporteCursos: ' . ->getMessage());
            return [];
        }
    }

    /**
     * Reporte General de Instructores
     */
    public function obtenerReporteInstructores(): array
    {
        try {
             = ->con->query("
                SELECT i.id_instructor,
                       CONCAT(i.primer_nombre_instructor, ' ', COALESCE(i.primer_apellido_instructor, '')) AS nombre_completo,
                       i.cedula_instructor, i.telefono_instructor, i.correo_instructor,
                       e.nombre_especialidad, i.estatus_instructor
                FROM instructores i
                LEFT JOIN especialidades e ON i.id_especialidad = e.id_especialidad
                ORDER BY i.id_instructor DESC
            ");
            return ->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception ) {
            error_log('Error en obtenerReporteInstructores: ' . ->getMessage());
            return [];
        }
    }

    /**
     * Reporte General de Especialidades
     */
    public function obtenerReporteEspecialidades(): array
    {
        try {
             = ->con->query("
                SELECT id_especialidad, nombre_especialidad, descripcion_especialidad, estatus_especialidad
                FROM especialidades
                ORDER BY id_especialidad DESC
            ");
            return ->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception ) {
            error_log('Error en obtenerReporteEspecialidades: ' . ->getMessage());
            return [];
        }
    }

    /**
     * Reporte General de Bancos
     */
    public function obtenerReporteBancos(): array
    {
        try {
             = ->con->query("
                SELECT id_banco, nombre_banco, estatus_banco
                FROM banco
                ORDER BY id_banco DESC
            ");
            return ->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception ) {
            error_log('Error en obtenerReporteBancos: ' . ->getMessage());
            return [];
        }
    }

    /**
     * Reporte General de Clases
     */
    public function obtenerReporteClases(): array
    {
        try {
             = ->con->query("
                SELECT cl.id_clase, cl.titulo_clase, cl.orden_clase, cl.estatus_clase,
                       c.nombre_curso,
                       CONCAT(i.primer_nombre_instructor, ' ', COALESCE(i.primer_apellido_instructor, '')) AS nombre_instructor
                FROM clases cl
                LEFT JOIN instructor_curso ic ON cl.id_instructor_curso = ic.id_instructor_curso
                LEFT JOIN cursos c ON ic.id_curso = c.id_curso
                LEFT JOIN instructores i ON ic.id_instructor = i.id_instructor
                ORDER BY cl.id_clase DESC
            ");
            return ->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception ) {
            error_log('Error en obtenerReporteClases: ' . ->getMessage());
            return [];
        }
    }

    /**
     * Reporte General de Recursos
     */
    public function obtenerReporteRecursos(): array
    {
        try {
             = ->con->query("
                SELECT id_recurso, nombre_recurso, descripcion_recurso, url_archivo_recurso
                FROM recursos
                ORDER BY id_recurso DESC
            ");
            return ->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception ) {
            error_log('Error en obtenerReporteRecursos: ' . ->getMessage());
            return [];
        }
    }

    /**
     * Reporte General de Evaluaciones
     */
    public function obtenerReporteEvaluaciones(): array
    {
        try {
             = ->con->query("
                SELECT ev.id_evaluacion, ev.titulo_evaluacion, ev.tipo_evaluacion, ev.orden_evaluacion, ev.estatus_evaluacion,
                       c.nombre_curso
                FROM evaluaciones ev
                LEFT JOIN instructor_curso ic ON ev.id_instructor_curso = ic.id_instructor_curso
                LEFT JOIN cursos c ON ic.id_curso = c.id_curso
                ORDER BY ev.id_evaluacion DESC
            ");
            return ->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception ) {
            error_log('Error en obtenerReporteEvaluaciones: ' . ->getMessage());
            return [];
        }
    }

    /**
     * Reporte General de Entregas de Evaluación
     */
    public function obtenerReporteEntregas(): array
    {
        try {
             = ->con->query("
                SELECT ee.id_entrega_evaluacion,
                       CONCAT(COALESCE(e.primer_nombre_estudiante, ''), ' ', COALESCE(e.primer_apellido_estudiante, '')) AS nombre_estudiante,
                       ev.titulo_evaluacion,
                       c.nombre_curso,
                       ee.calificacion_entrega,
                       ee.estatus_entrega,
                       ee.fecha_entrega
                FROM entregas_evaluaciones ee
                LEFT JOIN evaluaciones ev ON ee.id_evaluacion = ev.id_evaluacion
                LEFT JOIN matriculas m ON ee.id_matricula = m.id_matricula
                LEFT JOIN estudiantes e ON m.id_estudiante = e.id_estudiante
                LEFT JOIN instructor_curso ic ON m.id_instructor_curso = ic.id_instructor_curso
                LEFT JOIN cursos c ON ic.id_curso = c.id_curso
                ORDER BY ee.id_entrega_evaluacion DESC
            ");
            return ->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception ) {
            error_log('Error en obtenerReporteEntregas: ' . ->getMessage());
            return [];
        }
    }

    /**
     * Reporte General de Diplomas
     */
    public function obtenerReporteDiplomas(): array
    {
        try {
             = ->con->query("
                SELECT d.id_diploma, d.nombre_diploma, d.categoria_diploma, d.estatus_diploma,
                       CONCAT(i.primer_nombre_instructor, ' ', COALESCE(i.primer_apellido_instructor, '')) AS nombre_instructor
                FROM diplomas d
                LEFT JOIN instructores i ON d.id_instructor = i.id_instructor
                ORDER BY d.id_diploma DESC
            ");
            return ->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception ) {
            error_log('Error en obtenerReporteDiplomas: ' . ->getMessage());
            return [];
        }
    }

    /**
     * Reporte General de Usuarios
     */
    public function obtenerReporteUsuarios(): array
    {
        try {
             = ->con->query("
                SELECT u.id_usuario, u.email_usuario, r.nombre_rol, u.estatus_usuario,
                       CASE 
                           WHEN u.id_instructor IS NOT NULL THEN CONCAT(i.primer_nombre_instructor, ' ', COALESCE(i.primer_apellido_instructor, ''))
                           WHEN u.id_estudiante IS NOT NULL THEN CONCAT(e.primer_nombre_estudiante, ' ', COALESCE(e.primer_apellido_estudiante, ''))
                           ELSE 'Administrador'
                       END AS nombre_asociado
                FROM usuarios u
                LEFT JOIN roles r ON u.id_rol = r.id_rol
                LEFT JOIN instructores i ON u.id_instructor = i.id_instructor
                LEFT JOIN estudiantes e ON u.id_estudiante = e.id_estudiante
                ORDER BY u.id_usuario DESC
            ");
            return ->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception ) {
            error_log('Error en obtenerReporteUsuarios: ' . ->getMessage());
            return [];
        }
    }

}
