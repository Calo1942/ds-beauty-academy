<?php

namespace DsBeautyAcademy\Controllers;

use DsBeautyAcademy\Models\RolModel;

// Configuración del Módulo
$module_config = [
    'primary_key' => 'id_rol',
    'fields' => ['nombre_rol', 'descripcion_rol', 'estatus_rol'],
    'view_path' => 'rol/rol.php'
];

$model = new RolModel();
$action = null;

// Determinar acción AJAX vía POST
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

// Procesar peticiones AJAX
if ($action) {
    header('Content-Type: application/json');
    switch ($action) {
        case 'guardar':
            $data = [];
            foreach ($module_config['fields'] as $field) {
                if (isset($_POST[$field])) {
                    $data[$field] = $_POST[$field];
                }
            }
            echo json_encode($model->guardar($data));
            exit;

        case 'actualizar':
            $id = $_POST[$module_config['primary_key']] ?? null;
            if (!$id) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'ID no proporcionado para la actualización.'
                ]);
                exit;
            }
            $data = [];
            foreach ($module_config['fields'] as $field) {
                if (isset($_POST[$field])) {
                    $data[$field] = $_POST[$field];
                }
            }
            echo json_encode($model->actualizar($id, $data));
            exit;

        case 'eliminar':
            $id = $_POST['eliminar'] ?? $_POST['id'] ?? null;
            if (!$id) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'ID no proporcionado para la eliminación.'
                ]);
                exit;
            }
            echo json_encode($model->eliminar($id));
            exit;

        case 'buscar':
            $id = $_POST['buscar'] ?? $_POST['id'] ?? null;
            if (!$id) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'ID no proporcionado para la búsqueda.'
                ]);
                exit;
            }
            echo json_encode($model->buscar($id));
            exit;

        case 'buscarTodos':
            echo json_encode($model->buscarTodos());
            exit;
    }
}

// Renderizado de la vista
require_once __DIR__ . '/../views/' . $module_config['view_path'];
