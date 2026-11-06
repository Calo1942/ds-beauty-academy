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
        <div class="custom-page-header d-flex flex-wrap align-items-center justify-content-between mb-4">
    <div class="d-flex align-items-center gap-3">
        <div class="page-header-icon-badge">
            <i class="fas fa-chalkboard-teacher"></i>
        </div>
        <div>
            <h1 class="page-header-title">Gestión de Instructores</h1>
            <p class="page-header-subtitle">Administra y controla el equipo docente de la academia</p>
        </div>
    </div>
        <div class="page-header-actions mt-3 mt-md-0 d-flex align-items-center">
            
            <button class="btn-custom-primary btn-registrar-instructor" type="button" onclick="abrirModal('modalCrear')">
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