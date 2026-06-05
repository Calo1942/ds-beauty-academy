<?php

namespace DsBeautyAcademy\Controllers;

use DsBeautyAcademy\Models\DashboardModel;

$model = new DashboardModel();

// Obtener datos del modelo
$stats = $model->getStatistics();
$enrollments = $model->getRecentEnrollments();
$payments = $model->getUpcomingPayments();
$certifications = $model->getLatestCertifications();

// Incluir vista
$viewPath = __DIR__ . '/../views/dashboard.php';
if (defined('__ROOT__')) {
    $viewPath = __ROOT__ . '/views/dashboard.php';
}

if (file_exists($viewPath)) {
    include $viewPath;
} else {
    echo "Vista de Dashboard no encontrada en: " . $viewPath;
}

die();
