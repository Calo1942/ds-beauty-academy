<?php

namespace DsBeautyAcademy\Models;

use Exception;
use DsBeautyAcademy\config\connect\DBConnect;
use DsBeautyAcademy\config\interfaces\Crud;
use DsBeautyAcademy\Helpers\ApiResponse;
use DsBeautyAcademy\Helpers\Validations;

class EntregaEvaluacionModel extends DBConnect implements Crud
{
    use ApiResponse, Validations;

    private $table = 'entrega_evaluaciones';
    private $idField = 'id_entrega_evaluacion';
    
    private $id_evaluacion;
    private $id_matricula;
    private $url_archivo_entrega;
    private $comentarios_estudiante;
    private $fecha_entrega;
    private $calificacion_entrega;
    private $retroalimentacion_instructor;
    private $estatus_entrega;

    private $module_name = [
        'singular' => 'Entrega de Evaluación',
        'plural' => 'Entregas de Evaluaciones'
    ];

    public $validate_estatus_entrega_opciones = ['Enviado', 'Calificado', 'Rechazado'];

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
        if (!empty($valor) || $valor === '0' || $valor === 0) {
            if (self::validator($valor, $this->validate_decimal) !== true) {
                throw new Exception("La calificación es inválida.");
            }
            $this->calificacion_entrega = $valor;
        } else {
            $this->calificacion_entrega = null;
        }
    }

    public function setRetroalimentacionInstructor($valor)
    {
        if (!empty($valor)) {
            if (self::validator($valor, $this->validate_description) !== true) {
                throw new Exception("La retroalimentación es inválida.");
            }
            $this->retroalimentacion_instructor = $valor;
        } else {
            $this->retroalimentacion_instructor = null;
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
            $stmt = $this->con->query("SELECT * FROM {$this->table}");
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

            // Hard delete as no specific soft delete column is designated for simple deletion
            $sql = "DELETE FROM {$this->table} WHERE {$this->idField} = ?";
            $stmt = $this->con->prepare($sql);
            if ($stmt->execute([$id])) {
                return self::success(200, "{$this->module_name['singular']} eliminada");
            }
            throw new Exception('Error al eliminar');
        } catch (Exception $e) {
            return self::error(500, 'Error al eliminar', $e->getMessage());
        }
    }
}
