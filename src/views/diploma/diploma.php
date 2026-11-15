<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diplomas | DS Beauty Academy</title>
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

    <?php
    $userRole = $_SESSION['rol'] ?? 'Administrador';
    $isInstructor = ($userRole === 'Instructor');
    $pageTitle = $isInstructor ? 'Mis Diplomas' : 'Gestión de Diplomas';
    $pageSubtitle = $isInstructor ? 'Mis títulos y diplomas profesionales acreditados' : 'Títulos y diplomas oficiales del staff docente';
    ?>

    <main class="content">
        <div class="custom-page-header d-flex flex-wrap align-items-center justify-content-between mb-4">
            <div class="d-flex align-items-center gap-3">
                <div class="page-header-icon-badge">
                    <i class="fas fa-certificate"></i>
                </div>
                <div>
                    <h1 class="page-header-title"><?= htmlspecialchars($pageTitle) ?></h1>
                    <p class="page-header-subtitle"><?= htmlspecialchars($pageSubtitle) ?></p>
                </div>
            </div>
            <div class="page-header-actions mt-3 mt-md-0 d-flex align-items-center">
                <button class="btn-custom-primary btn-registrar-diploma" type="button">
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

    <!-- Contexto del usuario en sesión para el frontend -->
    <script>
        window.currentUserRole = <?= json_encode($userRole) ?>;
        window.currentInstructorId = <?= json_encode($_SESSION['id_instructor'] ?? null) ?>;
        window.currentUserName = <?= json_encode($_SESSION['nombre'] ?? '') ?>;
    </script>

    <!-- Scripts y librerías globales (jQuery, Bootstrap, DataTables, SweetAlert2) -->
    <?php echo $scripts_links; ?>

    <!-- Módulos ES6 específicos de Diploma -->
    <script type="module" src="assets/js/diploma/diplomaValidation.js?v=2.1"></script>
    <script type="module" src="assets/js/diploma/diplomaDataTable.js?v=2.1"></script>
</body>

</html>