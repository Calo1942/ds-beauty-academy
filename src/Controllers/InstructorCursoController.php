<?php

namespace DsBeautyAcademy\Controllers;

use DsBeautyAcademy\Models\InstructorCursoModel;

$module_config = [
    'primary_key' => 'id_instructor_curso',
    'fields' => [
        'id_instructor',
        'id_curso'
    ]
];

$model = new InstructorCursoModel();
$action = null;

if (isset($_POST['guardar'])) {
    $action = 'guardar';
} elseif (isset($_POST['actualizar'])) {
    $action = 'actualizar';
} elseif (isset($_POST['eliminar'])) {
    $action = 'eliminar';
} elseif (isset($_POST['buscar']) || isset($_GET['buscar'])) {
    $action = 'buscar';
} elseif (isset($_POST['buscarTodos']) || isset($_GET['buscarTodos']) || $_SERVER['REQUEST_METHOD'] === 'GET') {
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
                $result = $model->actualizar($id, $data);
                echo json_encode($result);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'Falta el ID de asignación académica a actualizar'
                ]);
            }
            exit;

        case 'eliminar':
            $id = $_POST['eliminar'] ?? $_POST[$module_config['primary_key']] ?? null;
            if ($id) {
                $result = $model->eliminar($id);
                echo json_encode($result);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'Falta el ID de asignación académica a desvincular'
                ]);
            }
            exit;

        case 'buscar':
            $id = $_POST['buscar'] ?? $_GET['buscar'] ?? $_POST[$module_config['primary_key']] ?? null;
            if ($id) {
                $result = $model->buscar($id);
                echo json_encode($result);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'Falta el ID para la búsqueda'
                ]);
            }
            exit;

        case 'buscarTodos':
            $result = $model->buscarTodos();
            echo json_encode($result);
            exit;
    }
}
