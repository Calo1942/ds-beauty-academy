<?php
// Asegurar disponibilidad de la variable $metrics en caso de carga directa
if (!isset($metrics)) {
    $metrics = [
        'kpi' => [
            'estudiantes_activos' => '2,150',
            'estudiantes_trend' => '+12.8%',
            'ingresos_mes' => '$12,450',
            'ingresos_trend' => '+8.4%',
            'inscripciones_confirmadas' => '186',
            'inscripciones_trend' => '+10.1%',
            'cupos_alcanzados_pct' => 78,
            'cupos_alcanzados_detalle' => '156 / 200 cupos'
        ],
        'cursos_mas_vendidos' => [
            ['nombre' => 'Diseño de Cejas', 'cantidad' => 48, 'porcentaje' => 96],
            ['nombre' => 'Pestañas Pelo a Pelo', 'cantidad' => 36, 'porcentaje' => 72],
            ['nombre' => 'Micropigmentación', 'cantidad' => 29, 'porcentaje' => 58],
            ['nombre' => 'Uñas Acrílicas', 'cantidad' => 24, 'porcentaje' => 48],
            ['nombre' => 'Maquillaje Profesional', 'cantidad' => 18, 'porcentaje' => 36]
        ],
        'cupos_cursos' => [
            ['nombre' => 'Diseño de Cejas', 'ocupados' => 18, 'total' => 20, 'porcentaje' => 90],
            ['nombre' => 'Pestañas', 'ocupados' => 14, 'total' => 18, 'porcentaje' => 78],
            ['nombre' => 'Micropigmentación', 'ocupados' => 12, 'total' => 15, 'porcentaje' => 80]
        ],
        'resumen_pagos' => [
            'total' => '$12,450',
            'verificados_monto' => '$9,750',
            'verificados_pct' => 78,
            'pendientes_monto' => '$1,850',
            'pendientes_pct' => 15,
            'rechazados_monto' => '$850',
            'rechazados_pct' => 7
        ]
    ];
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | DS Beauty Academy</title>
    <?php
    // Inclusión centralizada de dependencias CSS y fuentes
    include_once __DIR__ . '/../../assets/linksFront.php';
    echo $css_links;
    ?>
    <!-- Chart.js 4.4 UMD para gráficos interactivos -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
</head>

<body>
    <!-- Sidebar de navegación -->
    <?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>

    <!-- Navbar superior (abre el .main-wrapper) -->
    <?php require_once __DIR__ . '/../layouts/navbar.php'; ?>

    <!-- CONTENIDO PRINCIPAL DEL DASHBOARD (MODULARIZADO) -->
    <main class="content" style="padding: 24px 28px;">
        <div class="container-fluid px-0">
            <h1 class="page-title mb-3">Dashboard</h1>

            <!-- FILA 1: TARJETAS KPI SUPERIORES -->
            <?php include __DIR__ . '/components/kpiCards.php'; ?>

            <!-- FILA 2: GRÁFICO DE INGRESOS MENSUALES + CURSOS MÁS VENDIDOS -->
            <div class="row g-3 g-lg-4 mb-4">
                <div class="col-12 col-xl-8">
                    <?php include __DIR__ . '/components/graficaIngresos.php'; ?>
                </div>
                <div class="col-12 col-xl-4">
                    <?php include __DIR__ . '/components/cursosMasVendidos.php'; ?>
                </div>
            </div>

            <!-- FILA 3: ESTADO INSCRIPCIONES, CUPOS Y RESUMEN DE PAGOS -->
            <div class="row g-3 g-lg-4">
                <div class="col-12 col-md-6 col-lg-4">
                    <?php include __DIR__ . '/components/graficaInscripciones.php'; ?>
                </div>
                <div class="col-12 col-md-6 col-lg-4">
                    <?php include __DIR__ . '/components/cuposAlcanzados.php'; ?>
                </div>
                <div class="col-12 col-md-12 col-lg-4">
                    <?php include __DIR__ . '/components/graficaPagos.php'; ?>
                </div>
            </div>

        </div>
    </main>

    <!-- Cierre del .main-wrapper abierto en navbar.php -->
    </div>

    <!-- Scripts y librerías globales (jQuery, Bootstrap 5, DataTables, SweetAlert2) -->
    <?php echo $scripts_links; ?>

    <!-- Inyección de métricas calculadas en el Backend hacia JavaScript -->
    <script>
        window.DASHBOARD_METRICS = <?= json_encode($metrics, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE) ?>;
    </script>

    <!-- Scripts modulares del Dashboard -->
    <script src="src/assets/js/dashboard/centerTextPlugin.js"></script>
    <script src="src/assets/js/dashboard/chartIngresos.js"></script>
    <script src="src/assets/js/dashboard/chartInscripciones.js"></script>
    <script src="src/assets/js/dashboard/chartPagos.js"></script>
    <script src="src/assets/js/dashboard/dashboardAnimations.js"></script>
</body>

</html>
