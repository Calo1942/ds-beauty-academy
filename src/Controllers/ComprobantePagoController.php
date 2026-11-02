<?php

namespace DsBeautyAcademy\Controllers;

use DsBeautyAcademy\Models\ComprobantePagoModel;

$module_config = [
    'primary_key' => 'id_comprobante_pago',
    'fields' => [
        'id_matricula',
        'id_banco',
        'referencia_transaccion',
        'monto',
        'url_img_comprobante',
        'fecha_pago',
        'estatus_pago'
    ],
    'view_path' => 'pago/comprobante_pago.php'
];

$model = new ComprobantePagoModel();
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
} elseif (isset($_POST['conciliar_pago'])) {
    $action = 'conciliar_pago';
}

if ($action) {
    switch ($action) {
        case 'guardar':
            $data = [];
            foreach ($module_config['fields'] as $field) {
                if (isset($_POST[$field])) {
                    $data[$field] = $_POST[$field];
                }
            }

            // Procesar archivo físico del comprobante si fue enviado
            if (isset($_FILES['comprobante_archivo']) && $_FILES['comprobante_archivo']['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES['comprobante_archivo'];
                $allowedMimes = ['image/jpeg', 'image/png', 'image/webp', 'application/pdf'];
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mime = finfo_file($finfo, $file['tmp_name']);
                finfo_close($finfo);

                if (in_array($mime, $allowedMimes, true)) {
                    $uploadDir = __DIR__ . '/../../uploads/comprobantes/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }
                    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                    $filename = 'comprobante_' . uniqid('', true) . '.' . $ext;
                    if (move_uploaded_file($file['tmp_name'], $uploadDir . $filename)) {
                        $data['url_img_comprobante'] = 'uploads/comprobantes/' . $filename;
                    }
                }
            }

            // Valores por defecto
            if (empty($data['estatus_pago'])) {
                $data['estatus_pago'] = 'Pendiente';
            }
            if (empty($data['fecha_pago'])) {
                $data['fecha_pago'] = date('Y-m-d H:i:s');
            } elseif (preg_match('/^\d{4}-\d{2}-\d{2}$/', $data['fecha_pago'])) {
                $data['fecha_pago'] .= ' ' . date('H:i:s');
            }

            $result = $model->guardar($data);
            if ($result) {
                header('Content-Type: application/json');
                echo json_encode($result);
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
                $result = $model->actualizar($id, $data);
                if ($result) {
                    header('Content-Type: application/json; charset=utf-8');
                    echo json_encode($result);
                }
            } else {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode([
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'Falta el ID del comprobante para actualizar'
                ]);
            }
            exit;

        case 'eliminar':
            $id = $_POST['eliminar'] ?? $_POST[$module_config['primary_key']] ?? $_POST['id'] ?? null;
            if ($id) {
                $result = $model->eliminar($id);
                if ($result) {
                    header('Content-Type: application/json; charset=utf-8');
                    echo json_encode($result);
                }
            } else {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode([
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'Falta el ID del comprobante para eliminar'
                ]);
            }
            exit;

        case 'buscar':
            $id = $_POST['buscar'] ?? $_GET['buscar'] ?? $_POST[$module_config['primary_key']] ?? $_POST['id'] ?? null;
            if ($id) {
                $result = $model->buscar($id);
                if ($result) {
                    header('Content-Type: application/json; charset=utf-8');
                    echo json_encode($result);
                }
            } else {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode([
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'Falta el ID del comprobante para consultar'
                ]);
            }
            exit;

        case 'buscarTodos':
            $result = $model->buscarTodos();
            if ($result) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode($result);
            }
            exit;

        case 'conciliar_pago':
            $id = $_POST['id_comprobante_pago'] ?? $_POST[$module_config['primary_key']] ?? $_POST['id'] ?? null;
            $nuevo_estatus = $_POST['estatus_pago'] ?? null;
            if ($id && $nuevo_estatus) {
                $result = $model->conciliarPago($id, $nuevo_estatus);
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode($result);
            } else {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode([
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'Faltan parámetros para conciliar el pago'
                ]);
            }
            exit;
    }
}

// Carga de la vista si es petición GET
$viewFile = __DIR__ . '/../views/' . $module_config['view_path'];
if (file_exists($viewFile)) {
    include $viewFile;
} else {
    echo "<h1>Vista para Comprobantes de Pago en desarrollo</h1>";
}
die();
