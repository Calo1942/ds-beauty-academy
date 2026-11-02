<?php

namespace DsBeautyAcademy\Controllers;

use DsBeautyAcademy\Models\EntregaEvaluacionModel;

$module_config = [
    'primary_key' => 'id_entrega_evaluacion',
    'fields' => [
        'id_evaluacion',
        'id_matricula',
        'url_archivo_entrega',
        'comentarios_estudiante',
        'calificacion_entrega',
        'feedback_instructora_entrega',
        'fecha_entrega',
        'estatus_entrega'
    ],
    'view_path' => 'entrega/entrega.php'
];

$model = new EntregaEvaluacionModel();
$action = null;

if (isset($_POST['guardar'])) {
    $action = 'guardar';
} elseif (isset($_POST['calificar'])) {
    $action = 'calificar';
} elseif (isset($_POST['actualizar'])) {
    $action = 'actualizar';
} elseif (isset($_POST['eliminar'])) {
    $action = 'eliminar';
} elseif (isset($_POST['buscar']) || isset($_GET['buscar'])) {
    $action = 'buscar';
} elseif (isset($_POST['buscarTodos']) || isset($_GET['buscarTodos']) || (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false && $_SERVER['REQUEST_METHOD'] === 'GET')) {
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

            // Procesar archivo físico de la entrega si fue cargado
            $fileInputKey = isset($_FILES['archivo_entrega']) ? 'archivo_entrega' : (isset($_FILES['url_archivo_entrega']) ? 'url_archivo_entrega' : null);
            if ($fileInputKey && $_FILES[$fileInputKey]['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES[$fileInputKey];
                $allowedMimes = [
                    'image/jpeg', 'image/png', 'image/webp',
                    'application/pdf',
                    'application/zip', 'application/x-rar-compressed', 'application/x-zip-compressed'
                ];
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mime = finfo_file($finfo, $file['tmp_name']);
                finfo_close($finfo);

                if (in_array($mime, $allowedMimes, true)) {
                    $uploadDir = __DIR__ . '/../../uploads/entregas/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }
                    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                    $filename = 'tarea_' . uniqid('', true) . '.' . $ext;
                    if (move_uploaded_file($file['tmp_name'], $uploadDir . $filename)) {
                        $data['url_archivo_entrega'] = 'uploads/entregas/' . $filename;
                    }
                }
            }

            // Valores por defecto para nueva entrega de alumna
            if (empty($data['estatus_entrega'])) {
                $data['estatus_entrega'] = 'Pendiente';
            }
            if (empty($data['fecha_entrega'])) {
                $data['fecha_entrega'] = date('Y-m-d H:i:s');
            }

            $result = $model->guardar($data);
            echo json_encode($result);
            exit;

        case 'calificar':
            $id = $_POST['id_entrega_evaluacion'] ?? $_POST[$module_config['primary_key']] ?? $_POST['id'] ?? null;
            $calificacion = $_POST['calificacion_entrega'] ?? null;
            $feedback = $_POST['feedback_instructora_entrega'] ?? null;
            $estatus = $_POST['estatus_entrega'] ?? 'Aprobada';

            if ($id && $calificacion !== null && $calificacion !== '') {
                $result = $model->calificarEntrega($id, $calificacion, $feedback, $estatus);
                echo json_encode($result);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'Faltan parámetros requeridos para calificar la entrega (ID y Calificación).'
                ]);
            }
            exit;

        case 'actualizar':
            $id = $_POST[$module_config['primary_key']] ?? $_POST['id'] ?? null;
            if ($id) {
                $data = [];
                foreach ($module_config['fields'] as $field) {
                    if (isset($_POST[$field])) {
                        $data[$field] = $_POST[$field];
                    }
                }

                // Archivo si se actualiza
                $fileInputKey = isset($_FILES['archivo_entrega']) ? 'archivo_entrega' : (isset($_FILES['url_archivo_entrega']) ? 'url_archivo_entrega' : null);
                if ($fileInputKey && $_FILES[$fileInputKey]['error'] === UPLOAD_ERR_OK) {
                    $file = $_FILES[$fileInputKey];
                    $uploadDir = __DIR__ . '/../../uploads/entregas/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }
                    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                    $filename = 'tarea_' . uniqid('', true) . '.' . $ext;
                    if (move_uploaded_file($file['tmp_name'], $uploadDir . $filename)) {
                        $data['url_archivo_entrega'] = 'uploads/entregas/' . $filename;
                    }
                }

                $result = $model->actualizar($id, $data);
                echo json_encode($result);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'Falta el ID de la entrega a actualizar.'
                ]);
            }
            exit;

        case 'eliminar':
            $id = $_POST['eliminar'] ?? $_POST[$module_config['primary_key']] ?? $_POST['id'] ?? null;
            if ($id) {
                $result = $model->eliminar($id);
                echo json_encode($result);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'Falta el ID de la entrega a eliminar.'
                ]);
            }
            exit;

        case 'buscar':
            $id = $_POST['buscar'] ?? $_GET['buscar'] ?? $_POST[$module_config['primary_key']] ?? $_POST['id'] ?? null;
            if ($id) {
                $result = $model->buscar($id);
                echo json_encode($result);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'Falta el ID para consultar la entrega.'
                ]);
            }
            exit;

        case 'buscarTodos':
            $result = $model->buscarTodos();
            echo json_encode($result);
            exit;
    }
}

// Carga de la vista si es petición GET
$viewFile = __DIR__ . '/../views/' . $module_config['view_path'];
if (file_exists($viewFile)) {
    include $viewFile;
} else {
    echo "<h1>Vista para Entregas de Evaluaciones en desarrollo</h1>";
}
exit;
