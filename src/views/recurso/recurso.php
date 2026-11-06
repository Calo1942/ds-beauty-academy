<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recursos | DS Beauty Academy</title>
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
            <i class="fas fa-folder-open"></i>
        </div>
        <div>
            <h1 class="page-header-title">Gestión de Recursos</h1>
            <p class="page-header-subtitle">Materiales didácticos y recursos descargables</p>
        </div>
    </div>
        <div class="page-header-actions mt-3 mt-md-0 d-flex align-items-center">
            
            <button class="btn-custom-primary btn-registrar-recurso" type="button" onclick="abrirModal("modalCrear")">
                <i class="fas fa-plus"></i> Registrar Recurso
            </button>
        </div>
</div>
        <!-- Componente de Tabla DataTables con Skeleton Loader -->
        <?php include __DIR__ . '/components/recursoDataTable.php'; ?>
    </main>

    <!-- Cierre de main-wrapper -->
    </div>

    <!-- Modales del Módulo -->
    <?php include __DIR__ . '/components/modales/recursoModalCrear.php'; ?>
    <?php include __DIR__ . '/components/modales/recursoModalEditar.php'; ?>
    <?php include __DIR__ . '/components/modales/recursoModalDetalle.php'; ?>

    <!-- Scripts y librerías globales (jQuery, Bootstrap, DataTables, SweetAlert2) -->
    <?php echo $scripts_links; ?>

    <!-- Módulos ES6 específicos de Recurso -->
    <script type="module" src="src/assets/js/recurso/recursoValidation.js"></script>
    <script type="module" src="src/assets/js/recurso/recursoDataTable.js"></script>
</body>

</html>