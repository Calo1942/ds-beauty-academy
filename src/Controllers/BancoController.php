<?php

namespace DsBeautyAcademy\Controllers;

use DsBeautyAcademy\Models\BancoModel;

// Configuración del Módulo
$module_config = [
    'primary_key' => 'id_banco',
    'fields' => ['nombre', 'estatus_banco', 'fecha_creacion', 'fecha_actualizacion'],
    'view_path' => 'banco.php'
];

$model = new BancoModel();
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
}

// Procesar acciones
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

// Incluir vista
include __ROOT__ . '/views/' . $module_config['view_path'];

die();