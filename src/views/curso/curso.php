<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cursos | DS Beauty Academy</title>
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
        <?php include __DIR__ . '/components/cursoHeader.php'; ?>

        <!-- 2. Barra de Filtros (Búsqueda, Tipo, Estado, Botón Filtrar) -->
        <?php include __DIR__ . '/components/cursoFiltros.php'; ?>

        <!-- 3. Componente de Tabla DataTables con Skeleton Loader -->
        <?php include __DIR__ . '/components/cursoDataTable.php'; ?>
    </main>

    <!-- Cierre de main-wrapper -->
    </div>

    <!-- Modales del Módulo -->
    <?php include __DIR__ . '/components/modales/cursoModalCrear.php'; ?>
    <?php include __DIR__ . '/components/modales/cursoModalEditar.php'; ?>
    <?php include __DIR__ . '/components/modales/cursoModalDetalle.php'; ?>

    <!-- Scripts y librerías globales (jQuery, Bootstrap, DataTables, SweetAlert2) -->
    <?php echo $scripts_links; ?>

    <!-- Módulos ES6 específicos de Curso -->
    <script type="module" src="src/assets/js/curso/cursoValidation.js"></script>
    <script type="module" src="src/assets/js/curso/cursoDataTable.js"></script>
</body>

</html>
