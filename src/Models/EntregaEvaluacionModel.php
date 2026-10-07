<?php

namespace DsBeautyAcademy\Models;

use Exception;
use PDO;
use PDOException;
use DsBeautyAcademy\config\connect\DBConnect;
use DsBeautyAcademy\config\interfaces\Crud;
use DsBeautyAcademy\Helpers\ApiResponse;
use DsBeautyAcademy\Helpers\Validations;

class EntregaEvaluacionModel extends DBConnect implements Crud
{
    use ApiResponse, Validations;

    private $table = 'entregas_evaluaciones';
    private $idField = 'id_entrega_evaluacion';
    
    private $id_evaluacion;
    private $id_matricula;
    private $url_archivo_entrega;
    private $comentarios_estudiante;
    private $calificacion_entrega;
    private $feedback_instructora_entrega;
    private $fecha_entrega;
    private $estatus_entrega;

    private $module_name = [
        'singular' => 'Entrega de Evaluación',
        'plural' => 'Entregas de Evaluaciones'
    ];

    public $validate_estatus_entrega_opciones = ['Pendiente', 'En Revisión', 'Repetir', 'Aprobada'];

    public function setIdEvaluacion($valor)
    {
        if (self::validator($valor, $this->validate_id) !== true) {
            throw new Exception("El ID de evaluación es inválido.");
        }
        $this->id_evaluacion = $valor;
    }

    public function setIdMatricula($valor)
    {
        if (self::validator($valor, $this->validate_id) !== true) {
            throw new Exception("El ID de matrícula es inválido.");
        }
        $this->id_matricula = $valor;
    }

    public function setUrlArchivoEntrega($valor)
    {
        if (!empty($valor)) {
            if (strlen($valor) > 512) {
                throw new Exception("La URL del archivo es muy larga.");
            }
            $this->url_archivo_entrega = $valor;
        } else {
            $this->url_archivo_entrega = null;
        }
    }

    public function setComentariosEstudiante($valor)
    {
        if (!empty($valor)) {
            if (self::validator($valor, $this->validate_description) !== true) {
                throw new Exception("Los comentarios son inválidos.");
            }
            $this->comentarios_estudiante = $valor;
        } else {
            $this->comentarios_estudiante = null;
        }
    }

    public function setFechaEntrega($valor)
    {
        if (!empty($valor)) {
            if (self::validator($valor, $this->validate_datetime) !== true && self::validator($valor, $this->validate_fecha) !== true) {
                throw new Exception("La fecha de entrega es inválida.");
            }
            $this->fecha_entrega = $valor;
        } else {
            $this->fecha_entrega = date('Y-m-d H:i:s');
        }
    }

    public function setCalificacionEntrega($valor)
    {
        if ($valor !== null && $valor !== '') {
            if (self::validator($valor, $this->validate_decimal) !== true) {
                throw new Exception("La calificación es inválida.");
            }
            $this->calificacion_entrega = $valor;
        } else {
            $this->calificacion_entrega = null;
        }
    }

    public function setFeedbackInstructoraEntrega($valor)
    {
        if (!empty($valor)) {
            if (self::validator($valor, $this->validate_description) !== true) {
                throw new Exception("El feedback es inválido.");
            }
            $this->feedback_instructora_entrega = $valor;
        } else {
            $this->feedback_instructora_entrega = null;
        }
    }

    public function setEstatusEntrega($valor)
    {
        if (self::validator($valor, $this->validate_estatus_entrega_opciones) !== true) {
            throw new Exception("El estatus de la entrega es inválido.");
        }
        $this->estatus_entrega = $valor;
    }

    private function validarYSetearDatos($data)
    {
        $validatedData = [];
        foreach ($data as $key => $value) {
            $methodName = 'set' . str_replace('_', '', ucwords($key, '_'));
            if (method_exists($this, $methodName)) {
                $this->$methodName($value);
                $validatedData[$key] = $this->$key;
            }
        }
        return $validatedData;
    }

    private function validarId($id)
    {
        if (self::validator($id, $this->validate_id) !== true) {
            throw new Exception("ID inválido");
        }
    }

    public function guardar($data)
    {
        try {
            $validatedData = $this->validarYSetearDatos($data);

            if (empty($validatedData)) {
                throw new Exception('No hay datos válidos para registrar la entrega.');
            }

            $this->con->beginTransaction();

            $columns = array_keys($validatedData);
            $placeholders = array_fill(0, count($validatedData), '?');
            $values = array_values($validatedData);

            $sql = "INSERT INTO {$this->table} (" . implode(', ', $columns) . ") 
                    VALUES (" . implode(', ', $placeholders) . ")";

            $stmt = $this->con->prepare($sql);
            $stmt->execute($values);
            $id = $this->con->lastInsertId();

            $this->con->commit();

            return self::success(201, "{$this->module_name['singular']} registrada exitosamente.", [
                'id_entrega_evaluacion' => $id
            ]);

        } catch (PDOException $e) {
            if ($this->con->inTransaction()) {
                $this->con->rollBack();
            }
            return self::error(400, "Error en entrega: " . $e->getMessage());
        } catch (Exception $e) {
            if ($this->con->inTransaction()) {
                $this->con->rollBack();
            }
            return self::error(400, $e->getMessage());
        }
    }

    /**
     * Calificación y Retroalimentación Transaccional
     * Cuando se aprueba la entrega, el trigger trg_entregas_completadas evalúa si se emite certificado
     */
    public function calificarEntrega($id, $calificacion, $feedback, $estatus)
    {
        try {
            $this->validarId($id);
            $this->setCalificacionEntrega($calificacion);
            $this->setFeedbackInstructoraEntrega($feedback);
            $this->setEstatusEntrega($estatus);

            $this->con->beginTransaction();

            $sql = "UPDATE {$this->table} SET 
                    calificacion_entrega = ?, 
                    feedback_instructora_entrega = ?, 
                    estatus_entrega = ?,
                    fecha_actualizacion = NOW() 
                    WHERE {$this->idField} = ?";

            $stmt = $this->con->prepare($sql);
            $stmt->execute([
                $this->calificacion_entrega,
                $this->feedback_instructora_entrega,
                $this->estatus_entrega,
                $id
            ]);

            $this->con->commit();

            return self::success(200, "Entrega calificada como '{$estatus}' exitosamente.");

        } catch (PDOException $e) {
            if ($this->con->inTransaction()) {
                $this->con->rollBack();
            }
            return self::error(400, "Error al calificar entrega: " . $e->getMessage());
        } catch (Exception $e) {
            if ($this->con->inTransaction()) {
                $this->con->rollBack();
            }
            return self::error(400, $e->getMessage());
        }
    }

    public function buscarTodos()
    {
        try {
            $sql = "SELECT ee.*, 
                           ev.titulo_evaluacion, 
                           ev.tipo_evaluacion,
                           ev.orden_evaluacion,
                           CONCAT(e.primer_nombre_estudiante, ' ', e.primer_apellido_estudiante) AS nombre_estudiante,
                           e.cedula_estudiante,
                           cu.nombre_curso
                    FROM {$this->table} ee
                    JOIN evaluaciones ev ON ee.id_evaluacion = ev.id_evaluacion
                    JOIN matriculas m ON ee.id_matricula = m.id_matricula
                    JOIN estudiantes e ON m.id_estudiante = e.id_estudiante
                    JOIN instructor_curso ic ON m.id_instructor_curso = ic.id_instructor_curso
                    JOIN cursos cu ON ic.id_curso = cu.id_curso
                    ORDER BY ee.id_entrega_evaluacion DESC";

            $stmt = $this->con->query($sql);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return self::success(200, "{$this->module_name['plural']} obtenidas", $result);
        } catch (Exception $e) {
            return self::error(500, 'Error al obtener', $e->getMessage());
        }
    }

    public function buscar($id)
    {
        try {
            $this->validarId($id);
            $sql = "SELECT ee.*, 
                           ev.titulo_evaluacion, 
                           ev.tipo_evaluacion,
                           CONCAT(e.primer_nombre_estudiante, ' ', e.primer_apellido_estudiante) AS nombre_estudiante,
                           e.cedula_estudiante,
                           cu.nombre_curso
                    FROM {$this->table} ee
                    JOIN evaluaciones ev ON ee.id_evaluacion = ev.id_evaluacion
                    JOIN matriculas m ON ee.id_matricula = m.id_matricula
                    JOIN estudiantes e ON m.id_estudiante = e.id_estudiante
                    JOIN instructor_curso ic ON m.id_instructor_curso = ic.id_instructor_curso
                    JOIN cursos cu ON ic.id_curso = cu.id_curso
                    WHERE ee.{$this->idField} = ?";

            $stmt = $this->con->prepare($sql);
            $stmt->execute([$id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$result) {
                throw new Exception("Entrega no encontrada");
            }
            return self::success(200, "{$this->module_name['singular']} obtenida", $result);
        } catch (Exception $e) {
            return self::error(400, $e->getMessage());
        }
    }

    public function actualizar($id, $data)
    {
        try {
            $this->validarId($id);
            $validatedData = $this->validarYSetearDatos($data);

            if (empty($validatedData)) {
                throw new Exception('No hay datos válidos para actualizar');
            }

            $this->con->beginTransaction();

            $updates = [];
            $values = [];

            foreach ($validatedData as $field => $value) {
                $updates[] = "$field = ?";
                $values[] = $value;
            }

            $values[] = $id;

            $sql = "UPDATE {$this->table} SET " . implode(', ', $updates) . " 
                    WHERE {$this->idField} = ?";

            $stmt = $this->con->prepare($sql);
            $stmt->execute($values);

            $this->con->commit();

            return self::success(200, "{$this->module_name['singular']} actualizada correctamente");

        } catch (PDOException $e) {
            if ($this->con->inTransaction()) {
                $this->con->rollBack();
            }
            return self::error(400, "Error al actualizar: " . $e->getMessage());
        } catch (Exception $e) {
            if ($this->con->inTransaction()) {
                $this->con->rollBack();
            }
            return self::error(400, $e->getMessage());
        }
    }

    public function eliminar($id)
    {
        try {
            $this->validarId($id);

            $this->con->beginTransaction();

            $sql = "DELETE FROM {$this->table} WHERE {$this->idField} = ?";
            $stmt = $this->con->prepare($sql);
            $stmt->execute([$id]);

            $this->con->commit();

            return self::success(200, "{$this->module_name['singular']} eliminada correctamente");
        } catch (PDOException $e) {
            if ($this->con->inTransaction()) {
                $this->con->rollBack();
            }
            return self::error(400, "Error al eliminar: " . $e->getMessage());
        } catch (Exception $e) {
            if ($this->con->inTransaction()) {
                $this->con->rollBack();
            }
            return self::error(400, $e->getMessage());
        }
    }
}
