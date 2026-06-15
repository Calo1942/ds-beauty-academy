<?php

namespace DsBeautyAcademy\Models;

use Exception;
use DsBeautyAcademy\config\connect\DBConnect;
use DsBeautyAcademy\config\interfaces\Crud;
use DsBeautyAcademy\Helpers\ApiResponse;
use DsBeautyAcademy\Helpers\Validations;

class ClaseModel extends DBConnect implements Crud
{
    use ApiResponse, Validations;

    private $table = 'clases';
    private $idField = 'id_clase';
    private $statusField = 'estatus_clase';
    
    private $titulo_clase;
    private $descripcion_clase;
    private $url_video_clase;
    private $orden_clase;
    private $estatus_clase;
    private $id_instructor_curso;

    private $module_name = [
        'singular' => 'Clase',
        'plural' => 'Clases'
    ];

    public function setTituloClase($valor)
    {
        if (self::validator($valor, $this->validate_text_long) !== true) {
            throw new Exception("El título de la clase es inválido.");
        }
        $this->titulo_clase = $valor;
    }

    public function setDescripcionClase($valor)
    {
        if (!empty($valor)) {
            if (self::validator($valor, $this->validate_description) !== true) {
                throw new Exception("La descripción de la clase es inválida.");
            }
            $this->descripcion_clase = $valor;
        } else {
            $this->descripcion_clase = null;
        }
    }

    public function setUrlVideoClase($valor)
    {
        if (empty($valor) || strlen($valor) > 512) {
            throw new Exception("La URL del video es inválida o muy larga.");
        }
        $this->url_video_clase = $valor;
    }

    public function setOrdenClase($valor)
    {
        if (self::validator($valor, $this->validate_number) !== true) {
            throw new Exception("El orden de la clase es inválido.");
        }
        $this->orden_clase = $valor;
    }

    public function setEstatusClase($valor)
    {
        if (self::validator($valor, $this->validate_boolean) !== true) {
            throw new Exception("El estatus de la clase es inválido.");
        }
        $this->estatus_clase = $valor;
    }

    public function setIdInstructorCurso($valor)
    {
        if (self::validator($valor, $this->validate_id) !== true) {
            throw new Exception("El ID de instructor-curso es inválido.");
        }
        $this->id_instructor_curso = $valor;
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
            $stmt = $this->con->query("SELECT * FROM {$this->table} WHERE {$this->statusField} = 1");
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

            $sql = "UPDATE {$this->table} SET {$this->statusField} = 0 WHERE {$this->idField} = ?";
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
