<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificados | DS Beauty Academy</title>
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
            <h1 class="page-title">Certificados Emitidos</h1>
        </div>

        <div class="panel">
            <!-- Skeleton loader mientras carga la información -->
            <div id="certificadoSkeleton" class="skeleton-table">
                <div class="skeleton-header">
                    <div class="skeleton-header-cell" style="flex: 0.5;"></div>
                    <div class="skeleton-header-cell" style="flex: 2;"></div>
                    <div class="skeleton-header-cell" style="flex: 1.5;"></div>
                    <div class="skeleton-header-cell" style="flex: 1.5;"></div>
                    <div class="skeleton-header-cell"></div>
                    <div class="skeleton-header-cell"></div>
                    <div class="skeleton-header-cell"></div>
                    <div class="skeleton-header-cell"></div>
                </div>
                <?php for ($i = 0; $i < 5; $i++): ?>
                    <div class="skeleton-row">
                        <div class="skeleton-cell" style="flex: 0.5;"></div>
                        <div class="skeleton-cell" style="flex: 2;"></div>
                        <div class="skeleton-cell" style="flex: 1.5;"></div>
                        <div class="skeleton-cell" style="flex: 1.5;"></div>
                        <div class="skeleton-cell"></div>
                        <div class="skeleton-cell"></div>
                        <div class="skeleton-cell"></div>
                        <div class="skeleton-cell"></div>
                    </div>
                <?php endfor; ?>
            </div>

            <!-- Contenedor de DataTable -->
            <div class="table-responsive" id="certificadoTableContainer" style="display: none;">
                <table id="certificadoTable" class="table table-dark table-hover w-100">
                    <thead>
                        <tr>
                            <th style="width: 6%;">ID</th>
                            <th style="width: 25%;">Título del Certificado</th>
                            <th style="width: 18%;">Estudiante</th>
                            <th style="width: 17%;">Curso</th>
                            <th style="width: 12%;">Matrícula</th>
                            <th style="width: 10%;">Categoría</th>
                            <th style="width: 12%;">Estatus</th>
                            <th style="width: 10%; text-align: right;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Contenido dinámico mediante DataTables y AJAX -->
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- Cierre de main-wrapper -->
    </div>

    <!-- Scripts y librerías globales (jQuery, Bootstrap, DataTables, SweetAlert2) -->
    <?php echo $scripts_links; ?>

    <!-- Módulo ES6 específico de Certificados -->
    <script type="module" src="src/assets/js/certificado/certificadoDataTable.js"></script>
</body>

</html>
