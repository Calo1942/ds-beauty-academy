<?php

namespace DsBeautyAcademy\Models;

use Exception;
use DsBeautyAcademy\config\connect\DBConnect;
use DsBeautyAcademy\config\interfaces\Crud;
use DsBeautyAcademy\Helpers\ApiResponse;
use DsBeautyAcademy\Helpers\Validations;

class InstructorModel extends DBConnect implements Crud
{
    use ApiResponse, Validations;

    private $table = 'instructores';
    private $idField = 'id_instructor';
    private $statusField = 'estatus_instructor';

    private $primer_nombre_instructor;
    private $segundo_nombre_instructor;
    private $primer_apellido_instructor;
    private $segundo_apellido_instructor;
    private $cedula_instructor;
    private $correo_instructor;
    private $telefono_principal_instructor;
    private $telefono_alternativo_instructor;
    private $usuario_instagram_instructor;
    private $estatus_instructor;

    private $module_name = [
        'singular' => 'Instructor',
        'plural' => 'Instructores'
    ];

    public $validate_estatus_activo_inactivo = ['activo', 'inactivo'];
    public $validate_usuario_instagram = '/^@?[A-Za-z0-9_.-]{2,50}$/';

    // --- Setters de Encapsulamiento ---
    public function setPrimerNombreInstructor($valor)
    {
        if (self::validator($valor, $this->validate_names) !== true) {
            throw new Exception("El primer nombre es inválido.");
        }
        $this->primer_nombre_instructor = $valor;
    }

    public function setSegundoNombreInstructor($valor)
    {
        if (!empty($valor)) {
            if (self::validator($valor, $this->validate_names) !== true) {
                throw new Exception("El segundo nombre es inválido.");
            }
            $this->segundo_nombre_instructor = $valor;
        } else {
            $this->segundo_nombre_instructor = null;
        }
    }

    public function setPrimerApellidoInstructor($valor)
    {
        if (self::validator($valor, $this->validate_names) !== true) {
            throw new Exception("El primer apellido es inválido.");
        }
        $this->primer_apellido_instructor = $valor;
    }

    public function setSegundoApellidoInstructor($valor)
    {
        if (!empty($valor)) {
            if (self::validator($valor, $this->validate_names) !== true) {
                throw new Exception("El segundo apellido es inválido.");
            }
            $this->segundo_apellido_instructor = $valor;
        } else {
            $this->segundo_apellido_instructor = null;
        }
    }

    public function setCedulaInstructor($valor)
    {
        if (self::validator($valor, $this->validate_cedula) !== true) {
            throw new Exception("La cédula es inválida.");
        }
        $this->cedula_instructor = $valor;
    }

    public function setCorreoInstructor($valor)
    {
        if (self::validator($valor, $this->validate_email) !== true) {
            throw new Exception("El correo es inválido.");
        }
        $this->correo_instructor = $valor;
    }

    public function setTelefonoPrincipalInstructor($valor)
    {
        if (self::validator($valor, $this->validate_telefono) !== true) {
            throw new Exception("El teléfono principal es inválido.");
        }
        $this->telefono_principal_instructor = $valor;
    }

    public function setTelefonoAlternativoInstructor($valor)
    {
        if (!empty($valor)) {
            if (self::validator($valor, $this->validate_telefono) !== true) {
                throw new Exception("El teléfono alternativo es inválido.");
            }
            $this->telefono_alternativo_instructor = $valor;
        } else {
            $this->telefono_alternativo_instructor = null;
        }
    }

    public function setUsuarioInstagramInstructor($valor)
    {
        if (!empty($valor)) {
            if (self::validator($valor, $this->validate_usuario_instagram) !== true) {
                throw new Exception("El usuario de Instagram es inválido.");
            }
            $this->usuario_instagram_instructor = $valor;
        } else {
            $this->usuario_instagram_instructor = null;
        }
    }

    public function setEstatusInstructor($valor)
    {
        if (self::validator($valor, $this->validate_estatus_activo_inactivo) !== true) {
            throw new Exception("El estatus es inválido.");
        }
        $this->estatus_instructor = $valor;
    }

    // --- Método Validador Dinámico ---
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
                return self::success(201, "{$this->module_name['singular']} creado exitosamente");
            }
            throw new Exception('Error al guardar');

        } catch (Exception $e) {
            return self::error(500, 'Error al almacenar', $e->getMessage());
        }
    }

    public function buscarTodos()
    {
        try {
            $stmt = $this->con->query("SELECT * FROM {$this->table} WHERE {$this->statusField} = 'activo'");
            $result = $stmt->fetchAll();
            return self::success(200, "{$this->module_name['plural']} obtenidos", $result);
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
            return self::success(200, "{$this->module_name['singular']} obtenido", $result);
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
                return self::success(200, "{$this->module_name['singular']} actualizado");
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

            $sql = "UPDATE {$this->table} SET {$this->statusField} = 'inactivo' WHERE {$this->idField} = ?";
            $stmt = $this->con->prepare($sql);
            if ($stmt->execute([$id])) {
                return self::success(200, "{$this->module_name['singular']} eliminado");
            }
            throw new Exception('Error al eliminar');
        } catch (Exception $e) {
            return self::error(500, 'Error al eliminar', $e->getMessage());
        }
    }
}
