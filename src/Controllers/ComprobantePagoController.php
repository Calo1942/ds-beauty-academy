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
            $result = $model->guardar($data);
            if ($result) {
                header('Content-Type: application/json');
                echo json_encode($result);
            }
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
                $result = $model->actualizar($id, $data);
                if ($result) {
                    header('Content-Type: application/json');
                    echo json_encode($result);
                }
            }
            exit;

        case 'eliminar':
            $id = $_POST[$module_config['primary_key']] ?? null;
            if ($id) {
                $result = $model->eliminar($id);
                if ($result) {
                    header('Content-Type: application/json');
                    echo json_encode($result);
                }
            }
            exit;

        case 'buscar':
            $id = $_POST[$module_config['primary_key']] ?? null;
            if ($id) {
                $result = $model->buscar($id);
                if ($result) {
                    header('Content-Type: application/json');
                    echo json_encode($result);
                }
            }
            exit;

        case 'buscarTodos':
            $result = $model->buscarTodos();
            if ($result) {
                header('Content-Type: application/json');
                echo json_encode($result);
            }
            exit;

        case 'conciliar_pago':
            $id = $_POST[$module_config['primary_key']] ?? null;
            $nuevo_estatus = $_POST['estatus_pago'] ?? null;
            if ($id && $nuevo_estatus) {
                $result = $model->conciliarPago($id, $nuevo_estatus);
                header('Content-Type: application/json');
                echo json_encode($result);
            } else {
                header('Content-Type: application/json');
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
