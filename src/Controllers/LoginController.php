<?php

namespace DsBeautyAcademy\Controllers;

use DsBeautyAcademy\Models\UsuarioModel;
use Throwable;

$model = new UsuarioModel();

// 1. Acción de Cerrar Sesión (Logout)
if (($_GET['action'] ?? '') === 'logout' || ($_GET['url'] ?? '') === 'logout') {
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }
    session_destroy();
    header('Location: ?url=login');
    exit;
}

// 2. Acción de Inicio de Sesión vía POST (AJAX / Form)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json; charset=utf-8');

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Por favor, ingrese su correo electrónico y contraseña.'
        ]);
        exit;
    }

    try {
        $usuario = $model->buscarPorEmail($email);

        if (!$usuario) {
            echo json_encode([
                'status' => 'error',
                'message' => 'No existe ninguna cuenta registrada con el correo: ' . htmlspecialchars($email)
            ]);
            exit;
        }

        // Verificar estatus del usuario (admite 1, '1', 'Activo', true)
        $estatus = $usuario['estatus_usuario'] ?? 1;
        if ($estatus !== 1 && $estatus !== '1' && $estatus !== true && strtolower((string)$estatus) !== 'activo') {
            echo json_encode([
                'status' => 'error',
                'message' => 'Esta cuenta de usuario se encuentra inactiva en el sistema.'
            ]);
            exit;
        }

        // Obtener el hash de la contraseña (soporta password_usuario o contrasena_usuario)
        $passwordHash = $usuario['password_usuario'] ?? $usuario['contrasena_usuario'] ?? '';

        if (empty($passwordHash) || !password_verify($password, $passwordHash)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Contraseña incorrecta. Por favor, verifique sus datos.'
            ]);
            exit;
        }

        // Credenciales válidas: inicializar sesión
        $_SESSION['usuario_id']    = $usuario['id_usuario'];
        $_SESSION['email']         = $usuario['email_usuario'];
        $_SESSION['rol_id']        = $usuario['id_rol'];
        $_SESSION['rol']           = $usuario['nombre_rol'] ?? 'Administrador';
        $_SESSION['nombre']        = $model->obtenerNombrePersona($usuario);
        $_SESSION['id_instructor'] = $usuario['id_instructor'] ?? null;
        $_SESSION['id_estudiante'] = $usuario['id_estudiante'] ?? null;

        echo json_encode([
            'status' => 'success',
            'message' => '¡Bienvenido(a) a DS Beauty Academy!',
            'redirect' => '?url=dashboard'
        ]);
        exit;

    } catch (Throwable $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error al conectar con la base de datos: ' . $e->getMessage()
        ]);
        exit;
    }
}

// 3. Si ya tiene sesión activa y accede por GET, enviar al Dashboard
if (!empty($_SESSION['usuario_id'])) {
    header('Location: ?url=dashboard');
    exit;
}

// 4. Renderizar vista de login
$loginView = __DIR__ . '/../views/login/login.php';
if (file_exists($loginView)) {
    require $loginView;
} else {
    echo "<h1>Error: Vista de inicio de sesión no encontrada.</h1>";
}
