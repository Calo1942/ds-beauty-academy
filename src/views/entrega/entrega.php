<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entregas y Calificaciones | DS Beauty Academy</title>
    <?php
    // Inclusión centralizada de dependencias CSS y fuentes
    include_once __DIR__ . '/../../../assets/linksFront.php';
    echo $css_links;
    ?>
</head>

<body>
    <!-- Sidebar de navegación -->
    <?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>

    <!-- Navbar superior (abre el .main-wrapper) -->
    <?php require_once __DIR__ . '/../layouts/navbar.php'; ?>

    <main class="content">
        <?php include __DIR__ . '/components/entregaHeader.php'; ?>
        <!-- Componente de Tabla DataTables con Skeleton Loader -->
        <?php include __DIR__ . '/components/entregaDataTable.php'; ?>
    </main>

    <!-- Cierre de main-wrapper -->
    </div>

    <!-- Modales del Módulo -->
    <?php include __DIR__ . '/components/modales/entregaModalCalificar.php'; ?>
    <?php include __DIR__ . '/components/modales/entregaModalCrear.php'; ?>
    <?php include __DIR__ . '/components/modales/entregaModalDetalle.php'; ?>

    <!-- Scripts y librerías globales (jQuery, Bootstrap, DataTables, SweetAlert2) -->
    <?php echo $scripts_links; ?>

    <!-- Módulo ES6 específico de Entregas -->
    <script type="module" src="assets/js/entrega/entregaDataTable.js"></script>
</body>

</html>
