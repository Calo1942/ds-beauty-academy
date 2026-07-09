<?php

namespace DsBeautyAcademy\Controllers;

use DsBeautyAcademy\Models\EstudianteModel;

// Configuración del Módulo
$module_config = [
    'primary_key' => 'id_estudiante',
    'fields' => [
        'primer_nombre_estudiante',
        'segundo_nombre_estudiante',
        'primer_apellido_estudiante',
        'segundo_apellido_estudiante',
        'cedula_estudiante',
        'correo_estudiante',
        'telefono_principal_estudiante',
        'telefono_alternativo_estudiante',
        'usuario_instagram_estudiante',
        'estatus_estudiante'
    ],
    'view_path' => 'estudiante/estudiante.php'
];

$model = new EstudianteModel();
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
