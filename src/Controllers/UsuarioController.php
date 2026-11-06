<?php

namespace DsBeautyAcademy\Controllers;

use DsBeautyAcademy\Models\UsuarioModel;

// Configuración del Módulo
$module_config = [
    'primary_key' => 'id_usuario',
    'fields' => ['email_usuario', 'password_usuario', 'id_rol', 'id_instructor', 'id_estudiante', 'estatus_usuario'],
    'view_path' => 'usuario/usuario.php'
];

$model = new UsuarioModel();
$action = null;

// Determina la acción basándose en las solicitudes POST
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
} elseif (isset($_POST['obtenerRoles'])) {
    $action = 'obtenerRoles';
}

// Procesar acciones AJAX
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
            if ($id) {
                $data = [];
                foreach ($module_config['fields'] as $field) {
                    if (isset($_POST[$field])) {
                        $data[$field] = $_POST[$field];
                    }
                }
                echo json_encode($model->actualizar($id, $data));
            } else {
                echo json_encode(['status' => 'error', 'message' => 'ID no proporcionado']);
            }
            exit;

        case 'eliminar':
            $id = $_POST['eliminar'] ?? null;
            if ($id) {
                echo json_encode($model->eliminar($id));
            } else {
                echo json_encode(['status' => 'error', 'message' => 'ID no proporcionado']);
            }
            exit;

        case 'buscar':
            $id = $_POST['buscar'] ?? null;
            if ($id) {
                echo json_encode($model->buscar($id));
            } else {
                echo json_encode(['status' => 'error', 'message' => 'ID no proporcionado']);
            }
            exit;

        case 'buscarTodos':
            echo json_encode($model->buscarTodos());
            exit;

        case 'obtenerRoles':
            echo json_encode($model->obtenerRoles());
            exit;
    }
}

// Incluir vista de usuario
include __ROOT__ . "/views/" . $module_config['view_path'];
die();

