<?php

namespace DsBeautyAcademy\Models;

use DsBeautyAcademy\config\connect\DBConnect;
use PDO;
use Exception;

class UsuarioModel extends DBConnect
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Busca un usuario por su correo electrónico incluyendo el rol.
     * Utiliza SELECT u.* para ser tolerante a variaciones de columnas (password_usuario vs contrasena_usuario).
     */
    public function buscarPorEmail(string $email)
    {
        try {
            $sql = "
                SELECT u.*, r.nombre_rol
                FROM usuarios u
                INNER JOIN roles r ON u.id_rol = r.id_rol
                WHERE LOWER(TRIM(u.email_usuario)) = LOWER(TRIM(:email))
                LIMIT 1
            ";

            $stmt = $this->con->prepare($sql);
            $stmt->bindValue(':email', $email, PDO::PARAM_STR);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error en UsuarioModel::buscarPorEmail: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Obtiene el nombre legible de la persona vinculada a la cuenta de usuario.
     */
    public function obtenerNombrePersona(array $usuario): string
    {
        try {
            if (!empty($usuario['id_instructor'])) {
                $stmt = $this->con->prepare("
                    SELECT CONCAT(primer_nombre_instructor, ' ', primer_apellido_instructor) AS nombre 
                    FROM instructores 
                    WHERE id_instructor = :id
                ");
                $stmt->bindValue(':id', $usuario['id_instructor'], PDO::PARAM_INT);
                $stmt->execute();
                $res = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($res && !empty($res['nombre'])) {
                    return trim($res['nombre']);
                }
            }

            if (!empty($usuario['id_estudiante'])) {
                $stmt = $this->con->prepare("
                    SELECT CONCAT(primer_nombre_estudiante, ' ', primer_apellido_estudiante) AS nombre 
                    FROM estudiantes 
                    WHERE id_estudiante = :id
                ");
                $stmt->bindValue(':id', $usuario['id_estudiante'], PDO::PARAM_INT);
                $stmt->execute();
                $res = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($res && !empty($res['nombre'])) {
                    return trim($res['nombre']);
                }
            }
        } catch (Exception $e) {
            // Si la tabla no está creada aún o no existe el registro, retorna el rol
            return $usuario['nombre_rol'] ?? 'Usuario';
        }

        return $usuario['nombre_rol'] ?? 'Administrador';
    }
}
