<?php

namespace DsBeautyAcademy\Controllers;

use DsBeautyAcademy\Models\UsuarioModel;
use Throwable;

/**
 * ---------------------------------------------------------
 * Proyecto: DS Beauty Academy
 * Archivo: src/Controllers/RecuperarController.php
 * Descripción:
 * Controlador para el Paso 1 de recuperación de contraseña:
 * Solicitar envío del código de 6 dígitos al correo electrónico.
 * ---------------------------------------------------------
 */

// Si el usuario ya cuenta con una sesión activa, redirigir al Dashboard
if (!empty($_SESSION['usuario_id'])) {
    header('Location: ?url=dashboard');
    exit;
}

$model = new UsuarioModel();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json; charset=utf-8');

    $correo = trim($_POST['correoRecuperar'] ?? '');

    if (empty($correo)) {
        echo json_encode([
            'estado'  => false,
            'status'  => 'error',
            'mensaje' => 'Por favor, ingresa tu correo electrónico registrado.'
        ]);
        exit;
    }

    try {
        $resultado = $model->getSolicitarRecuperacion($correo);

        if (isset($resultado['error'])) {
            echo json_encode([
                'estado'  => false,
                'status'  => 'error',
                'mensaje' => $resultado['error']
            ]);
            exit;
        }

        // Recordamos el correo en sesión para el Paso 2
        $_SESSION['recuperacionCorreo'] = strtolower(trim($correo));

        echo json_encode([
            'estado'  => true,
            'status'  => 'success',
            'mensaje' => $resultado['mensaje'] ?? 'Código generado exitosamente.',
            'datos'   => [
                'siguiente' => '?url=restablecer'
            ]
        ]);
        exit;

    } catch (Throwable $e) {
        error_log("DS Beauty Academy - Error en RecuperarController: " . $e->getMessage());
        echo json_encode([
            'estado'  => false,
            'status'  => 'error',
            'mensaje' => 'Ocurrió un error inesperado al procesar la solicitud.'
        ]);
        exit;
    }
}

// Petición GET: Renderizar la vista
$vistaRecuperar = __DIR__ . '/../views/login/recuperar.php';
if (file_exists($vistaRecuperar)) {
    require $vistaRecuperar;
} else {
    echo "<h1>Error: Vista de recuperación de contraseña no encontrada.</h1>";
}
