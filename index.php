<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/vendor/autoload.php';
use DsBeautyAcademy\Controllers\FrontController;

try {
    $app = new FrontController();
    $app->run();
} catch (Exception $e) {
    error_log("[DS Beauty Academy] Error en la aplicación: " . $e->getMessage());
    http_response_code(500);
    die("Ha ocurrido un error interno. Por favor, intenta más tarde.");
}
