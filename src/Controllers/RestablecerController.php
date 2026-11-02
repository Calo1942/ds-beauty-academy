<?php

namespace DsBeautyAcademy\Controllers;

use DsBeautyAcademy\Models\UsuarioModel;
use Throwable;

/**
 * ---------------------------------------------------------
 * Proyecto: DS Beauty Academy
 * Archivo: src/Controllers/RestablecerController.php
 * Descripción:
 * Controlador para el Paso 2 de recuperación de contraseña:
 * Validar código de 6 dígitos y establecer nueva contraseña.
 * ---------------------------------------------------------
 */

// Si el usuario ya cuenta con una sesión activa, redirigir al Dashboard
if (!empty($_SESSION['usuario_id'])) {
    header('Location: ?url=dashboard');
    exit;
}

$model = new UsuarioModel();

// Correo inicial proveniente de la sesión del paso 1, de la URL o del POST
$correoPrellenado = trim($_POST['correoRecuperar'] ?? $_GET['correo'] ?? $_SESSION['recuperacionCorreo'] ?? '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json; charset=utf-8');

    $correo       = trim($_POST['correoRecuperar'] ?? '');
    $codigo       = trim($_POST['codigoRecuperar'] ?? '');
    $clave        = $_POST['nuevaClave'] ?? '';
    $confirmacion = $_POST['confirmarClave'] ?? '';

    if (empty($correo) || empty($codigo) || empty($clave) || empty($confirmacion)) {
        echo json_encode([
            'estado'  => false,
            'status'  => 'error',
            'mensaje' => 'Por favor, completa todos los campos del formulario.'
        ]);
        exit;
    }

    try {
        $resultado = $model->getRestablecerClaveConCodigo($correo, $codigo, $clave, $confirmacion);

        if (isset($resultado['error'])) {
            echo json_encode([
                'estado'  => false,
                'status'  => 'error',
                'mensaje' => $resultado['error']
            ]);
            exit;
        }

        // Limpiar variable temporal de sesión
        unset($_SESSION['recuperacionCorreo']);

        echo json_encode([
            'estado'  => true,
            'status'  => 'success',
            'mensaje' => $resultado['mensaje'] ?? 'Contraseña actualizada correctamente.',
            'datos'   => [
                'redirect' => '?url=login'
            ]
        ]);
        exit;

    } catch (Throwable $e) {
        error_log("DS Beauty Academy - Error en RestablecerController: " . $e->getMessage());
        echo json_encode([
            'estado'  => false,
            'status'  => 'error',
            'mensaje' => 'Ocurrió un error inesperado al restablecer la contraseña.'
        ]);
        exit;
    }
}

// Petición GET: Renderizar la vista
$vistaRestablecer = __DIR__ . '/../views/login/restablecer.php';
if (file_exists($vistaRestablecer)) {
    require $vistaRestablecer;
} else {
    echo "<h1>Error: Vista para restablecer contraseña no encontrada.</h1>";
}
