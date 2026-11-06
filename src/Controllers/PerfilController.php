<?php

namespace DsBeautyAcademy\Controllers;

use DsBeautyAcademy\Models\PerfilModel;

$model = new PerfilModel();
$idUsuario = $_SESSION['usuario_id'] ?? null;

if (isset($_POST['obtenerPerfil'])) {
    header('Content-Type: application/json');
    if (!$idUsuario) {
        echo json_encode(['status' => 'error', 'message' => 'Sesión expirada']);
        exit;
    }
    echo json_encode($model->obtenerPerfilUsuario((int)$idUsuario));
    exit;
}

if (isset($_POST['actualizarPerfil'])) {
    header('Content-Type: application/json');
    if (!$idUsuario) {
        echo json_encode(['status' => 'error', 'message' => 'Sesión expirada']);
        exit;
    }
    $datos = [
        'telefono' => $_POST['telefono'] ?? '',
        'instagram' => $_POST['instagram'] ?? '',
        'password_usuario' => !empty($_POST['password_usuario']) ? $_POST['password_usuario'] : null
    ];
    echo json_encode($model->actualizarPerfil((int)$idUsuario, $datos));
    exit;
}

// Cargar vista de perfil
require_once __DIR__ . '/../views/perfil/perfil.php';

