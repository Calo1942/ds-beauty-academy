<?php

namespace DsBeautyAcademy\Models;

use Exception;
use DsBeautyAcademy\config\connect\DBConnect;
use DsBeautyAcademy\config\interfaces\Crud;
use DsBeautyAcademy\Helpers\ApiResponse;
use DsBeautyAcademy\Helpers\Validations;

class RecursoCursoModel extends DBConnect implements Crud
{
    use ApiResponse, Validations;

    private $table = 'recursos_curso';
    private $idField = 'id_curso'; // Agrupamos por curso
    
    private $id_curso;

    private $module_name = [
        'singular' => 'Recurso de Curso',
        'plural' => 'Recursos de Cursos'
    ];

    public function setIdCurso($valor)
    {
        if (self::validator($valor, $this->validate_id) !== true) {
            throw new Exception("El ID de curso es inválido.");
        }
        $this->id_curso = $valor;
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
            if (!isset($data['id_curso']) || !isset($data['id_recurso']) || !is_array($data['id_recurso'])) {
                throw new Exception("Faltan datos o el formato es inválido para asignar recursos.");
            }

            $this->setIdCurso($data['id_curso']);

            $this->con->beginTransaction();

            foreach ($data['id_recurso'] as $id_rec) {
                if (self::validator($id_rec, $this->validate_id) !== true) {
                    throw new Exception("ID de recurso inválido.");
                }

                $stmt = $this->con->prepare("INSERT INTO {$this->table} (id_curso, id_recurso) VALUES (?, ?)");
                $stmt->execute([$this->id_curso, $id_rec]);
            }

            $this->con->commit();
            return self::success(201, "Recursos asignados exitosamente");

        } catch (Exception $e) {
            if ($this->con->inTransaction()) {
                $this->con->rollBack();
            }
            return self::error(500, 'Error al almacenar asignaciones', $e->getMessage());
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
            $result = $stmt->fetchAll();
            return self::success(200, "{$this->module_name['plural']} del curso obtenidas", $result);
        } catch (Exception $e) {
            return self::error(500, 'Error al obtener', $e->getMessage());
        }
    }

    public function actualizar($id, $data)
    {
        try {
            $this->validarId($id);

            if (!isset($data['id_recurso']) || !is_array($data['id_recurso'])) {
                throw new Exception("El formato de los recursos es inválido.");
            }

            $this->con->beginTransaction();

            $stmt = $this->con->prepare("DELETE FROM {$this->table} WHERE {$this->idField} = ?");
            $stmt->execute([$id]);

            foreach ($data['id_recurso'] as $id_rec) {
                if (self::validator($id_rec, $this->validate_id) !== true) {
                    throw new Exception("ID de recurso inválido.");
                }
                $stmt = $this->con->prepare("INSERT INTO {$this->table} (id_curso, id_recurso) VALUES (?, ?)");
                $stmt->execute([$id, $id_rec]);
            }

            $this->con->commit();
            return self::success(200, "Recursos actualizados exitosamente");

        } catch (Exception $e) {
            if ($this->con->inTransaction()) {
                $this->con->rollBack();
            }
            return self::error(500, 'Error al actualizar', $e->getMessage());
        }
    }

    public function eliminar($id)
    {
        try {
            $this->validarId($id);

            $sql = "DELETE FROM {$this->table} WHERE {$this->idField} = ?";
            $stmt = $this->con->prepare($sql);
            if ($stmt->execute([$id])) {
                return self::success(200, "Asignaciones eliminadas");
            }
            throw new Exception('Error al eliminar');
        } catch (Exception $e) {
            return self::error(500, 'Error al eliminar', $e->getMessage());
        }
    }
}
