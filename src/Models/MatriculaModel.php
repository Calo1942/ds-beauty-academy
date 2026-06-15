<?php

namespace DsBeautyAcademy\Models;

use Exception;
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
    private $id_curso;
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

    public function setIdCurso($valor)
    {
        if (self::validator($valor, $this->validate_id) !== true) {
            throw new Exception("El ID de curso es inválido.");
        }
        $this->id_curso = $valor;
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
        if (self::validator($valor, $this->validate_decimal) !== true) {
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

    public function guardar($data)
    {
        try {
            $validatedData = $this->validarYSetearDatos($data);

            if (empty($validatedData)) {
                throw new Exception('No hay datos válidos para guardar');
            }

            $columns = array_keys($validatedData);
            $placeholders = array_fill(0, count($validatedData), '?');
            $values = array_values($validatedData);

            $sql = "INSERT INTO {$this->table} (" . implode(', ', $columns) . ") 
                    VALUES (" . implode(', ', $placeholders) . ")";

            $stmt = $this->con->prepare($sql);

            if ($stmt->execute($values)) {
                return self::success(201, "{$this->module_name['singular']} creada exitosamente");
            }
            throw new Exception('Error al guardar');

        } catch (Exception $e) {
            return self::error(500, 'Error al almacenar', $e->getMessage());
        }
    }

    public function buscarTodos()
    {
        try {
            $stmt = $this->con->query("SELECT * FROM {$this->table} WHERE {$this->statusField} != 'Cancelado'");
            $result = $stmt->fetchAll();
            return self::success(200, "{$this->module_name['plural']} obtenidas", $result);
        } catch (Exception $e) {
            return self::error(500, 'Error al obtener', $e->getMessage());
        }
    }

    public function buscar($id)
    {
        try {
            $this->validarId($id);
            $stmt = $this->con->prepare("SELECT * FROM {$this->table} WHERE {$this->idField} = ?");
            $stmt->execute([$id]);
            $result = $stmt->fetch();
            return self::success(200, "{$this->module_name['singular']} obtenida", $result);
        } catch (Exception $e) {
            return self::error(500, 'Error al obtener', $e->getMessage());
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
            if ($stmt->execute($values)) {
                return self::success(200, "{$this->module_name['singular']} actualizada");
            }
            throw new Exception('Error al actualizar');

        } catch (Exception $e) {
            return self::error(500, 'Error al actualizar', $e->getMessage());
        }
    }

    public function eliminar($id)
    {
        try {
            $this->validarId($id);

            $sql = "UPDATE {$this->table} SET {$this->statusField} = 'Cancelado' WHERE {$this->idField} = ?";
            $stmt = $this->con->prepare($sql);
            if ($stmt->execute([$id])) {
                return self::success(200, "{$this->module_name['singular']} cancelada");
            }
            throw new Exception('Error al eliminar');
        } catch (Exception $e) {
            return self::error(500, 'Error al eliminar', $e->getMessage());
        }
    }
}
