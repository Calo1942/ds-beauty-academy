<?php

namespace DsBeautyAcademy\Controllers;

use DsBeautyAcademy\Models\EvaluacionModel;

$module_config = [
    'primary_key' => 'id_evaluacion',
    'fields' => [
        'id_instructor_curso',
        'titulo_evaluacion',
        'descripcion_evaluacion',
        'orden_evaluacion',
        'tipo_evaluacion',
        'estatus_evaluacion'
    ],
    'view_path' => 'evaluaciones.php'
];

$model = new EvaluacionModel();
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
            $id = $_POST['eliminar'] ?? null;
            if ($id) {
                $result = $model->eliminar($id);
                if ($result) {
                    header('Content-Type: application/json');
                    echo json_encode($result);
                }
            }
            exit;

        case 'buscar':
            $id = $_POST['buscar'] ?? null;
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
    }
}

include __ROOT__ . '/views/' . $module_config['view_path'];

die();
