<?php

namespace DsBeautyAcademy\Models;

use DsBeautyAcademy\config\connect\DBConnect;
use DsBeautyAcademy\config\interfaces\Crud;
use DsBeautyAcademy\Helpers\ApiResponse;
use PDO;
use PDOException;
use Exception;

/**
 * Modelo para la Gestión de Roles
 * DS Beauty Academy
 */
class RolModel extends DBConnect implements Crud
{
    use ApiResponse;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Guarda un nuevo rol en la base de datos
     */
    public function guardar($data)
    {
        try {
            $nombre = trim($data['nombre_rol'] ?? '');
            $descripcion = trim($data['descripcion_rol'] ?? '');
            $estatus = isset($data['estatus_rol']) ? (int)$data['estatus_rol'] : 1;

            if (empty($nombre)) {
                return $this->error(400, 'El nombre del rol es obligatorio.');
            }

            // Verificar si ya existe un rol con el mismo nombre
            $checkSql = "SELECT id_rol FROM roles WHERE LOWER(TRIM(nombre_rol)) = LOWER(TRIM(:nombre)) LIMIT 1";
            $checkStmt = $this->con->prepare($checkSql);
            $checkStmt->bindValue(':nombre', $nombre, PDO::PARAM_STR);
            $checkStmt->execute();

            if ($checkStmt->fetch()) {
                return $this->error(400, 'Ya existe un rol registrado con este nombre.');
            }

            $sql = "INSERT INTO roles (nombre_rol, descripcion_rol, estatus_rol, fecha_creacion) 
                    VALUES (:nombre, :descripcion, :estatus, NOW())";
            $stmt = $this->con->prepare($sql);
            $stmt->bindValue(':nombre', $nombre, PDO::PARAM_STR);
            $stmt->bindValue(':descripcion', $descripcion, PDO::PARAM_STR);
            $stmt->bindValue(':estatus', $estatus, PDO::PARAM_INT);

            if ($stmt->execute()) {
                $id = $this->con->lastInsertId();
                return $this->success(201, 'Rol registrado exitosamente.', ['id_rol' => $id]);
            }

            return $this->error(400, 'No se pudo registrar el rol.');
        } catch (PDOException $e) {
            error_log("Error en RolModel::guardar: " . $e->getMessage());
            return $this->error(500, 'Error de base de datos al registrar el rol.', $e->getMessage());
        } catch (Exception $e) {
            error_log("Excepción en RolModel::guardar: " . $e->getMessage());
            return $this->error(500, 'Ocurrió un error inesperado al registrar el rol.', $e->getMessage());
        }
    }

    /**
     * Obtiene todos los roles registrados
     */
    public function buscarTodos()
    {
        try {
            $sql = "SELECT r.id_rol, r.nombre_rol, r.descripcion_rol, r.estatus_rol, r.fecha_creacion,
                           COUNT(u.id_usuario) as total_usuarios
                    FROM roles r
                    LEFT JOIN usuarios u ON r.id_rol = u.id_rol
                    GROUP BY r.id_rol, r.nombre_rol, r.descripcion_rol, r.estatus_rol, r.fecha_creacion
                    ORDER BY r.id_rol ASC";
            $stmt = $this->con->prepare($sql);
            $stmt->execute();
            $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $this->success(200, 'Roles obtenidos correctamente.', $roles);
        } catch (PDOException $e) {
            error_log("Error en RolModel::buscarTodos: " . $e->getMessage());
            return $this->error(500, 'Error al consultar el listado de roles.', $e->getMessage());
        }
    }

    /**
     * Busca un rol por su ID
     */
    public function buscar($id)
    {
        try {
            $id = (int)$id;
            $sql = "SELECT r.id_rol, r.nombre_rol, r.descripcion_rol, r.estatus_rol, r.fecha_creacion,
                           COUNT(u.id_usuario) as total_usuarios
                    FROM roles r
                    LEFT JOIN usuarios u ON r.id_rol = u.id_rol
                    WHERE r.id_rol = :id
                    GROUP BY r.id_rol, r.nombre_rol, r.descripcion_rol, r.estatus_rol, r.fecha_creacion
                    LIMIT 1";
            $stmt = $this->con->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $rol = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($rol) {
                return $this->success(200, 'Rol encontrado.', $rol);
            }

            return $this->error(404, 'El rol solicitado no existe.');
        } catch (PDOException $e) {
            error_log("Error en RolModel::buscar: " . $e->getMessage());
            return $this->error(500, 'Error al buscar los datos del rol.', $e->getMessage());
        }
    }

    /**
     * Actualiza un rol existente
     */
    public function actualizar($id, $data)
    {
        try {
            $id = (int)$id;
            $nombre = trim($data['nombre_rol'] ?? '');
            $descripcion = trim($data['descripcion_rol'] ?? '');
            $estatus = isset($data['estatus_rol']) ? (int)$data['estatus_rol'] : 1;

            if (empty($nombre)) {
                return $this->error(400, 'El nombre del rol es obligatorio.');
            }

            // Validar si existe otro rol con el mismo nombre
            $checkSql = "SELECT id_rol FROM roles WHERE LOWER(TRIM(nombre_rol)) = LOWER(TRIM(:nombre)) AND id_rol != :id LIMIT 1";
            $checkStmt = $this->con->prepare($checkSql);
            $checkStmt->bindValue(':nombre', $nombre, PDO::PARAM_STR);
            $checkStmt->bindValue(':id', $id, PDO::PARAM_INT);
            $checkStmt->execute();

            if ($checkStmt->fetch()) {
                return $this->error(400, 'Ya existe otro rol con este nombre.');
            }

            // Si es un rol protegido del sistema (Administrador) y se intenta desactivar
            if ($id === 1 && $estatus == 0) {
                return $this->error(400, 'No se puede desactivar el rol principal de Administrador.');
            }

            $sql = "UPDATE roles 
                    SET nombre_rol = :nombre, 
                        descripcion_rol = :descripcion, 
                        estatus_rol = :estatus 
                    WHERE id_rol = :id";
            $stmt = $this->con->prepare($sql);
            $stmt->bindValue(':nombre', $nombre, PDO::PARAM_STR);
            $stmt->bindValue(':descripcion', $descripcion, PDO::PARAM_STR);
            $stmt->bindValue(':estatus', $estatus, PDO::PARAM_INT);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                return $this->success(200, 'Rol actualizado correctamente.');
            }

            return $this->error(400, 'No se pudo actualizar el rol.');
        } catch (PDOException $e) {
            error_log("Error en RolModel::actualizar: " . $e->getMessage());
            return $this->error(500, 'Error de base de datos al actualizar el rol.', $e->getMessage());
        }
    }

    /**
     * Elimina un rol si no tiene usuarios vinculados y no es rol crítico
     */
    public function eliminar($id)
    {
        try {
            $id = (int)$id;

            // Roles protegidos del núcleo
            if (in_array($id, [1, 2, 3], true)) {
                return $this->error(400, 'Los roles base del sistema (Administrador, Instructor, Estudiante) están protegidos y no pueden ser eliminados.');
            }

            // Verificar si tiene usuarios asignados
            $checkUserSql = "SELECT COUNT(*) as total FROM usuarios WHERE id_rol = :id";
            $checkUserStmt = $this->con->prepare($checkUserSql);
            $checkUserStmt->bindValue(':id', $id, PDO::PARAM_INT);
            $checkUserStmt->execute();
            $userCount = $checkUserStmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

            if ($userCount > 0) {
                return $this->error(400, "No se puede eliminar este rol porque tiene {$userCount} usuario(s) asignado(s). Reasigna los usuarios primero.");
            }

            $sql = "DELETE FROM roles WHERE id_rol = :id";
            $stmt = $this->con->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                return $this->success(200, 'Rol eliminado correctamente.');
            }

            return $this->error(400, 'No se pudo eliminar el rol.');
        } catch (PDOException $e) {
            error_log("Error en RolModel::eliminar: " . $e->getMessage());
            return $this->error(500, 'No se puede eliminar el rol debido a restricciones de integridad de datos.', $e->getMessage());
        }
    }
}
