<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roles | DS Beauty Academy</title>
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
        <!-- 1. Encabezado con Icono del Menú, Título y Botón de Registro (sin botón de reporte) -->
        <?php include __DIR__ . '/components/rolHeader.php'; ?>

        <!-- 2. Componente de Tabla DataTables con Skeleton Loader -->
        <?php include __DIR__ . '/components/rolDataTable.php'; ?>
    </main>

    <!-- Cierre de main-wrapper -->
    </div>

    <!-- Modales del Módulo -->
    <?php include __DIR__ . '/components/modales/rolModalCrear.php'; ?>
    <?php include __DIR__ . '/components/modales/rolModalEditar.php'; ?>
    <?php include __DIR__ . '/components/modales/rolModalDetalle.php'; ?>

    <!-- Scripts y librerías globales (jQuery, Bootstrap, DataTables, SweetAlert2) -->
    <?php echo $scripts_links; ?>

    <!-- Módulo ES6 específico de Roles -->
    <script type="module" src="assets/js/rol/rolDataTable.js"></script>
</body>

</html>
