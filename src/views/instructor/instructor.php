<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instructores | DS Beauty Academy</title>
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
        <div class="page-header">
            <h1 class="page-title">Gestión de Instructores</h1>
            <div class="header-actions">
                <button class="btn btn-primary btn-registrar-instructor" type="button" onclick="abrirModal('modalCrear')">
                    <i class="fas fa-plus"></i> Registrar Instructor
                </button>
            </div>
        </div>

        <!-- Componente de Tabla DataTables con Skeleton Loader -->
        <?php include __DIR__ . '/components/instructorDataTable.php'; ?>
    </main>

    <!-- Cierre de main-wrapper -->
    </div>

    <!-- Modales del Módulo -->
    <?php include __DIR__ . '/components/modales/instructorModalCrear.php'; ?>
    <?php include __DIR__ . '/components/modales/instructorModalEditar.php'; ?>
    <?php include __DIR__ . '/components/modales/instructorModalDetalle.php'; ?>

    <!-- Scripts y librerías globales (jQuery, Bootstrap, DataTables, SweetAlert2) -->
    <?php echo $scripts_links; ?>

    <!-- Módulos ES6 específicos de Instructor -->
    <script type="module" src="src/assets/js/instructor/instructorValidation.js"></script>
    <script type="module" src="src/assets/js/instructor/instructorDataTable.js"></script>
</body>

</html>