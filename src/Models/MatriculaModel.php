<?php

namespace DsBeautyAcademy\Models;

use Exception;
use PDO;
use PDOException;
use DsBeautyAcademy\config\connect\DBConnect;
use DsBeautyAcademy\config\interfaces\Crud;
use DsBeautyAcademy\Helpers\ApiResponse;
use DsBeautyAcademy\Helpers\Validations;

class MatriculaModel extends DBConnect implements Crud
{
    use ApiResponse, Validations;

    private $table = 'matriculas';
    private $idField = 'id_matricula';
    private $statusField = 'estatus_cupo';
    
    private $id_estudiante;
    private $id_instructor_curso;
    private $fecha_solicitud;
    private $precio_acordado;
    private $estatus_pago;
    private $estatus_cupo;

    private $module_name = [
        'singular' => 'Matrícula',
        'plural' => 'Matrículas'
    ];

    public $validate_estatus_pago_opciones = ['Deudora', 'Solvente'];
    public $validate_estatus_cupo_opciones = ['Reservado', 'Confirmado', 'Cancelado'];

    public function setIdEstudiante($valor)
    {
        if (self::validator($valor, $this->validate_id) !== true) {
            throw new Exception("El ID de estudiante es inválido.");
        }
        $this->id_estudiante = $valor;
    }

    public function setIdInstructorCurso($valor)
    {
        if (self::validator($valor, $this->validate_id) !== true) {
            throw new Exception("El ID de instructor-curso es inválido.");
        }
        $this->id_instructor_curso = $valor;
    }

    public function setFechaSolicitud($valor)
    {
        if (!empty($valor)) {
            if (self::validator($valor, $this->validate_datetime) !== true && self::validator($valor, $this->validate_fecha) !== true) {
                throw new Exception("La fecha de solicitud es inválida.");
            }
            $this->fecha_solicitud = $valor;
        } else {
            $this->fecha_solicitud = date('Y-m-d H:i:s');
        }
    }

    public function setPrecioAcordado($valor)
    {
        if (self::validator($valor, $this->validate_decimal) !== true || (float)$valor < 0) {
            throw new Exception("El precio acordado es inválido.");
        }
        $this->precio_acordado = $valor;
    }

    public function setEstatusPago($valor)
    {
        if (self::validator($valor, $this->validate_estatus_pago_opciones) !== true) {
            throw new Exception("El estatus de pago es inválido.");
        }
        $this->estatus_pago = $valor;
    }

    public function setEstatusCupo($valor)
    {
        if (self::validator($valor, $this->validate_estatus_cupo_opciones) !== true) {
            throw new Exception("El estatus de cupo es inválido.");
        }
        $this->estatus_cupo = $valor;
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
     * Registro Transaccional de Matrícula (ACID)
     * Ejecuta la inserción atómicamente y captura las reglas de aforo y duplicidad del motor
     */
    public function guardar($data)
    {
        try {
            $validatedData = $this->validarYSetearDatos($data);

            if (empty($validatedData)) {
                throw new Exception('No hay datos válidos para registrar la matrícula.');
            }

            // Iniciar transacción atómica
            $this->con->beginTransaction();

            $columns = array_keys($validatedData);
            $placeholders = array_fill(0, count($validatedData), '?');
            $values = array_values($validatedData);

            $sql = "INSERT INTO {$this->table} (" . implode(', ', $columns) . ") 
                    VALUES (" . implode(', ', $placeholders) . ")";

            $stmt = $this->con->prepare($sql);
            $stmt->execute($values);

            $id_matricula = $this->con->lastInsertId();

            $this->con->commit();

            return self::success(201, "{$this->module_name['singular']} registrada exitosamente.", [
                'id_matricula' => $id_matricula
            ]);

        } catch (PDOException $e) {
            if ($this->con->inTransaction()) {
                $this->con->rollBack();
            }
            // Retornar mensaje limpio del trigger o de la restricción
            return self::error(400, $e->getMessage());
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
            $sql = "SELECT m.*, 
                           CONCAT(e.primer_nombre_estudiante, ' ', e.primer_apellido_estudiante) AS nombre_estudiante,
                           e.cedula_estudiante,
                           e.correo_estudiante,
                           c.id_curso,
                           c.nombre_curso,
                           CONCAT(i.primer_nombre_instructor, ' ', i.primer_apellido_instructor) AS nombre_instructor
                    FROM {$this->table} m
                    JOIN estudiantes e ON m.id_estudiante = e.id_estudiante
                    JOIN instructor_curso ic ON m.id_instructor_curso = ic.id_instructor_curso
                    JOIN cursos c ON ic.id_curso = c.id_curso
                    JOIN instructores i ON ic.id_instructor = i.id_instructor
                    ORDER BY m.id_matricula DESC";

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
            $sql = "SELECT m.*, 
                           CONCAT(e.primer_nombre_estudiante, ' ', e.primer_apellido_estudiante) AS nombre_estudiante,
                           e.cedula_estudiante,
                           e.correo_estudiante,
                           c.id_curso,
                           c.nombre_curso,
                           CONCAT(i.primer_nombre_instructor, ' ', i.primer_apellido_instructor) AS nombre_instructor
                    FROM {$this->table} m
                    JOIN estudiantes e ON m.id_estudiante = e.id_estudiante
                    JOIN instructor_curso ic ON m.id_instructor_curso = ic.id_instructor_curso
                    JOIN cursos c ON ic.id_curso = c.id_curso
                    JOIN instructores i ON ic.id_instructor = i.id_instructor
                    WHERE m.{$this->idField} = ?";

            $stmt = $this->con->prepare($sql);
            $stmt->execute([$id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$result) {
                throw new Exception("Matrícula no encontrada.");
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
                throw new Exception('No hay datos válidos para actualizar.');
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

            return self::success(200, "{$this->module_name['singular']} actualizada correctamente.");

        } catch (PDOException $e) {
            if ($this->con->inTransaction()) {
                $this->con->rollBack();
            }
            return self::error(400, $e->getMessage());
        } catch (Exception $e) {
            if ($this->con->inTransaction()) {
                $this->con->rollBack();
            }
            return self::error(400, $e->getMessage());
        }
    }

    /**
     * Soft Delete: Cancela el cupo de la matrícula para no destruir trazabilidad
     */
    public function eliminar($id)
    {
        try {
            $this->validarId($id);

            $this->con->beginTransaction();

            $sql = "UPDATE {$this->table} SET {$this->statusField} = 'Cancelado' WHERE {$this->idField} = ?";
            $stmt = $this->con->prepare($sql);
            $stmt->execute([$id]);

            $this->con->commit();

            return self::success(200, "{$this->module_name['singular']} cancelada correctamente.");
        } catch (PDOException $e) {
            if ($this->con->inTransaction()) {
                $this->con->rollBack();
            }
            return self::error(400, $e->getMessage());
        } catch (Exception $e) {
            if ($this->con->inTransaction()) {
                $this->con->rollBack();
            }
            return self::error(400, $e->getMessage());
        }
    }
}
