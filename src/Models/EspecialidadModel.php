<?php

namespace DsBeautyAcademy\Models;

use Exception;
use DsBeautyAcademy\config\connect\DBConnect;
use DsBeautyAcademy\config\interfaces\Crud;
use DsBeautyAcademy\Helpers\ApiResponse;
use DsBeautyAcademy\Helpers\Validations;

class EspecialidadModel extends DBConnect implements Crud
{
    use ApiResponse, Validations;

    private $id_especialidad;
    private $nombre_especialidad;
    private $estatus_especialidad;

    private function validarDatos($data, $isUpdate = false)
    {
        $id = $data['id_especialidad'] ?? null;
        $nombre = $data['nombre_especialidad'] ?? null;
        $estatus = $data['estatus_especialidad'] ?? 1;

        if ($isUpdate || $id !== null) {
            if ($this->validator($id, $this->validate_id) !== true) {
                throw new Exception("El ID de la especialidad es inválido.");
            }
            $this->id_especialidad = $id;
        }

        if (self::validator($nombre, $this->validate_names) !== true) {
            throw new Exception("El nombre de la especialidad es inválido.");
        }

        if (!in_array($estatus, $this->validate_boolean, false)) {
            throw new Exception("El estatus de la especialidad es inválido.");
        }

        $this->nombre_especialidad = $nombre;
        $this->estatus_especialidad = $estatus;

        return true;
    }

    public function verificarDatosExistentes($nombre, $id_excluir = null)
    {
        try {
            $sql = "SELECT id_especialidad, estatus_especialidad FROM especialidades WHERE nombre_especialidad = :nombre";
            if ($id_excluir) {
                $sql .= " AND id_especialidad != :id_excluir";
            }

            $stmt = $this->con->prepare($sql);
            $stmt->bindValue(':nombre', trim($nombre));
            if ($id_excluir) {
                $stmt->bindValue(':id_excluir', $id_excluir);
            }
            $stmt->execute();
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);

            return ($result) ? $result : false;
        } catch (\PDOException $e) {
            throw new Exception("Error al verificar existencia: " . $e->getMessage());
        }
    }

    private function obtenerParametros()
    {
        return [
            ':nombre' => $this->nombre_especialidad,
            ':estatus' => $this->estatus_especialidad
        ];
    }

    public function guardar($data)
    {
        try {
            $this->validarDatos($data);
            $this->guardarDatos();
            return $this->success(201, "Especialidad creada exitosamente.");
        } catch (Exception $e) {
            return $this->error(400, $e->getMessage());
        }
    }

    private function guardarDatos()
    {
        try {
            $especialidadExistente = $this->verificarDatosExistentes($this->nombre_especialidad);

            if ($especialidadExistente) {
                if ($especialidadExistente['estatus_especialidad'] == 1) {
                    throw new Exception("Ya existe una especialidad registrada con ese nombre.");
                }
            }

            $sqlInsert = "INSERT INTO especialidades (nombre_especialidad, estatus_especialidad) 
                          VALUES (:nombre, :estatus)";
            $stmtInsert = $this->con->prepare($sqlInsert);
            $stmtInsert->execute($this->obtenerParametros());
        } catch (\PDOException $e) {
            throw new Exception("Error en la base de datos: " . $e->getMessage());
        }
    }

    public function buscarTodos()
    {
        try {
            $sql = "SELECT * FROM especialidades WHERE estatus_especialidad = 1";
            $stmt = $this->con->prepare($sql);
            $stmt->execute();

            $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            return $this->success(200, "Especialidades obtenidas", $result);
        } catch (\PDOException $e) {
            return $this->error(400, "Error de base de datos", $e->getMessage());
        }
    }

    public function buscar($id)
    {
        try {
            if (self::validator($id, $this->validate_id) !== true) {
                throw new Exception("El ID de la especialidad es inválido.");
            }

            $sql = "SELECT * FROM especialidades WHERE id_especialidad = :id";
            $stmt = $this->con->prepare($sql);
            $stmt->bindValue(':id', $id);
            $stmt->execute();
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            if ($result) {
                return $this->success(200, "Especialidad obtenida", $result);
            }
            return $this->error(404, "Especialidad no encontrada");
        } catch (\PDOException $e) {
            return $this->error(400, "Error de base de datos", $e->getMessage());
        } catch (Exception $e) {
            return $this->error(400, $e->getMessage());
        }
    }

    public function actualizar($id, $data)
    {
        try {
            $data['id_especialidad'] = $id;
            $this->validarDatos($data, true);
            $this->actualizarDatos();
            return $this->success(200, "Especialidad actualizada correctamente");
        } catch (Exception $e) {
            return $this->error(400, $e->getMessage());
        }
    }

    private function actualizarDatos()
    {
        try {
            $especialidadExistente = $this->verificarDatosExistentes($this->nombre_especialidad, $this->id_especialidad);
            if ($especialidadExistente) {
                throw new Exception("Ya existe otra especialidad registrada con ese nombre.");
            }

            $sql = "UPDATE especialidades SET 
                nombre_especialidad = :nombre,
                estatus_especialidad = :estatus
                WHERE id_especialidad = :id";

            $stmt = $this->con->prepare($sql);
            
            $parametros = $this->obtenerParametros();
            $parametros[':id'] = $this->id_especialidad;
            $stmt->execute($parametros);
        } catch (\PDOException $e) {
            throw new Exception("Error al actualizar: " . $e->getMessage());
        }
    }

    public function eliminar($id)
    {
        try {
            if (self::validator($id, $this->validate_id) !== true) {
                throw new Exception("El ID de la especialidad es inválido.");
            }
            $this->id_especialidad = $id;
            $this->eliminarDatos();
            return $this->success(200, "Especialidad eliminada correctamente");
        } catch (Exception $e) {
            return $this->error(400, $e->getMessage());
        }
    }

    private function eliminarDatos()
    {
        try {
            $sql = "UPDATE especialidades SET estatus_especialidad = 0 WHERE id_especialidad = :id";
            $stmt = $this->con->prepare($sql);
            $stmt->bindValue(':id', $this->id_especialidad);
            $stmt->execute();
        } catch (\PDOException $e) {
            throw new Exception("Error al eliminar: " . $e->getMessage());
        }
    }
}
