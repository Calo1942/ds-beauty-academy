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

    private $id_instructor;
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

    // Metodo para validar datos
    private function validarDatos($data, $isUpdate = false)
    {
        $id = $data['id_instructor'] ?? null;
        $primer_nombre = $data['primer_nombre_instructor'] ?? null;
        $segundo_nombre = $data['segundo_nombre_instructor'] ?? null;
        $primer_apellido = $data['primer_apellido_instructor'] ?? null;
        $segundo_apellido = $data['segundo_apellido_instructor'] ?? null;
        $cedula = $data['cedula_instructor'] ?? null;
        $correo = $data['correo_instructor'] ?? null;
        $telefono_principal = $data['telefono_principal_instructor'] ?? null;
        $telefono_alternativo = $data['telefono_alternativo_instructor'] ?? null;
        $usuario_instagram = $data['usuario_instagram_instructor'] ?? null;
        $estatus = $data['estatus_instructor'] ?? 1;

        if ($isUpdate || $id !== null) {
            if ($this->validator($id, $this->validate_id) !== true) {
                throw new Exception("El ID del instructor es inválido.");
            }
            $this->id_instructor = $id;
        }

        if (self::validator($primer_nombre, $this->validate_names) !== true) {
            throw new Exception("El primer nombre es inválido.");
        }

        if (!empty($segundo_nombre) && self::validator($segundo_nombre, $this->validate_names) !== true) {
            throw new Exception("El segundo nombre es inválido.");
        }

        if (self::validator($primer_apellido, $this->validate_names) !== true) {
            throw new Exception("El primer apellido es inválido.");
        }

        if (!empty($segundo_apellido) && self::validator($segundo_apellido, $this->validate_names) !== true) {
            throw new Exception("El segundo apellido es inválido.");
        }

        if (self::validator($cedula, $this->validate_cedula) !== true) {
            throw new Exception("La cédula es inválida.");
        }

        if (self::validator($correo, $this->validate_email) !== true) {
            throw new Exception("El correo es inválido.");
        }

        if (self::validator($telefono_principal, $this->validate_telefono) !== true) {
            throw new Exception("El teléfono principal es inválido.");
        }

        if (!empty($telefono_alternativo) && self::validator($telefono_alternativo, $this->validate_telefono) !== true) {
            throw new Exception("El teléfono alternativo es inválido.");
        }

        if (!empty($usuario_instagram) && self::validator($usuario_instagram, $this->validate_usuario_instagram) !== true) {
            throw new Exception("El usuario de Instagram es inválido.");
        }

        $estatus = $this->parseBoolean($estatus);

        if (!in_array($estatus, $this->validate_boolean, true)) {
            throw new Exception("El estatus del instructor es inválido.");
        }

        // Encapsulamiento
        $this->primer_nombre_instructor = $primer_nombre;
        $this->segundo_nombre_instructor = empty($segundo_nombre) ? null : $segundo_nombre;
        $this->primer_apellido_instructor = $primer_apellido;
        $this->segundo_apellido_instructor = empty($segundo_apellido) ? null : $segundo_apellido;
        $this->cedula_instructor = $cedula;
        $this->correo_instructor = $correo;
        $this->telefono_principal_instructor = $telefono_principal;
        $this->telefono_alternativo_instructor = empty($telefono_alternativo) ? null : $telefono_alternativo;
        $this->usuario_instagram_instructor = empty($usuario_instagram) ? null : $usuario_instagram;
        $this->estatus_instructor = $estatus;

        return true;
    }

    // Metodo setter para guardar datos
    public function guardar($data)
    {
        try {
            $this->validarDatos($data);
            $this->guardarDatos();
            return $this->success(201, "Instructor agregado correctamente.");
        } catch (Exception $e) {
            return $this->error(400, $e->getMessage());
        }
    }

    // Metodo para verificar si el dato existe
    public function verificarDatosExistentes($cedula, $id_excluir = null)
    {
        try {
            $sql = "SELECT id_instructor, estatus_instructor FROM instructores WHERE cedula_instructor = :cedula";
            if ($id_excluir) {
                $sql .= " AND id_instructor != :id_excluir";
            }

            $stmt = $this->con->prepare($sql);
            $stmt->bindValue(':cedula', trim($cedula));
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
            ':p_nombre' => $this->primer_nombre_instructor,
            ':s_nombre' => $this->segundo_nombre_instructor,
            ':p_apellido' => $this->primer_apellido_instructor,
            ':s_apellido' => $this->segundo_apellido_instructor,
            ':cedula' => $this->cedula_instructor,
            ':correo' => $this->correo_instructor,
            ':tel_princ' => $this->telefono_principal_instructor,
            ':tel_alt' => $this->telefono_alternativo_instructor,
            ':instagram' => $this->usuario_instagram_instructor,
            ':estatus' => $this->estatus_instructor
        ];
    }

    private function guardarDatos()
    {
        try {
            $instructorExistente = $this->verificarDatosExistentes($this->cedula_instructor);

            if ($instructorExistente) {
                if ($instructorExistente['estatus_instructor'] == 1) {
                    throw new Exception("Ya existe un instructor registrado con esa cédula.");
                } else {
                    // Reactivar instructor inactivo y actualizar sus datos
                    $this->id_instructor = $instructorExistente['id_instructor'];
                    $this->estatus_instructor = 1;
                    $this->actualizarDatos();
                    return;
                }
            }

            $sqlInsert = "INSERT INTO instructores (
                primer_nombre_instructor, 
                segundo_nombre_instructor, 
                primer_apellido_instructor, 
                segundo_apellido_instructor, 
                cedula_instructor, 
                correo_instructor, 
                telefono_principal_instructor, 
                telefono_alternativo_instructor, 
                usuario_instagram_instructor, 
                estatus_instructor
            ) VALUES (
                :p_nombre, :s_nombre, :p_apellido, :s_apellido, :cedula, 
                :correo, :tel_princ, :tel_alt, :instagram, :estatus
            )";
            $stmtInsert = $this->con->prepare($sqlInsert);
            $stmtInsert->execute($this->obtenerParametros());

        } catch (\PDOException $e) {
            throw new Exception("Error en la base de datos: " . $e->getMessage());
        }
    }

    public function buscarTodos()
    {
        try {
            $sql = "SELECT * FROM instructores WHERE estatus_instructor = 1";
            $stmt = $this->con->prepare($sql);
            $stmt->execute();

            $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            return $this->success(200, "Consulta exitosa", $result);
        } catch (\PDOException $e) {
            return $this->error(400, "Error de base de datos", $e->getMessage());
        }
    }

    public function buscar($id)
    {
        try {
            if (self::validator($id, $this->validate_id) !== true) {
                throw new Exception("El ID del instructor es inválido.");
            }

            $sql = "SELECT * FROM instructores WHERE id_instructor = :id";
            $stmt = $this->con->prepare($sql);
            $stmt->bindValue(':id', $id);
            $stmt->execute();
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            if ($result) {
                return $this->success(200, "Consulta exitosa", $result);
            }
            return $this->error(404, "Instructor no encontrado");

        } catch (\PDOException $e) {
            return $this->error(400, "Error de base de datos", $e->getMessage());
        } catch (Exception $e) {
            return $this->error(400, $e->getMessage());
        }
    }

    public function actualizar($id, $data)
    {
        try {
            $data['id_instructor'] = $id;
            $this->validarDatos($data, true);
            $this->actualizarDatos();
            return $this->success(200, "Instructor modificado correctamente");
        } catch (Exception $e) {
            return $this->error(400, $e->getMessage());
        }
    }

    private function actualizarDatos()
    {
        try {
            $instructorExistente = $this->verificarDatosExistentes($this->cedula_instructor, $this->id_instructor);
            if ($instructorExistente) {
                throw new Exception("Ya existe otro instructor registrado con esa cédula.");
            }

            $sql = "UPDATE instructores SET 
                primer_nombre_instructor = :p_nombre,
                segundo_nombre_instructor = :s_nombre,
                primer_apellido_instructor = :p_apellido,
                segundo_apellido_instructor = :s_apellido,
                cedula_instructor = :cedula,
                correo_instructor = :correo,
                telefono_principal_instructor = :tel_princ,
                telefono_alternativo_instructor = :tel_alt,
                usuario_instagram_instructor = :instagram,
                estatus_instructor = :estatus
                WHERE id_instructor = :id";

            $stmt = $this->con->prepare($sql);

            $parametros = $this->obtenerParametros();
            $parametros[':id'] = $this->id_instructor;
            $stmt->execute($parametros);
        } catch (\PDOException $e) {
            throw new Exception("Error al actualizar: " . $e->getMessage());
        }
    }

    public function eliminar($id)
    {
        try {
            if (self::validator($id, $this->validate_id) !== true) {
                throw new Exception("El ID del instructor es inválido.");
            }
            $this->id_instructor = $id;
            $this->eliminarDatos();
            return $this->success(200, "Instructor eliminado correctamente");
        } catch (Exception $e) {
            return $this->error(400, $e->getMessage());
        }
    }

    private function eliminarDatos()
    {
        try {
            $sql = "UPDATE instructores SET estatus_instructor = 0 WHERE id_instructor = :id";
            $stmt = $this->con->prepare($sql);
            $stmt->bindValue(':id', $this->id_instructor);
            $stmt->execute();
        } catch (\PDOException $e) {
            throw new Exception("Error al eliminar: " . $e->getMessage());
        }
    }
}
