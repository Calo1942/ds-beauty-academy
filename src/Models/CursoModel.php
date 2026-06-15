<?php

namespace DsBeautyAcademy\Models;

use Exception;
use DsBeautyAcademy\config\connect\DBConnect;
use DsBeautyAcademy\config\interfaces\Crud;
use DsBeautyAcademy\Helpers\ApiResponse;
use DsBeautyAcademy\Helpers\Validations;

class CursoModel extends DBConnect implements Crud
{
    use ApiResponse, Validations;

    private $table = 'cursos';
    private $idField = 'id_curso';
    
    private $nombre_curso;
    private $descripcion_curso;
    private $tipo_curso;
    private $precio_preventa;
    private $precio_normal;
    private $fecha_fin_preventa;
    private $fecha_inicio_curso;
    private $fecha_fin_curso;
    private $limite_cupos;
    private $estatus_curso;

    private $module_name = [
        'singular' => 'Curso',
        'plural' => 'Cursos'
    ];

    public $validate_tipo_curso_opciones = ['Masterclass', 'Curso completo'];
    public $validate_estatus_curso_opciones = ['Borrador', 'Preventa', 'Venta Normal', 'Cerrado'];

    // --- Setters de Encapsulamiento ---
    public function setNombreCurso($valor)
    {
        if (self::validator($valor, $this->validate_text_long) !== true) {
            throw new Exception("El nombre del curso es inválido.");
        }
        $this->nombre_curso = $valor;
    }

    public function setDescripcionCurso($valor)
    {
        if (!empty($valor)) {
            if (self::validator($valor, $this->validate_description) !== true) {
                throw new Exception("La descripción del curso es inválida.");
            }
            $this->descripcion_curso = $valor;
        } else {
            $this->descripcion_curso = null;
        }
    }

    public function setTipoCurso($valor)
    {
        if (self::validator($valor, $this->validate_tipo_curso_opciones) !== true) {
            throw new Exception("El tipo de curso es inválido.");
        }
        $this->tipo_curso = $valor;
    }

    public function setPrecioPreventa($valor)
    {
        if (!empty($valor)) {
            if (self::validator($valor, $this->validate_decimal) !== true) {
                throw new Exception("El precio de preventa es inválido.");
            }
            $this->precio_preventa = $valor;
        } else {
            $this->precio_preventa = null;
        }
    }

    public function setPrecioNormal($valor)
    {
        if (self::validator($valor, $this->validate_decimal) !== true) {
            throw new Exception("El precio normal es inválido.");
        }
        $this->precio_normal = $valor;
    }

    public function setFechaFinPreventa($valor)
    {
        if (!empty($valor)) {
            // Se puede usar date() o strtotime() para mayor flexibilidad, usamos el validador básico
            if (self::validator($valor, $this->validate_datetime) !== true && self::validator($valor, $this->validate_fecha) !== true) {
                // Aceptamos fecha o datetime
                throw new Exception("La fecha de fin de preventa es inválida.");
            }
            $this->fecha_fin_preventa = $valor;
        } else {
            $this->fecha_fin_preventa = null;
        }
    }

    public function setFechaInicioCurso($valor)
    {
        if (self::validator($valor, $this->validate_datetime) !== true && self::validator($valor, $this->validate_fecha) !== true) {
            throw new Exception("La fecha de inicio de curso es inválida.");
        }
        $this->fecha_inicio_curso = $valor;
    }

    public function setFechaFinCurso($valor)
    {
        if (!empty($valor)) {
            if (self::validator($valor, $this->validate_datetime) !== true && self::validator($valor, $this->validate_fecha) !== true) {
                throw new Exception("La fecha de fin de curso es inválida.");
            }
            $this->fecha_fin_curso = $valor;
        } else {
            $this->fecha_fin_curso = null;
        }
    }

    public function setLimiteCupos($valor)
    {
        if (self::validator($valor, $this->validate_number) !== true) {
            throw new Exception("El límite de cupos es inválido.");
        }
        $this->limite_cupos = $valor;
    }

    public function setEstatusCurso($valor)
    {
        if (self::validator($valor, $this->validate_estatus_curso_opciones) !== true) {
            throw new Exception("El estatus del curso es inválido.");
        }
        $this->estatus_curso = $valor;
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
        
        // Validación transversal a nivel de entidad
        if (isset($validatedData['precio_preventa']) && isset($validatedData['precio_normal'])) {
            if ($validatedData['precio_preventa'] >= $validatedData['precio_normal']) {
                throw new Exception("El precio de preventa debe ser menor al precio normal.");
            }
        }

        if (isset($validatedData['fecha_fin_preventa']) && isset($validatedData['fecha_inicio_curso'])) {
            if (strtotime($validatedData['fecha_fin_preventa']) >= strtotime($validatedData['fecha_inicio_curso'])) {
                throw new Exception("La fecha de fin de preventa debe ser anterior al inicio del curso.");
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
            $stmt = $this->con->query("SELECT * FROM {$this->table}");
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
            // La base de datos tiene un trigger trg_cursos_no_delete_con_matriculas
            // que bloquea el DELETE si hay matrículas activas.
            $sql = "DELETE FROM {$this->table} WHERE {$this->idField} = ?";
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
