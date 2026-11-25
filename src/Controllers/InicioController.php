<?php

namespace DsBeautyAcademy\Controllers;

use DsBeautyAcademy\Models\CursoModel;
use DsBeautyAcademy\config\connect\DBConnect;
use PDO;

class InicioController extends DBConnect
{
    private $cursoModel;

    public function __construct()
    {
        parent::__construct();
        $this->cursoModel = new CursoModel();
    }

    /**
     * Obtiene todos los cursos disponibles con cálculo en tiempo real de cupos disponibles
     */
    public function obtenerCursosDisponibles()
    {
        try {
            $sql = "
                SELECT c.*, 
                       COUNT(CASE WHEN m.estatus_cupo != 'Cancelado' THEN m.id_matricula END) AS ocupados,
                       (c.limite_cupos - COUNT(CASE WHEN m.estatus_cupo != 'Cancelado' THEN m.id_matricula END)) AS cupos_disponibles,
                       CONCAT(i.primer_nombre_instructor, ' ', i.primer_apellido_instructor) AS nombre_instructor,
                       i.usuario_instagram_instructor
                FROM cursos c
                LEFT JOIN instructor_curso ic ON c.id_curso = ic.id_curso
                LEFT JOIN instructores i ON ic.id_instructor = i.id_instructor
                LEFT JOIN matriculas m ON ic.id_instructor_curso = m.id_instructor_curso
                WHERE c.estatus_curso IN ('Venta Normal', 'Preventa', 'Activo')
                GROUP BY c.id_curso, i.id_instructor
                ORDER BY c.id_curso ASC
            ";
            $stmt = $this->con->prepare($sql);
            $stmt->execute();
            $cursos = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Fallback si no hay instructores asociados aún
            if (empty($cursos)) {
                $sqlFallback = "
                    SELECT c.*, 
                           0 AS ocupados, 
                           c.limite_cupos AS cupos_disponibles, 
                           'Difenmar Sánchez' AS nombre_instructor,
                           '@difenmar_beauty' AS usuario_instagram_instructor
                    FROM cursos c
                    WHERE c.estatus_curso IN ('Venta Normal', 'Preventa', 'Activo')
                    ORDER BY c.id_curso ASC
                ";
                $stmtFallback = $this->con->prepare($sqlFallback);
                $stmtFallback->execute();
                $cursos = $stmtFallback->fetchAll(PDO::FETCH_ASSOC);
            }

            return $cursos;
        } catch (\Exception $e) {
            error_log("Error en obtenerCursosDisponibles: " . $e->getMessage());
            $all = $this->cursoModel->buscarTodos();
            return $all['data'] ?? [];
        }
    }

    public function index()
    {
        // Petición AJAX para obtener el detalle de un curso en el modal
        if (isset($_GET['ajax']) && $_GET['ajax'] === 'detalle_curso' && !empty($_GET['id'])) {
            header('Content-Type: application/json');
            $id = (int)$_GET['id'];
            try {
                $sql = "
                    SELECT c.*, 
                           COUNT(CASE WHEN m.estatus_cupo != 'Cancelado' THEN m.id_matricula END) AS ocupados,
                           (c.limite_cupos - COUNT(CASE WHEN m.estatus_cupo != 'Cancelado' THEN m.id_matricula END)) AS cupos_disponibles,
                           CONCAT(i.primer_nombre_instructor, ' ', i.primer_apellido_instructor) AS nombre_instructor,
                           i.usuario_instagram_instructor,
                           esp.nombre_especialidad
                    FROM cursos c
                    LEFT JOIN instructor_curso ic ON c.id_curso = ic.id_curso
                    LEFT JOIN instructores i ON ic.id_instructor = i.id_instructor
                    LEFT JOIN especialidades esp ON i.id_especialidad = esp.id_especialidad
                    LEFT JOIN matriculas m ON ic.id_instructor_curso = m.id_instructor_curso
                    WHERE c.id_curso = :id
                    GROUP BY c.id_curso, i.id_instructor, esp.id_especialidad
                    LIMIT 1
                ";
                $stmt = $this->con->prepare($sql);
                $stmt->bindValue(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
                $detalle = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($detalle) {
                    echo json_encode(['status' => 'success', 'data' => $detalle]);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Curso no encontrado']);
                }
            } catch (\Exception $e) {
                echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
            }
            exit;
        }

        $cursos = $this->obtenerCursosDisponibles();
        require_once __DIR__ . '/../views/inicio/inicio.php';
    }
}

// Ejecución
$controller = new InicioController();
$controller->index();
