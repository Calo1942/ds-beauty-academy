<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bancos | DS Beauty Academy</title>
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
            <h1 class="page-title">Gestión de Bancos</h1>
            <div class="header-actions">
                <button class="btn btn-primary btn-registrar-banco" type="button" onclick="abrirModal('modalCrear')">
                    <i class="fas fa-plus"></i> Registrar Banco
                </button>
            </div>
        </div>

        <!-- Componente de Tabla DataTables con Skeleton Loader -->
        <?php include __DIR__ . '/components/bancoDataTable.php'; ?>
    </main>

    <!-- Cierre de main-wrapper -->
    </div>

    <!-- Modales del Módulo -->
    <?php include __DIR__ . '/components/modales/bancoModalCrear.php'; ?>
    <?php include __DIR__ . '/components/modales/bancoModalEditar.php'; ?>
    <?php include __DIR__ . '/components/modales/bancoModalDetalle.php'; ?>

    <!-- Scripts y librerías globales (jQuery, Bootstrap, DataTables, SweetAlert2) -->
    <?php echo $scripts_links; ?>

    <!-- Módulos ES6 específicos de Banco -->
    <script type="module" src="src/assets/js/banco/bancoValidation.js"></script>
    <script type="module" src="src/assets/js/banco/bancoDataTable.js"></script>
</body>

</html>