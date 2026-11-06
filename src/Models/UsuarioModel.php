<?php

namespace DsBeautyAcademy\Models;

use DsBeautyAcademy\config\connect\DBConnect;
use DsBeautyAcademy\config\interfaces\Crud;
use DsBeautyAcademy\Helpers\ApiResponse;
use DsBeautyAcademy\Helpers\Validations;
use DsBeautyAcademy\Helpers\CorreoTrait;
use PDO;
use PDOException;
use Exception;

/**
 * ---------------------------------------------------------
 * Proyecto: DS Beauty Academy
 * Archivo: src/Models/UsuarioModel.php
 * Descripción:
 * Modelo para autenticación, gestión de cuentas de usuario
 * y recuperación de contraseña bajo los estándares académicos
 * y de seguridad (métodos puente getX vs métodos privados).
 * ---------------------------------------------------------
 */
class UsuarioModel extends DBConnect implements Crud
{
    use ApiResponse, Validations, CorreoTrait;

    // Atributos privados para el encapsulamiento
    private $idUsuario;
    private $emailUsuario;
    private $passwordUsuario;
    private $codigoRecuperacion;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Busca un usuario por su correo electrónico incluyendo el rol.
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
            return $usuario['nombre_rol'] ?? 'Usuario';
        }

        return $usuario['nombre_rol'] ?? 'Administrador';
    }

    // =========================================================
    // VALIDACIONES Y ENCAPSULAMIENTO (MÉTODOS PRIVADOS/GET)
    // =========================================================

    /**
     * Capa de validación jerárquica para la recuperación de contraseñas.
     */
    public function validarRecuperacion(string $tipo, array $datos)
    {
        $expCorreo = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";
        $expCodigo = "/^[0-9]{6}$/";
        // Mínimo 8 caracteres, al menos una letra y un número
        $expClave  = "/^(?=.*[A-Za-z])(?=.*\d).{8,72}$/";

        switch ($tipo) {
            case 'correo':
                $correo = trim($datos['correo'] ?? '');
                if ($correo === '' || !preg_match($expCorreo, $correo)) {
                    return ['error' => 'Ingresa un formato de correo electrónico válido.'];
                }
                $this->emailUsuario = strtolower($correo);
                return ['respuesta' => true];

            case 'restablecer':
                $correo       = trim($datos['correo'] ?? '');
                $codigo       = trim($datos['codigo'] ?? '');
                $clave        = $datos['clave'] ?? '';
                $confirmacion = $datos['confirmacion'] ?? '';

                if ($correo === '' || !preg_match($expCorreo, $correo)) {
                    return ['error' => 'El correo electrónico no es válido.'];
                }
                if ($codigo === '' || !preg_match($expCodigo, $codigo)) {
                    return ['error' => 'El código de recuperación debe contener exactamente 6 dígitos numéricos.'];
                }
                if ($clave === '' || !preg_match($expClave, $clave)) {
                    return ['error' => 'La nueva contraseña debe tener al menos 8 caracteres e incluir letras y números.'];
                }
                if ($clave !== $confirmacion) {
                    return ['error' => 'Las contraseñas ingresadas no coinciden.'];
                }

                $this->emailUsuario       = strtolower($correo);
                $this->codigoRecuperacion = $codigo;
                $this->passwordUsuario    = $clave;
                return ['respuesta' => true];
        }

        return ['error' => 'Tipo de validación no reconocido.'];
    }

    // =========================================================
    // PASO 1: SOLICITAR CÓDIGO DE RECUPERACIÓN (6 DÍGITOS)
    // =========================================================

    /**
     * Método puente público (+) para solicitar código de recuperación.
     */
    public function getSolicitarRecuperacion(string $correo): array
    {
        $validacion = $this->validarRecuperacion('correo', ['correo' => $correo]);
        if (isset($validacion['error'])) {
            return $validacion;
        }

        return $this->solicitarRecuperacion();
    }

    /**
     * Método privado (-) que genera el código y actualiza la base de datos.
     */
    private function solicitarRecuperacion(): array
    {
        if ($this->emailUsuario === null) {
            return ['error' => 'Primero debes validar el correo electrónico.'];
        }

        // Mensaje genérico para mitigar ataques de enumeración de usuarios
        $mensajeGenerico = 'Si el correo está registrado en DS Beauty Academy, recibirás un código de 6 dígitos válido por 10 minutos. Espera un minuto antes de solicitar otro.';

        try {
            $config   = $this->obtenerConfiguracionCorreo();
            $cooldown = (int) ($config['cooldown'] ?? 60);
            $vigencia = (int) ($config['vigencia'] ?? 10);

            // Consulta de usuario con verificación de cooldown
            $sql = "
                SELECT id_usuario, id_instructor, id_estudiante, id_rol,
                       (reenvio_recuperacion IS NOT NULL AND reenvio_recuperacion > DATE_SUB(NOW(), INTERVAL :cooldown SECOND)) AS reenvio_reciente
                FROM usuarios
                WHERE LOWER(TRIM(email_usuario)) = :email AND estatus_usuario = 1
                LIMIT 1
            ";
            $stmt = $this->con->prepare($sql);
            $stmt->bindValue(':cooldown', $cooldown, PDO::PARAM_INT);
            $stmt->bindValue(':email', $this->emailUsuario, PDO::PARAM_STR);
            $stmt->execute();
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            // Si el correo no existe o está inactivo, retornamos éxito genérico para no dar pistas
            if (!$usuario) {
                return ['respuesta' => true, 'mensaje' => $mensajeGenerico];
            }

            // Si está dentro de la ventana de espera (cooldown de 60 segundos)
            if ((int) $usuario['reenvio_reciente'] === 1) {
                return ['respuesta' => true, 'mensaje' => $mensajeGenerico];
            }

            // Generación de código numérico de 6 dígitos aleatorio seguro
            $codigo = (string) random_int(100000, 999999);
            // Almacenar únicamente el hash del código por seguridad
            $hashCodigo = password_hash($codigo, PASSWORD_DEFAULT);

            // Actualización atómica en la tabla usuarios
            $sqlUpdate = "
                UPDATE usuarios
                SET token_recuperacion = :token,
                    expira_recuperacion = DATE_ADD(NOW(), INTERVAL :vigencia MINUTE),
                    reenvio_recuperacion = NOW(),
                    intentos_recuperacion = 0
                WHERE id_usuario = :id
            ";
            $stmtUpdate = $this->con->prepare($sqlUpdate);
            $stmtUpdate->bindValue(':token', $hashCodigo, PDO::PARAM_STR);
            $stmtUpdate->bindValue(':vigencia', $vigencia, PDO::PARAM_INT);
            $stmtUpdate->bindValue(':id', $usuario['id_usuario'], PDO::PARAM_INT);
            $stmtUpdate->execute();

            $this->idUsuario          = (int) $usuario['id_usuario'];
            $this->codigoRecuperacion = $codigo;

            // Obtener el nombre para personalizar el correo
            $nombrePersona = $this->obtenerNombrePersona($usuario);

            // Enviar correo con PHPMailer mediante CorreoTrait
            $envio = $this->enviarCodigoRecuperacion($this->emailUsuario, $nombrePersona, $codigo, $vigencia);

            if (isset($envio['error'])) {
                // Si el envío falla, anulamos el código en la base de datos
                $stmtAnular = $this->con->prepare("
                    UPDATE usuarios
                    SET token_recuperacion = NULL, expira_recuperacion = NULL
                    WHERE id_usuario = :id AND token_recuperacion = :token
                ");
                $stmtAnular->bindValue(':id', $usuario['id_usuario'], PDO::PARAM_INT);
                $stmtAnular->bindValue(':token', $hashCodigo, PDO::PARAM_STR);
                $stmtAnular->execute();

                error_log("DS Beauty Academy: Falló el envío de código a {$this->emailUsuario}: " . $envio['error']);

                return ['error' => $envio['error']];
            }

            return ['respuesta' => true, 'mensaje' => $mensajeGenerico];

        } catch (PDOException $e) {
            error_log("DS Beauty Academy - Error SQL en solicitarRecuperacion: " . $e->getMessage());
            return ['error' => 'Error interno de base de datos al procesar la solicitud.'];
        }
    }

    // =========================================================
    // PASO 2: RESTABLECER CONTRASEÑA CON CÓDIGO
    // =========================================================

    /**
     * Método puente público (+) para validar código y guardar nueva clave.
     */
    public function getRestablecerClaveConCodigo(string $correo, string $codigo, string $clave, string $confirmacion): array
    {
        $validacion = $this->validarRecuperacion('restablecer', [
            'correo'       => $correo,
            'codigo'       => $codigo,
            'clave'        => $clave,
            'confirmacion' => $confirmacion
        ]);

        if (isset($validacion['error'])) {
            return $validacion;
        }

        return $this->restablecerClaveConCodigo();
    }

    /**
     * Método privado (-) que valida el código y actualiza la contraseña de forma atómica.
     */
    private function restablecerClaveConCodigo(): array
    {
        if ($this->emailUsuario === null || $this->codigoRecuperacion === null || $this->passwordUsuario === null) {
            return ['error' => 'Datos incompletos para restablecer la contraseña.'];
        }

        $mensajeErrorGenerico = 'El código es incorrecto, ya expiró o excedió el número de intentos permitidos. Por favor solicita uno nuevo.';

        try {
            // Consultar datos del usuario
            $stmt = $this->con->prepare("
                SELECT id_usuario, token_recuperacion, intentos_recuperacion, expira_recuperacion
                FROM usuarios
                WHERE LOWER(TRIM(email_usuario)) = :email AND estatus_usuario = 1
                LIMIT 1
            ");
            $stmt->bindValue(':email', $this->emailUsuario, PDO::PARAM_STR);
            $stmt->execute();
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$usuario || empty($usuario['token_recuperacion'])) {
                return ['error' => $mensajeErrorGenerico];
            }

            if ((int) $usuario['intentos_recuperacion'] >= 5) {
                return ['error' => $mensajeErrorGenerico];
            }

            // Consumo atómico del intento (previene colisiones y ataques de fuerza bruta)
            $stmtIntento = $this->con->prepare("
                UPDATE usuarios
                SET intentos_recuperacion = intentos_recuperacion + 1
                WHERE id_usuario = :id
                  AND token_recuperacion = :token
                  AND intentos_recuperacion < 5
                  AND expira_recuperacion IS NOT NULL
                  AND expira_recuperacion >= NOW()
            ");
            $stmtIntento->bindValue(':id', $usuario['id_usuario'], PDO::PARAM_INT);
            $stmtIntento->bindValue(':token', $usuario['token_recuperacion'], PDO::PARAM_STR);
            $stmtIntento->execute();

            if ($stmtIntento->rowCount() !== 1) {
                return ['error' => $mensajeErrorGenerico];
            }

            // Comprobación segura contra el hash del código de 6 dígitos
            if (!password_verify($this->codigoRecuperacion, $usuario['token_recuperacion'])) {
                return ['error' => $mensajeErrorGenerico];
            }

            // Código correcto: actualizar la contraseña y limpiar tokens de seguridad
            $nuevoHash = password_hash($this->passwordUsuario, PASSWORD_BCRYPT);
            $stmtActualizar = $this->con->prepare("
                UPDATE usuarios
                SET password_usuario = :password,
                    token_recuperacion = NULL,
                    expira_recuperacion = NULL,
                    reenvio_recuperacion = NULL,
                    intentos_recuperacion = 0,
                    login_intentos = 0,
                    login_bloqueado_hasta = NULL
                WHERE id_usuario = :id
            ");
            $stmtActualizar->bindValue(':password', $nuevoHash, PDO::PARAM_STR);
            $stmtActualizar->bindValue(':id', $usuario['id_usuario'], PDO::PARAM_INT);
            $stmtActualizar->execute();

            $this->idUsuario = (int) $usuario['id_usuario'];

            return [
                'respuesta' => true,
                'mensaje'   => '¡Contraseña restablecida exitosamente! Ya puedes iniciar sesión con tu nueva credencial.'
            ];

        } catch (PDOException $e) {
            error_log("DS Beauty Academy - Error SQL en restablecerClaveConCodigo: " . $e->getMessage());
            return ['error' => 'Error en el servidor al intentar actualizar la contraseña.'];
        }
    }

    // =========================================================
    // IMPLEMENTACIÓN CRUD PARA GESTIÓN DE USUARIOS
    // =========================================================

    public function buscarTodos()
    {
        try {
            $sql = "
                SELECT 
                    u.id_usuario,
                    u.email_usuario,
                    u.id_rol,
                    r.nombre_rol,
                    u.id_instructor,
                    u.id_estudiante,
                    u.foto_perfil_usuario,
                    u.estatus_usuario,
                    u.fecha_creacion,
                    CASE 
                        WHEN u.id_instructor IS NOT NULL THEN CONCAT(COALESCE(i.primer_nombre_instructor, ''), ' ', COALESCE(i.primer_apellido_instructor, ''))
                        WHEN u.id_estudiante IS NOT NULL THEN CONCAT(COALESCE(e.primer_nombre_estudiante, ''), ' ', COALESCE(e.primer_apellido_estudiante, ''))
                        ELSE 'Administrador General'
                    END AS nombre_persona
                FROM usuarios u
                INNER JOIN roles r ON u.id_rol = r.id_rol
                LEFT JOIN instructores i ON u.id_instructor = i.id_instructor
                LEFT JOIN estudiantes e ON u.id_estudiante = e.id_estudiante
                ORDER BY u.id_usuario DESC
            ";
            $stmt = $this->con->query($sql);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $this->success(200, "Usuarios obtenidos", $result);
        } catch (Exception $e) {
            return $this->error(500, "Error al obtener usuarios", $e->getMessage());
        }
    }

    public function buscar($id)
    {
        try {
            $stmt = $this->con->prepare("
                SELECT 
                    u.id_usuario,
                    u.email_usuario,
                    u.id_rol,
                    r.nombre_rol,
                    u.id_instructor,
                    u.id_estudiante,
                    u.foto_perfil_usuario,
                    u.estatus_usuario,
                    u.fecha_creacion,
                    CASE 
                        WHEN u.id_instructor IS NOT NULL THEN CONCAT(COALESCE(i.primer_nombre_instructor, ''), ' ', COALESCE(i.primer_apellido_instructor, ''))
                        WHEN u.id_estudiante IS NOT NULL THEN CONCAT(COALESCE(e.primer_nombre_estudiante, ''), ' ', COALESCE(e.primer_apellido_estudiante, ''))
                        ELSE 'Administrador General'
                    END AS nombre_persona
                FROM usuarios u
                INNER JOIN roles r ON u.id_rol = r.id_rol
                LEFT JOIN instructores i ON u.id_instructor = i.id_instructor
                LEFT JOIN estudiantes e ON u.id_estudiante = e.id_estudiante
                WHERE u.id_usuario = :id
                LIMIT 1
            ");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                return $this->success(200, "Usuario obtenido", $result);
            }
            return $this->error(404, "Usuario no encontrado");
        } catch (Exception $e) {
            return $this->error(500, "Error al obtener usuario", $e->getMessage());
        }
    }

    public function guardar($data)
    {
        try {
            $email = trim($data['email_usuario'] ?? '');
            $password = $data['password_usuario'] ?? '';
            $id_rol = (int)($data['id_rol'] ?? 0);
            $id_instructor = !empty($data['id_instructor']) ? (int)$data['id_instructor'] : null;
            $id_estudiante = !empty($data['id_estudiante']) ? (int)$data['id_estudiante'] : null;
            $estatus = isset($data['estatus_usuario']) ? (int)$data['estatus_usuario'] : 1;

            if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return $this->error(400, "Correo electrónico inválido");
            }
            if (strlen($password) < 6) {
                return $this->error(400, "La contraseña debe tener al menos 6 caracteres");
            }
            if ($id_rol <= 0) {
                return $this->error(400, "Debe seleccionar un rol válido");
            }

            // Verificar si el correo ya existe
            $stmtCheck = $this->con->prepare("SELECT id_usuario FROM usuarios WHERE LOWER(TRIM(email_usuario)) = LOWER(TRIM(:email))");
            $stmtCheck->bindValue(':email', $email, PDO::PARAM_STR);
            $stmtCheck->execute();
            if ($stmtCheck->fetch()) {
                return $this->error(400, "El correo electrónico ya está registrado");
            }

            $hashPassword = password_hash($password, PASSWORD_BCRYPT);

            $stmt = $this->con->prepare("
                INSERT INTO usuarios (email_usuario, password_usuario, id_rol, id_instructor, id_estudiante, estatus_usuario)
                VALUES (:email, :password, :id_rol, :id_instructor, :id_estudiante, :estatus)
            ");
            $stmt->bindValue(':email', $email, PDO::PARAM_STR);
            $stmt->bindValue(':password', $hashPassword, PDO::PARAM_STR);
            $stmt->bindValue(':id_rol', $id_rol, PDO::PARAM_INT);
            $stmt->bindValue(':id_instructor', $id_instructor, $id_instructor ? PDO::PARAM_INT : PDO::PARAM_NULL);
            $stmt->bindValue(':id_estudiante', $id_estudiante, $id_estudiante ? PDO::PARAM_INT : PDO::PARAM_NULL);
            $stmt->bindValue(':estatus', $estatus, PDO::PARAM_INT);
            $stmt->execute();

            return $this->success(201, "Usuario creado exitosamente");
        } catch (Exception $e) {
            return $this->error(500, "Error al crear usuario", $e->getMessage());
        }
    }

    public function actualizar($id, $data)
    {
        try {
            $id = (int)$id;
            $email = trim($data['email_usuario'] ?? '');
            $password = $data['password_usuario'] ?? '';
            $id_rol = (int)($data['id_rol'] ?? 0);
            $id_instructor = !empty($data['id_instructor']) ? (int)$data['id_instructor'] : null;
            $id_estudiante = !empty($data['id_estudiante']) ? (int)$data['id_estudiante'] : null;
            $estatus = isset($data['estatus_usuario']) ? (int)$data['estatus_usuario'] : 1;

            if ($id <= 0) {
                return $this->error(400, "ID inválido");
            }
            if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return $this->error(400, "Correo electrónico inválido");
            }
            if ($id_rol <= 0) {
                return $this->error(400, "Debe seleccionar un rol válido");
            }

            // Verificar si el correo ya le pertenece a otro usuario
            $stmtCheck = $this->con->prepare("SELECT id_usuario FROM usuarios WHERE LOWER(TRIM(email_usuario)) = LOWER(TRIM(:email)) AND id_usuario != :id");
            $stmtCheck->bindValue(':email', $email, PDO::PARAM_STR);
            $stmtCheck->bindValue(':id', $id, PDO::PARAM_INT);
            $stmtCheck->execute();
            if ($stmtCheck->fetch()) {
                return $this->error(400, "El correo electrónico ya está registrado por otro usuario");
            }

            if (!empty($password)) {
                if (strlen($password) < 6) {
                    return $this->error(400, "La nueva contraseña debe tener al menos 6 caracteres");
                }
                $hashPassword = password_hash($password, PASSWORD_BCRYPT);
                $stmt = $this->con->prepare("
                    UPDATE usuarios 
                    SET email_usuario = :email,
                        password_usuario = :password,
                        id_rol = :id_rol,
                        id_instructor = :id_instructor,
                        id_estudiante = :id_estudiante,
                        estatus_usuario = :estatus
                    WHERE id_usuario = :id
                ");
                $stmt->bindValue(':password', $hashPassword, PDO::PARAM_STR);
            } else {
                $stmt = $this->con->prepare("
                    UPDATE usuarios 
                    SET email_usuario = :email,
                        id_rol = :id_rol,
                        id_instructor = :id_instructor,
                        id_estudiante = :id_estudiante,
                        estatus_usuario = :estatus
                    WHERE id_usuario = :id
                ");
            }

            $stmt->bindValue(':email', $email, PDO::PARAM_STR);
            $stmt->bindValue(':id_rol', $id_rol, PDO::PARAM_INT);
            $stmt->bindValue(':id_instructor', $id_instructor, $id_instructor ? PDO::PARAM_INT : PDO::PARAM_NULL);
            $stmt->bindValue(':id_estudiante', $id_estudiante, $id_estudiante ? PDO::PARAM_INT : PDO::PARAM_NULL);
            $stmt->bindValue(':estatus', $estatus, PDO::PARAM_INT);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            return $this->success(200, "Usuario actualizado exitosamente");
        } catch (Exception $e) {
            return $this->error(500, "Error al actualizar usuario", $e->getMessage());
        }
    }

    public function eliminar($id)
    {
        try {
            $id = (int)$id;
            if ($id <= 0) {
                return $this->error(400, "ID inválido");
            }

            // Desactivación lógica
            $stmt = $this->con->prepare("UPDATE usuarios SET estatus_usuario = 0 WHERE id_usuario = :id");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            return $this->success(200, "Usuario desactivado correctamente");
        } catch (Exception $e) {
            return $this->error(500, "Error al desactivar usuario", $e->getMessage());
        }
    }

    public function obtenerRoles()
    {
        try {
            $stmt = $this->con->query("SELECT id_rol, nombre_rol FROM roles WHERE estatus_rol = 1 ORDER BY id_rol ASC");
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $this->success(200, "Roles obtenidos", $result);
        } catch (Exception $e) {
            return $this->error(500, "Error al obtener roles", $e->getMessage());
        }
    }
}
