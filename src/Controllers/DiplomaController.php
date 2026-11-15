<?php

namespace DsBeautyAcademy\Controllers;

use DsBeautyAcademy\Models\DiplomaModel;

$module_config = [
    'primary_key' => 'id_diploma',
    'fields' => [
        'nombre_diploma',
        'descripcion_diploma',
        'categoria_diploma',
        'url_pdf_diploma',
        'id_instructor',
        'estatus_diploma',
    ],
    'view_path' => 'diploma/diploma.php'
];

$model = new DiplomaModel();
$action = null;

if (isset($_POST['guardar'])) {
    $action = 'guardar';
} elseif (isset($_POST['actualizar'])) {
    $action = 'actualizar';
} elseif (isset($_POST['eliminar'])) {
    $action = 'eliminar';
} elseif (isset($_POST['buscar'])) {
    $action = 'buscar';
} elseif (isset($_POST['buscarTodos'])) {
    $action = 'buscarTodos';
}

if ($action) {
    header('Content-Type: application/json; charset=utf-8');

    switch ($action) {
        case 'guardar':
            $data = [];
            foreach ($module_config['fields'] as $field) {
                if (isset($_POST[$field])) {
                    $data[$field] = $_POST[$field];
                }
            }

            if (($_SESSION['rol'] ?? '') === 'Instructor' && !empty($_SESSION['id_instructor'])) {
                $data['id_instructor'] = (int)$_SESSION['id_instructor'];
            }

            // Procesar archivo físico del diploma si fue adjuntado
            if (isset($_FILES['archivo_diploma']) && $_FILES['archivo_diploma']['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES['archivo_diploma'];
                if ($file['size'] > 5242880) { // 5 MB
                    echo json_encode(['status' => 'error', 'code' => 400, 'message' => 'El archivo excede el tamaño máximo permitido (5 MB).']);
                    exit;
                }
                $allowedMimes = ['application/pdf', 'image/jpeg', 'image/png', 'image/webp'];
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mime = finfo_file($finfo, $file['tmp_name']);
                finfo_close($finfo);

                if (!in_array($mime, $allowedMimes, true)) {
                    echo json_encode(['status' => 'error', 'code' => 400, 'message' => 'Formato no autorizado. Solo se permiten documentos PDF e imágenes (JPG, PNG, WEBP).']);
                    exit;
                }

                $uploadDir = __DIR__ . '/../../assets/uploads/diplomas/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                $filename = 'diploma_' . uniqid('', true) . '.' . $ext;
                if (move_uploaded_file($file['tmp_name'], $uploadDir . $filename)) {
                    $data['url_pdf_diploma'] = 'assets/uploads/diplomas/' . $filename;
                }
            }

            $result = $model->guardar($data);
            echo json_encode($result);
            exit;

        case 'actualizar':
            $id = $_POST[$module_config['primary_key']] ?? null;
            if ($id) {
                $data = [];
                foreach ($module_config['fields'] as $field) {
                    if (isset($_POST[$field])) {
                        $data[$field] = $_POST[$field];
                    }
                }

                if (($_SESSION['rol'] ?? '') === 'Instructor' && !empty($_SESSION['id_instructor'])) {
                    $data['id_instructor'] = (int)$_SESSION['id_instructor'];
                }

                // Procesar archivo físico del diploma si se actualiza
                if (isset($_FILES['archivo_diploma']) && $_FILES['archivo_diploma']['error'] === UPLOAD_ERR_OK) {
                    $file = $_FILES['archivo_diploma'];
                    if ($file['size'] > 5242880) {
                        echo json_encode(['status' => 'error', 'code' => 400, 'message' => 'El archivo excede el tamaño máximo permitido (5 MB).']);
                        exit;
                    }
                    $allowedMimes = ['application/pdf', 'image/jpeg', 'image/png', 'image/webp'];
                    $finfo = finfo_open(FILEINFO_MIME_TYPE);
                    $mime = finfo_file($finfo, $file['tmp_name']);
                    finfo_close($finfo);

                    if (!in_array($mime, $allowedMimes, true)) {
                        echo json_encode(['status' => 'error', 'code' => 400, 'message' => 'Formato no autorizado. Solo se permiten documentos PDF e imágenes (JPG, PNG, WEBP).']);
                        exit;
                    }

                    $uploadDir = __DIR__ . '/../../assets/uploads/diplomas/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }
                    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                    $filename = 'diploma_' . uniqid('', true) . '.' . $ext;
                    if (move_uploaded_file($file['tmp_name'], $uploadDir . $filename)) {
                        $data['url_pdf_diploma'] = 'assets/uploads/diplomas/' . $filename;
                    }
                } elseif (empty($data['url_pdf_diploma'])) {
                    // Si no se cargó nuevo archivo ni se especificó nueva ruta, conservar la previa
                    $actual = $model->buscar($id);
                    if ($actual && isset($actual['data']['url_pdf_diploma'])) {
                        $data['url_pdf_diploma'] = $actual['data']['url_pdf_diploma'];
                    }
                }

                $result = $model->actualizar($id, $data);
                echo json_encode($result);
            } else {
                echo json_encode(['status' => 'error', 'code' => 400, 'message' => 'Falta el identificador del diploma.']);
            }
            exit;

        case 'eliminar':
            $id = $_POST['eliminar'] ?? null;
            if ($id) {
                $result = $model->eliminar($id);
                echo json_encode($result);
            } else {
                echo json_encode(['status' => 'error', 'code' => 400, 'message' => 'Falta el identificador a eliminar.']);
            }
            exit;

        case 'buscar':
            $id = $_POST['buscar'] ?? null;
            if ($id) {
                $result = $model->buscar($id);
                echo json_encode($result);
            } else {
                echo json_encode(['status' => 'error', 'code' => 400, 'message' => 'Falta el identificador a buscar.']);
            }
            exit;

        case 'buscarTodos':
            $result = $model->buscarTodos();
            echo json_encode($result);
            exit;
    }
}

include __ROOT__ . '/views/' . $module_config['view_path'];

die();
