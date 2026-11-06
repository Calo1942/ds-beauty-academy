<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Especialidades | DS Beauty Academy</title>
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
            <i class="fas fa-star"></i>
        </div>
        <div>
            <h1 class="page-header-title">Gestión de Especialidades</h1>
            <p class="page-header-subtitle">Áreas de experticia y ramos profesionales</p>
        </div>
    </div>
        <div class="page-header-actions mt-3 mt-md-0 d-flex align-items-center">
            
            <button class="btn-custom-primary btn-registrar-especialidad" type="button" onclick="abrirModal('modalCrear')">
                <i class="fas fa-plus"></i> Registrar Especialidad
            </button>
        </div>
</div>
        <!-- Componente de Tabla DataTables con Skeleton Loader -->
        <?php include __DIR__ . '/components/especialidadesDataTable.php'; ?>
    </main>

    <!-- Cierre de main-wrapper -->
    </div>

    <!-- Modales del Módulo -->
    <?php include __DIR__ . '/components/modales/especialidadesModalCrear.php'; ?>
    <?php include __DIR__ . '/components/modales/especialidadesModalEditar.php'; ?>
    <?php include __DIR__ . '/components/modales/especialidadesModalDetalle.php'; ?>

    <!-- Scripts y librerías globales (jQuery, Bootstrap, DataTables, SweetAlert2) -->
    <?php echo $scripts_links; ?>

    <!-- Módulos ES6 específicos de Especialidad -->
    <script type="module" src="src/assets/js/especialidad/especialidadValidation.js"></script>
    <script type="module" src="src/assets/js/especialidad/especialidadDataTable.js"></script>
</body>

</html>