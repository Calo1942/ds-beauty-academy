<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diplomas | DS Beauty Academy</title>
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
            <i class="fas fa-certificate"></i>
        </div>
        <div>
            <h1 class="page-header-title">Gestión de Diplomas</h1>
            <p class="page-header-subtitle">Títulos y diplomas oficiales otorgados</p>
        </div>
    </div>
        <div class="page-header-actions mt-3 mt-md-0 d-flex align-items-center">
            
            <button class="btn-custom-primary btn-registrar-diploma" type="button" onclick="abrirModal("modalCrear")">
                <i class="fas fa-plus"></i> Registrar Diploma
            </button>
        </div>
</div>
        <!-- Componente de Tabla DataTables con Skeleton Loader -->
        <?php include __DIR__ . '/components/diplomaDataTable.php'; ?>
    </main>

    <!-- Cierre de main-wrapper -->
    </div>

    <!-- Modales del Módulo -->
    <?php include __DIR__ . '/components/modales/diplomaModalCrear.php'; ?>
    <?php include __DIR__ . '/components/modales/diplomaModalEditar.php'; ?>
    <?php include __DIR__ . '/components/modales/diplomaModalDetalle.php'; ?>

    <!-- Scripts y librerías globales (jQuery, Bootstrap, DataTables, SweetAlert2) -->
    <?php echo $scripts_links; ?>

    <!-- Módulos ES6 específicos de Diploma -->
    <script type="module" src="src/assets/js/diploma/diplomaValidation.js"></script>
    <script type="module" src="src/assets/js/diploma/diplomaDataTable.js"></script>
</body>

</html>