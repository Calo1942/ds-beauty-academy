<?php

namespace DsBeautyAcademy\Models;

use Exception;
use PDO;
use PDOException;
use DsBeautyAcademy\config\connect\DBConnect;
use DsBeautyAcademy\config\interfaces\Crud;
use DsBeautyAcademy\Helpers\ApiResponse;
use DsBeautyAcademy\Helpers\Validations;

class InstructorCursoModel extends DBConnect implements Crud
{
    use ApiResponse, Validations;

    private $table = 'instructor_curso';
    private $idField = 'id_instructor_curso';

    private $id_instructor_curso;
    private $id_instructor;
    private $id_curso;

    private $module_name = [
        'singular' => 'Oferta Académica',
        'plural' => 'Ofertas Académicas'
    ];

    public function setIdInstructorCurso($valor)
    {
        if (self::validator($valor, $this->validate_id) !== true) {
            throw new Exception("El ID de oferta académica es inválido.");
        }
        $this->id_instructor_curso = $valor;
    }

    public function setIdInstructor($valor)
    {
        if (self::validator($valor, $this->validate_id) !== true) {
            throw new Exception("El ID del instructor es inválido.");
        }
        $this->id_instructor = $valor;
    }

    public function setIdCurso($valor)
    {
        if (self::validator($valor, $this->validate_id) !== true) {
            throw new Exception("El ID del curso es inválido.");
        }
        $this->id_curso = $valor;
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

    /**
     * Asignar un instructor a un curso
     */
    public function guardar($data)
    {
        try {
            $validatedData = $this->validarYSetearDatos($data);

            if (empty($validatedData['id_instructor']) || empty($validatedData['id_curso'])) {
                throw new Exception('Se requiere especificar tanto el instructor como el curso.');
            }

            // Verificar si la asignación ya existe
            $checkStmt = $this->con->prepare("SELECT id_instructor_curso FROM {$this->table} WHERE id_instructor = ? AND id_curso = ?");
            $checkStmt->execute([$this->id_instructor, $this->id_curso]);
            if ($checkStmt->fetch()) {
                throw new Exception('Esta asignación académica ya se encuentra registrada.');
            }

            $this->con->beginTransaction();

            $sql = "INSERT INTO {$this->table} (id_instructor, id_curso) VALUES (?, ?)";
            $stmt = $this->con->prepare($sql);
            $stmt->execute([$this->id_instructor, $this->id_curso]);

            $id_generado = $this->con->lastInsertId();

            $this->con->commit();

            return self::success(201, "{$this->module_name['singular']} asignada exitosamente.", [
                'id_instructor_curso' => $id_generado
            ]);

        } catch (PDOException $e) {
            if ($this->con->inTransaction()) {
                $this->con->rollBack();
            }
            $cleanMsg = $e->errorInfo[2] ?? $e->getMessage();
            return self::error(400, "Error al asignar: " . $cleanMsg);
        } catch (Exception $e) {
            if ($this->con->inTransaction()) {
                $this->con->rollBack();
            }
            return self::error(400, $e->getMessage());
        }
    }

    /**
     * Listar todas las asignaciones con los datos detallados del curso y del instructor
     */
    public function buscarTodos()
    {
        try {
            $sql = "SELECT ic.id_instructor_curso,
                           ic.id_instructor,
                           ic.id_curso,
                           ic.fecha_creacion,
                           ic.fecha_actualizacion,
                           c.nombre_curso,
                           c.tipo_curso,
                           c.precio_normal,
                           c.precio_preventa,
                           c.fecha_inicio_curso,
                           c.fecha_fin_preventa,
                           c.limite_cupos,
                           c.estatus_curso,
                           i.primer_nombre_instructor,
                           i.primer_apellido_instructor,
                           i.cedula_instructor,
                           i.correo_instructor,
                           i.estatus_instructor,
                           CONCAT(i.primer_nombre_instructor, ' ', i.primer_apellido_instructor) AS nombre_instructor
                    FROM {$this->table} ic
                    JOIN cursos c ON ic.id_curso = c.id_curso
                    JOIN instructores i ON ic.id_instructor = i.id_instructor
                    ORDER BY c.nombre_curso ASC, i.primer_nombre_instructor ASC";

            $stmt = $this->con->query($sql);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return self::success(200, "{$this->module_name['plural']} obtenidas", $result);
        } catch (Exception $e) {
            return self::error(500, 'Error al obtener asignaciones académicas', $e->getMessage());
        }
    }

    /**
     * Obtener una asignación específica por su ID
     */
    public function buscar($id)
    {
        try {
            $this->validarId($id);

            $sql = "SELECT ic.id_instructor_curso,
                           ic.id_instructor,
                           ic.id_curso,
                           ic.fecha_creacion,
                           ic.fecha_actualizacion,
                           c.nombre_curso,
                           c.tipo_curso,
                           c.precio_normal,
                           c.precio_preventa,
                           c.fecha_inicio_curso,
                           c.fecha_fin_preventa,
                           c.limite_cupos,
                           c.estatus_curso,
                           i.primer_nombre_instructor,
                           i.primer_apellido_instructor,
                           i.cedula_instructor,
                           i.correo_instructor,
                           i.estatus_instructor,
                           CONCAT(i.primer_nombre_instructor, ' ', i.primer_apellido_instructor) AS nombre_instructor
                    FROM {$this->table} ic
                    JOIN cursos c ON ic.id_curso = c.id_curso
                    JOIN instructores i ON ic.id_instructor = i.id_instructor
                    WHERE ic.{$this->idField} = ?";

            $stmt = $this->con->prepare($sql);
            $stmt->execute([$id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$result) {
                throw new Exception("Asignación académica no encontrada.");
            }

            return self::success(200, "{$this->module_name['singular']} obtenida", $result);
        } catch (Exception $e) {
            return self::error(400, $e->getMessage());
        }
    }

    /**
     * Actualizar asignación
     */
    public function actualizar($id, $data)
    {
        try {
            $this->validarId($id);
            $validatedData = $this->validarYSetearDatos($data);

            if (empty($validatedData)) {
                throw new Exception('No hay datos válidos para actualizar la asignación.');
            }

            $this->con->beginTransaction();

            $updates = [];
            $values = [];

            foreach ($validatedData as $field => $value) {
                $updates[] = "$field = ?";
                $values[] = $value;
            }

            $values[] = $id;

            $sql = "UPDATE {$this->table} SET " . implode(', ', $updates) . " WHERE {$this->idField} = ?";
            $stmt = $this->con->prepare($sql);
            $stmt->execute($values);

            $this->con->commit();

            return self::success(200, "{$this->module_name['singular']} actualizada correctamente.");

        } catch (PDOException $e) {
            if ($this->con->inTransaction()) {
                $this->con->rollBack();
            }
            $cleanMsg = $e->errorInfo[2] ?? $e->getMessage();
            return self::error(400, "Error al actualizar asignación: " . $cleanMsg);
        } catch (Exception $e) {
            if ($this->con->inTransaction()) {
                $this->con->rollBack();
            }
            return self::error(400, $e->getMessage());
        }
    }

    /**
     * Desvincular asignación de instructor a curso
     */
    public function eliminar($id)
    {
        try {
            $this->validarId($id);

            // Verificar si hay matrículas asociadas
            $checkStmt = $this->con->prepare("SELECT COUNT(*) FROM matriculas WHERE id_instructor_curso = ?");
            $checkStmt->execute([$id]);
            $matriculasCount = $checkStmt->fetchColumn();

            if ($matriculasCount > 0) {
                throw new Exception("No se puede desvincular la asignación porque existen {$matriculasCount} matrícula(s) asociadas a esta oferta académica.");
            }

            $this->con->beginTransaction();

            $sql = "DELETE FROM {$this->table} WHERE {$this->idField} = ?";
            $stmt = $this->con->prepare($sql);
            $stmt->execute([$id]);

            $this->con->commit();

            return self::success(200, "{$this->module_name['singular']} desvinculada correctamente.");

        } catch (PDOException $e) {
            if ($this->con->inTransaction()) {
                $this->con->rollBack();
            }
            $cleanMsg = $e->errorInfo[2] ?? $e->getMessage();
            return self::error(400, "Error al desvincular: " . $cleanMsg);
        } catch (Exception $e) {
            if ($this->con->inTransaction()) {
                $this->con->rollBack();
            }
            return self::error(400, $e->getMessage());
        }
    }
}
