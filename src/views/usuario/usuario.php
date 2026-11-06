<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios | DS Beauty Academy</title>
    <?php
    // Inclusión centralizada de dependencias CSS y fuentes
    include_once __DIR__ . '/../../assets/linksFront.php';
    echo $css_links;
    ?>
</head>

<body>
    <!-- Sidebar de navegación -->
    <?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>

    <!-- Navbar superior (abre el .main-wrapper) -->
    <?php require_once __DIR__ . '/../layouts/navbar.php'; ?>

    <main class="content">
        <!-- 1. Encabezado con Icono del Menú, Título y Botón de Registro -->
        <?php include __DIR__ . '/components/usuarioHeader.php'; ?>

        <!-- 2. Componente de Tabla DataTables con Skeleton Loader y Filtros Automáticos -->
        <?php include __DIR__ . '/components/usuarioDataTable.php'; ?>
    </main>

    <!-- Cierre de main-wrapper -->
    </div>

    <!-- Modales del Módulo -->
    <?php include __DIR__ . '/components/modales/usuarioModalCrear.php'; ?>
    <?php include __DIR__ . '/components/modales/usuarioModalEditar.php'; ?>
    <?php include __DIR__ . '/components/modales/usuarioModalDetalle.php'; ?>

    <!-- Scripts y librerías globales (jQuery, Bootstrap, DataTables, SweetAlert2) -->
    <?php echo $scripts_links; ?>

    <!-- Módulo ES6 específico de Usuarios -->
    <script type="module" src="src/assets/js/usuario/usuarioDataTable.js"></script>
</body>

</html>

