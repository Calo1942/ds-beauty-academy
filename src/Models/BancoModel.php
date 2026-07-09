<?php

namespace DsBeautyAcademy\Models;

use Exception;
use DsBeautyAcademy\config\connect\DBConnect;
use DsBeautyAcademy\config\interfaces\Crud;
use DsBeautyAcademy\Helpers\ApiResponse;
use DsBeautyAcademy\Helpers\Validations;

class BancoModel extends DBConnect implements Crud
{
    use ApiResponse, Validations;

    private $id_banco;
    private $nombre_banco;
    private $estatus_banco;

    // Metodo para validar datos
    private function validarDatos($data, $isUpdate = false)
    {
        $id = $data['id_banco'] ?? null;
        $nombre_banco = $data['nombre_banco'] ?? null;
        $status = $data['estatus_banco'] ?? 1;

        if ($isUpdate || $id !== null) {
            if ($this->validator($id, $this->validate_id) !== true) {
                throw new Exception("El ID del banco es inválido.");
            }
            $this->id_banco = $id;
        }

        if (self::validator($nombre_banco, $this->validate_names) !== true) {
            throw new Exception("El nombre del banco es inválido.");
        }

        // Estatus validation can be tricky with strict types in $_POST, so we ensure it's validated
        if (!in_array($status, $this->validate_boolean, false)) {
            throw new Exception("El estatus del banco es inválido.");
        }

        // Encapsulamiento si todo está correcto
        $this->nombre_banco = $nombre_banco;
        $this->estatus_banco = $status;
        return true;
    }

    // Metodo setter para guardar datos
    public function guardar($data)
    {
        try {
            $this->validarDatos($data);
            $this->guardarDatos();
            return $this->success(201, "Dato agregado correctamente.");
        } catch (Exception $e) {
            return $this->error(400, $e->getMessage());
        }
    }

    // Metodo para verificar si el dato existe
    public function verificarDatosExistentes($nombre, $id_excluir = null)
    {
        try {
            // Verificamos si existe un banco con ese nombre.
            // Si pasamos un $id_excluir (ej. al actualizar), excluimos ese ID de la búsqueda.
            $sql = "SELECT id_banco, estatus_banco FROM banco WHERE nombre_banco = :nombre";
            if ($id_excluir) {
                $sql .= " AND id_banco != :id_excluir";
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
            // Si falla la consulta, es mejor lanzar la excepción para no insertar a ciegas
            throw new Exception("Error al verificar existencia: " . $e->getMessage());
        }
    }

    private function guardarDatos()
    {
        try {
            // Se verifica si el banco ya existe (activo o inactivo)
            $bancoExistente = $this->verificarDatosExistentes($this->nombre_banco);

            if ($bancoExistente) {
                if ($bancoExistente['estatus_banco'] == 1) {
                    throw new Exception("Ya existe un banco registrado con ese nombre.");
                } else {
                    // Se Reahabilita el banco y se actualiza el nombre (por si se modificaron las mayúsculas o minúsculas)
                    $sqlUpdate = "UPDATE banco SET nombre_banco = :nombre, estatus_banco = 1 WHERE id_banco = :id";
                    $stmtUpdate = $this->con->prepare($sqlUpdate);
                    $stmtUpdate->bindValue(':nombre', trim($this->nombre_banco));
                    $stmtUpdate->bindValue(':id', $bancoExistente['id_banco']);
                    $stmtUpdate->execute();
                    return;
                }
            }

            // Si el dato no existe en absoluto, se procede a insertar el nuevo registro
            $sqlInsert = "INSERT INTO banco (nombre_banco, estatus_banco) VALUES (:nombre, :estatus)";
            $stmtInsert = $this->con->prepare($sqlInsert);
            $stmtInsert->bindValue(':nombre', $this->nombre_banco);
            $stmtInsert->bindValue(':estatus', $this->estatus_banco);
            $stmtInsert->execute();

        } catch (\PDOException $e) {
            throw new Exception("Error en la base de datos: " . $e->getMessage());
        }
    }

    // Metodo para buscar todos los datos activos
    public function buscarTodos()
    {
        try {
            $sql = "SELECT id_banco, nombre_banco, estatus_banco FROM banco WHERE estatus_banco = 1";
            $stmt = $this->con->prepare($sql);
            $stmt->execute();

            $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            return $this->success(200, "Consulta exitosa", $result);
        } catch (\PDOException $e) {
            return $this->error(400, "Error de base de datos", $e->getMessage());
        }
    }

    // Metodo para buscar un dato por id
    public function buscar($id)
    {
        try {
            // Validación del ID
            if (self::validator($id, $this->validate_id) !== true) {
                throw new Exception("El ID del banco es inválido.");
            }

            // Preparación de consulta SQL
            $sql = "SELECT id_banco, nombre_banco, estatus_banco FROM banco WHERE id_banco = :id";
            $stmt = $this->con->prepare($sql);
            $stmt->bindValue(':id', $id);
            $stmt->execute();
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            if ($result) {
                return $this->success(200, "Consulta exitosa", $result);
            }
            return $this->error(404, "Banco no encontrado");

        } catch (\PDOException $e) {
            return $this->error(400, "Error de base de datos", $e->getMessage());
        } catch (Exception $e) {
            return $this->error(400, $e->getMessage());
        }
    }

    // Metodo setter para actualizar un dato
    public function actualizar($id, $data)
    {
        try {
            $data['id_banco'] = $id;
            $this->validarDatos($data, true);
            $this->actualizarDatos();
            return $this->success(200, "Dato modificado correctamente");
        } catch (Exception $e) {
            return $this->error(400, $e->getMessage());
        }
    }

    // Metodo para actualizar un dato
    private function actualizarDatos()
    {
        try {
            // Validamos que el nuevo nombre no le pertenezca ya a otro banco
            $bancoExistente = $this->verificarDatosExistentes($this->nombre_banco, $this->id_banco);
            if ($bancoExistente) {
                throw new Exception("Ya existe otro banco registrado con ese nombre.");
            }

            $sql = "UPDATE banco SET nombre_banco = :nombre, estatus_banco = :estatus WHERE id_banco = :id";
            $stmt = $this->con->prepare($sql);
            $stmt->bindValue(':nombre', trim($this->nombre_banco));
            $stmt->bindValue(':estatus', $this->estatus_banco);
            $stmt->bindValue(':id', $this->id_banco);
            $stmt->execute();
        } catch (\PDOException $e) {
            throw new Exception("Error al actualizar: " . $e->getMessage());
        }
    }

    // Metodo setter para eliminar un dato
    public function eliminar($id)
    {
        try {
            if (self::validator($id, $this->validate_id) !== true) {
                throw new Exception("El ID del banco es inválido.");
            }
            $this->id_banco = $id;
            $this->eliminarDatos();
            return $this->success(200, "Dato eliminado correctamente");
        } catch (Exception $e) {
            return $this->error(400, $e->getMessage());
        }
    }

    // Metodo para eliminar un dato
    private function eliminarDatos()
    {
        try {
            // Eliminación Lógica
            $sql = "UPDATE banco SET estatus_banco = 0 WHERE id_banco = :id";
            $stmt = $this->con->prepare($sql);
            $stmt->bindValue(':id', $this->id_banco);
            $stmt->execute();
        } catch (\PDOException $e) {
            throw new Exception("Error al eliminar: " . $e->getMessage());
        }
    }
}