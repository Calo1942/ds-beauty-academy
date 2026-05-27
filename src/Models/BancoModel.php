<?php

namespace DsBeautyAcademy\Models;

use Exception;
use DsBeautyAcademy\config\connect\DBConnect;
use DsBeautyAcademy\config\interfaces\Crud;
use DsBeautyAcademy\Helpers\ApiResponse;

class BancoModel extends DBConnect implements Crud
{
    use ApiResponse;

    protected $table = 'banco';
    protected $idField = 'id_banco';
    protected $fields = [
        'nombre' => 'validate_names',
        'estatus_banco' => 'validate_numbers',
        'fecha_creacion' => 'validate_dates',
        'fecha_actualizacion' => 'validate_dates',
    ];
    // Aplicar booleano modular
    protected $module_name = [
        'singular' => 'Banco',
        'plural' => 'Bancos'
    ];

    public function guardar($data)
    {
        try {
            $columns = [];
            $placeholders = [];
            $values = [];

            foreach ($this->fields as $field => $validation) {
                if (isset($data[$field])) {
                    if ($validation && !$this->$validation($data[$field])) {
                        throw new Exception("Campo $field inválido");
                    }
                    $columns[] = $field;
                    $placeholders[] = "?";
                    $values[] = $data[$field];
                }
            }

            $sql = "INSERT INTO {$this->table} (" . implode(', ', $columns) . ") 
                    VALUES (" . implode(', ', $placeholders) . ")";

            $stmt = $this->con->prepare($sql);
            if ($stmt->execute($values)) {
                return self::success(201, "{$this->module_name['singular']} creado exitosamente");
            }
            throw new Exception('Error al guardar');

        } catch (\Exception $e) {
            return self::error(500, 'Error al almacenar', $e->getMessage());
        }
    }

    public function buscarTodos()
    {
        try {
            $stmt = $this->con->query("SELECT * FROM {$this->table} WHERE estatus_banco = 1");
            $result = $stmt->fetchAll();
            return self::success(200, "{$this->module_name['plural']} obtenidos", $result);
        } catch (\Exception $e) {
            return self::error(500, 'Error al obtener', $e->getMessage());
        }
    }

    public function buscar($id)
    {
        try {
            $stmt = $this->con->prepare("SELECT * FROM {$this->table} WHERE {$this->idField} = ?");
            $stmt->execute([$id]);
            $result = $stmt->fetch();
            return self::success(200, "{$this->module_name['singular']} obtenido", $result);
        } catch (\Exception $e) {
            return self::error(500, 'Error al obtener', $e->getMessage());
        }
    }

    public function actualizar($id, $data)
    {
        try {
            $updates = [];
            $values = [];

            foreach ($this->fields as $field => $validation) {
                if (isset($data[$field])) {
                    if ($validation && !$this->$validation($data[$field])) {
                        throw new Exception("Campo $field inválido");
                    }
                    $updates[] = "$field = ?";
                    $values[] = $data[$field];
                }
            }

            $values[] = $id;
            $sql = "UPDATE {$this->table} SET " . implode(', ', $updates) . " 
                    WHERE {$this->idField} = ?";

            $stmt = $this->con->prepare($sql);
            if ($stmt->execute($values)) {
                return self::success(200, "{$this->module_name['singular']} actualizado");
            }
            throw new Exception('Error al actualizar');

        } catch (\Exception $e) {
            return self::error(500, 'Error al actualizar', $e->getMessage());
        }
    }

    public function eliminar($id)
    {
        try {
            $sql = "UPDATE {$this->table} SET estatus_banco = 0 WHERE {$this->idField} = ?";
            $stmt = $this->con->prepare($sql);
            if ($stmt->execute([$id])) {
                return self::success(200, "{$this->module_name['singular']} eliminado");
            }
            throw new Exception('Error al eliminar');
        } catch (\Exception $e) {
            return self::error(500, 'Error al eliminar', $e->getMessage());
        }
    }
}