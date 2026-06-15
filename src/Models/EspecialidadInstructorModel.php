<?php

namespace DsBeautyAcademy\Models;

use Exception;
use DsBeautyAcademy\config\connect\DBConnect;
use DsBeautyAcademy\config\interfaces\Crud;
use DsBeautyAcademy\Helpers\ApiResponse;
use DsBeautyAcademy\Helpers\Validations;

class EspecialidadInstructorModel extends DBConnect implements Crud
{
    use ApiResponse, Validations;

    private $table = 'especialidad_instructor';
    private $idField = 'id_instructor'; // Agrupamos por instructor
    
    private $id_instructor;

    private $module_name = [
        'singular' => 'Especialidad de Instructor',
        'plural' => 'Especialidades de Instructor'
    ];

    public function setIdInstructor($valor)
    {
        if (self::validator($valor, $this->validate_id) !== true) {
            throw new Exception("El ID de instructor es inválido.");
        }
        $this->id_instructor = $valor;
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
            if (!isset($data['id_instructor']) || !isset($data['id_especialidad']) || !is_array($data['id_especialidad'])) {
                throw new Exception("Faltan datos o el formato es inválido para asignar especialidades.");
            }

            $this->setIdInstructor($data['id_instructor']);

            $this->con->beginTransaction();

            foreach ($data['id_especialidad'] as $id_esp) {
                if (self::validator($id_esp, $this->validate_id) !== true) {
                    throw new Exception("ID de especialidad inválido.");
                }

                $stmt = $this->con->prepare("INSERT INTO {$this->table} (id_instructor, id_especialidad) VALUES (?, ?)");
                $stmt->execute([$this->id_instructor, $id_esp]);
            }

            $this->con->commit();
            return self::success(201, "Especialidades asignadas exitosamente");

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
            // Busca todas las especialidades de un instructor
            $stmt = $this->con->prepare("SELECT * FROM {$this->table} WHERE {$this->idField} = ?");
            $stmt->execute([$id]);
            $result = $stmt->fetchAll();
            return self::success(200, "{$this->module_name['plural']} del instructor obtenidas", $result);
        } catch (Exception $e) {
            return self::error(500, 'Error al obtener', $e->getMessage());
        }
    }

    public function actualizar($id, $data)
    {
        try {
            $this->validarId($id);

            if (!isset($data['id_especialidad']) || !is_array($data['id_especialidad'])) {
                throw new Exception("El formato de las especialidades es inválido.");
            }

            $this->con->beginTransaction();

            // Borrar existentes
            $stmt = $this->con->prepare("DELETE FROM {$this->table} WHERE {$this->idField} = ?");
            $stmt->execute([$id]);

            // Insertar nuevas
            foreach ($data['id_especialidad'] as $id_esp) {
                if (self::validator($id_esp, $this->validate_id) !== true) {
                    throw new Exception("ID de especialidad inválido.");
                }
                $stmt = $this->con->prepare("INSERT INTO {$this->table} (id_instructor, id_especialidad) VALUES (?, ?)");
                $stmt->execute([$id, $id_esp]);
            }

            $this->con->commit();
            return self::success(200, "Especialidades actualizadas exitosamente");

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
