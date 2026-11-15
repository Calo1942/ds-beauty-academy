<?php

namespace DsBeautyAcademy\Controllers;

use DsBeautyAcademy\Models\PerfilModel;

$model = new PerfilModel();
$idUsuario = $_SESSION['usuario_id'] ?? null;

// 1. Obtener datos completos del perfil
if (isset($_POST['obtenerPerfil'])) {
    header('Content-Type: application/json; charset=utf-8');
    if (!$idUsuario) {
        echo json_encode(['status' => 'error', 'message' => 'Sesión expirada']);
        exit;
    }
    echo json_encode($model->obtenerPerfilUsuario((int)$idUsuario));
    exit;
}

// 2. Actualizar información del perfil
if (isset($_POST['actualizarPerfil'])) {
    header('Content-Type: application/json; charset=utf-8');
    if (!$idUsuario) {
        echo json_encode(['status' => 'error', 'message' => 'Sesión expirada']);
        exit;
    }
    $pass = $_POST['password_usuario'] ?? '';
    $passConfirm = $_POST['password_confirm'] ?? '';

    if (!empty($pass)) {
        if ($pass !== $passConfirm) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Las contraseñas no coinciden. Por favor, asegúrate de escribir la misma contraseña en ambos campos.'
            ]);
            exit;
        }
        if (strlen($pass) < 6) {
            echo json_encode([
                'status' => 'error',
                'message' => 'La nueva contraseña debe tener al menos 6 caracteres.'
            ]);
            exit;
        }
    }

    $datos = [
        'primer_nombre'        => $_POST['primer_nombre'] ?? '',
        'primer_apellido'      => $_POST['primer_apellido'] ?? '',
        'telefono'             => $_POST['telefono'] ?? '',
        'telefono_alternativo' => $_POST['telefono_alternativo'] ?? '',
        'instagram'            => $_POST['instagram'] ?? '',
        'password_usuario'     => !empty($pass) ? $pass : null
    ];
    $res = $model->actualizarPerfil((int)$idUsuario, $datos);

    // Si se actualizó el nombre, sincronizar la sesión activa
    if (isset($res['status']) && $res['status'] === 'success' && !empty($res['data']['nombre'])) {
        $_SESSION['nombre'] = $res['data']['nombre'];
    }

    echo json_encode($res);
    exit;
}

// 3. Subir o cambiar foto de perfil
if (isset($_POST['subirFotoPerfil'])) {
    header('Content-Type: application/json; charset=utf-8');
    if (!$idUsuario) {
        echo json_encode(['status' => 'error', 'message' => 'Sesión expirada']);
        exit;
    }

    if (!isset($_FILES['foto_perfil']) || $_FILES['foto_perfil']['error'] !== UPLOAD_ERR_OK) {
        echo json_encode([
            'status' => 'error',
            'message' => 'No se seleccionó ninguna imagen o ocurrió un error al subirla.'
        ]);
        exit;
    }

    $file = $_FILES['foto_perfil'];

    // Validar tamaño máximo (5MB)
    if ($file['size'] > 5 * 1024 * 1024) {
        echo json_encode([
            'status' => 'error',
            'message' => 'La imagen no debe superar los 5 MB de tamaño.'
        ]);
        exit;
    }

    // Validar tipo MIME
    $allowedMimes = ['image/jpeg', 'image/png', 'image/webp'];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mime, $allowedMimes, true)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Formato no permitido. Solo se aceptan imágenes JPG, PNG o WEBP.'
        ]);
        exit;
    }

    $uploadDir = __DIR__ . '/../../assets/uploads/perfiles/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Determinar extensión adecuada
    $extensions = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp'
    ];
    $ext = $extensions[$mime] ?? 'jpg';
    $filename = 'perfil_' . (int)$idUsuario . '_' . uniqid() . '.' . $ext;
    $targetPath = $uploadDir . $filename;

    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        $relativeUrl = 'assets/uploads/perfiles/' . $filename;

        // Eliminar foto previa física si existe
        if (!empty($_SESSION['foto_perfil_usuario'])) {
            $oldPath = __DIR__ . '/../../' . ltrim($_SESSION['foto_perfil_usuario'], '/');
            if (file_exists($oldPath) && is_file($oldPath)) {
                @unlink($oldPath);
            }
        }

        $res = $model->actualizarFotoPerfil((int)$idUsuario, $relativeUrl);
        if (isset($res['status']) && $res['status'] === 'success') {
            $_SESSION['foto_perfil_usuario'] = $relativeUrl;
        }
        echo json_encode($res);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error al guardar la imagen en el servidor.'
        ]);
    }
    exit;
}

// 4. Eliminar foto de perfil actual
if (isset($_POST['eliminarFotoPerfil'])) {
    header('Content-Type: application/json; charset=utf-8');
    if (!$idUsuario) {
        echo json_encode(['status' => 'error', 'message' => 'Sesión expirada']);
        exit;
    }

    $res = $model->eliminarFotoPerfil((int)$idUsuario);
    if (isset($res['status']) && $res['status'] === 'success') {
        $_SESSION['foto_perfil_usuario'] = null;
    }
    echo json_encode($res);
    exit;
}

// Cargar vista de perfil
require_once __DIR__ . '/../views/perfil/perfil.php';
