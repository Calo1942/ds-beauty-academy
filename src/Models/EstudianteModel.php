<?php

namespace DsBeautyAcademy\Models;

use Exception;
use DsBeautyAcademy\config\connect\DBConnect;
use DsBeautyAcademy\config\interfaces\Crud;
use DsBeautyAcademy\Helpers\ApiResponse;
use DsBeautyAcademy\Helpers\Validations;

class EstudianteModel extends DBConnect implements Crud
{
    use ApiResponse, Validations;

    private $id_estudiante;
    private $primer_nombre_estudiante;
    private $segundo_nombre_estudiante;
    private $primer_apellido_estudiante;
    private $segundo_apellido_estudiante;
    private $cedula_estudiante;
    private $correo_estudiante;
    private $telefono_principal_estudiante;
    private $telefono_alternativo_estudiante;
    private $usuario_instagram_estudiante;
    private $estatus_estudiante;

    private function validarDatos($data, $isUpdate = false)
    {
        $id = $data['id_estudiante'] ?? null;
        $primer_nombre = $data['primer_nombre_estudiante'] ?? null;
        $segundo_nombre = $data['segundo_nombre_estudiante'] ?? null;
        $primer_apellido = $data['primer_apellido_estudiante'] ?? null;
        $segundo_apellido = $data['segundo_apellido_estudiante'] ?? null;
        $cedula = $data['cedula_estudiante'] ?? null;
        $correo = $data['correo_estudiante'] ?? null;
        $telefono_principal = $data['telefono_principal_estudiante'] ?? null;
        $telefono_alternativo = $data['telefono_alternativo_estudiante'] ?? null;
        $usuario_instagram = $data['usuario_instagram_estudiante'] ?? null;
        $estatus = $data['estatus_estudiante'] ?? 1;

        if ($isUpdate || $id !== null) {
            if ($this->validator($id, $this->validate_id) !== true) {
                throw new Exception("El ID del estudiante es inválido.");
            }
            $this->id_estudiante = $id;
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
            throw new Exception("El estatus del estudiante es inválido.");
        }

        $this->primer_nombre_estudiante = $primer_nombre;
        $this->segundo_nombre_estudiante = empty($segundo_nombre) ? null : $segundo_nombre;
        $this->primer_apellido_estudiante = $primer_apellido;
        $this->segundo_apellido_estudiante = empty($segundo_apellido) ? null : $segundo_apellido;
        $this->cedula_estudiante = $cedula;
        $this->correo_estudiante = $correo;
        $this->telefono_principal_estudiante = $telefono_principal;
        $this->telefono_alternativo_estudiante = empty($telefono_alternativo) ? null : $telefono_alternativo;
        $this->usuario_instagram_estudiante = empty($usuario_instagram) ? null : $usuario_instagram;
        $this->estatus_estudiante = $estatus;

        return true;
    }

    public function verificarDatosExistentes($cedula, $id_excluir = null)
    {
        try {
            $sql = "SELECT id_estudiante, estatus_estudiante FROM estudiantes WHERE cedula_estudiante = :cedula";
            if ($id_excluir) {
                $sql .= " AND id_estudiante != :id_excluir";
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
            ':p_nombre' => $this->primer_nombre_estudiante,
            ':s_nombre' => $this->segundo_nombre_estudiante,
            ':p_apellido' => $this->primer_apellido_estudiante,
            ':s_apellido' => $this->segundo_apellido_estudiante,
            ':cedula' => $this->cedula_estudiante,
            ':correo' => $this->correo_estudiante,
            ':tel_princ' => $this->telefono_principal_estudiante,
            ':tel_alt' => $this->telefono_alternativo_estudiante,
            ':instagram' => $this->usuario_instagram_estudiante,
            ':estatus' => $this->estatus_estudiante
        ];
    }

    public function guardar($data)
    {
        try {
            $this->validarDatos($data);
            $this->guardarDatos();
            return $this->success(201, "Estudiante agregado correctamente.");
        } catch (Exception $e) {
            return $this->error(400, $e->getMessage());
        }
    }

    private function guardarDatos()
    {
        try {
            $estudianteExistente = $this->verificarDatosExistentes($this->cedula_estudiante);

            if ($estudianteExistente) {
                if ($estudianteExistente['estatus_estudiante'] == 1) {
                    throw new Exception("Ya existe un estudiante registrado con esa cédula.");
                } else {
                    // Reactivar estudiante inactivo y actualizar sus datos
                    $this->id_estudiante = $estudianteExistente['id_estudiante'];
                    $this->estatus_estudiante = 1;
                    $this->actualizarDatos();
                    return;
                }
            }

            $sqlInsert = "INSERT INTO estudiantes (
                primer_nombre_estudiante, 
                segundo_nombre_estudiante, 
                primer_apellido_estudiante, 
                segundo_apellido_estudiante, 
                cedula_estudiante, 
                correo_estudiante, 
                telefono_principal_estudiante, 
                telefono_alternativo_estudiante, 
                usuario_instagram_estudiante, 
                estatus_estudiante
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
            $sql = "SELECT * FROM estudiantes WHERE estatus_estudiante = 1";
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
                throw new Exception("El ID del estudiante es inválido.");
            }

            $sql = "SELECT * FROM estudiantes WHERE id_estudiante = :id";
            $stmt = $this->con->prepare($sql);
            $stmt->bindValue(':id', $id);
            $stmt->execute();
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            if ($result) {
                return $this->success(200, "Consulta exitosa", $result);
            }
            return $this->error(404, "Estudiante no encontrado");

        } catch (\PDOException $e) {
            return $this->error(400, "Error de base de datos", $e->getMessage());
        } catch (Exception $e) {
            return $this->error(400, $e->getMessage());
        }
    }

    public function actualizar($id, $data)
    {
        try {
            $data['id_estudiante'] = $id;
            $this->validarDatos($data, true);
            $this->actualizarDatos();
            return $this->success(200, "Estudiante modificado correctamente");
        } catch (Exception $e) {
            return $this->error(400, $e->getMessage());
        }
    }

    private function actualizarDatos()
    {
        try {
            $estudianteExistente = $this->verificarDatosExistentes($this->cedula_estudiante, $this->id_estudiante);
            if ($estudianteExistente) {
                throw new Exception("Ya existe otro estudiante registrado con esa cédula.");
            }

            $sql = "UPDATE estudiantes SET 
                primer_nombre_estudiante = :p_nombre,
                segundo_nombre_estudiante = :s_nombre,
                primer_apellido_estudiante = :p_apellido,
                segundo_apellido_estudiante = :s_apellido,
                cedula_estudiante = :cedula,
                correo_estudiante = :correo,
                telefono_principal_estudiante = :tel_princ,
                telefono_alternativo_estudiante = :tel_alt,
                usuario_instagram_estudiante = :instagram,
                estatus_estudiante = :estatus
                WHERE id_estudiante = :id";

            $stmt = $this->con->prepare($sql);
            
            $parametros = $this->obtenerParametros();
            $parametros[':id'] = $this->id_estudiante;
            $stmt->execute($parametros);
        } catch (\PDOException $e) {
            throw new Exception("Error al actualizar: " . $e->getMessage());
        }
    }

    public function eliminar($id)
    {
        try {
            if (self::validator($id, $this->validate_id) !== true) {
                throw new Exception("El ID del estudiante es inválido.");
            }
            $this->id_estudiante = $id;
            $this->eliminarDatos();
            return $this->success(200, "Estudiante eliminado correctamente");
        } catch (Exception $e) {
            return $this->error(400, $e->getMessage());
        }
    }

    private function eliminarDatos()
    {
        try {
            $sql = "UPDATE estudiantes SET estatus_estudiante = 0 WHERE id_estudiante = :id";
            $stmt = $this->con->prepare($sql);
            $stmt->bindValue(':id', $this->id_estudiante);
            $stmt->execute();
        } catch (\PDOException $e) {
            throw new Exception("Error al eliminar: " . $e->getMessage());
        }
    }
}
