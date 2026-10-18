<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Matrículas | DS Beauty Academy</title>
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
            <h1 class="page-title">Gestión de Matrículas</h1>
        </div>

        <div class="panel">
            <!-- Skeleton loader mientras carga la información -->
            <div id="matriculaSkeleton" class="skeleton-table">
                <div class="skeleton-header">
                    <div class="skeleton-header-cell" style="flex: 0.5;"></div>
                    <div class="skeleton-header-cell" style="flex: 2;"></div>
                    <div class="skeleton-header-cell"></div>
                    <div class="skeleton-header-cell" style="flex: 1.5;"></div>
                    <div class="skeleton-header-cell"></div>
                    <div class="skeleton-header-cell"></div>
                    <div class="skeleton-header-cell"></div>
                    <div class="skeleton-header-cell"></div>
                    <div class="skeleton-header-cell"></div>
                </div>
                <?php for ($i = 0; $i < 5; $i++): ?>
                    <div class="skeleton-row">
                        <div class="skeleton-cell" style="flex: 0.5;"></div>
                        <div class="skeleton-cell" style="flex: 2;"></div>
                        <div class="skeleton-cell"></div>
                        <div class="skeleton-cell" style="flex: 1.5;"></div>
                        <div class="skeleton-cell"></div>
                        <div class="skeleton-cell"></div>
                        <div class="skeleton-cell"></div>
                        <div class="skeleton-cell"></div>
                        <div class="skeleton-cell"></div>
                    </div>
                <?php endfor; ?>
            </div>

            <!-- Contenedor de DataTable -->
            <div class="table-responsive" id="matriculaTableContainer" style="display: none;">
                <table id="matriculaTable" class="table table-dark table-hover w-100">
                    <thead>
                        <tr>
                            <th style="width: 6%;">ID</th>
                            <th style="width: 18%;">Estudiante</th>
                            <th style="width: 11%;">Cédula</th>
                            <th style="width: 18%;">Curso</th>
                            <th style="width: 14%;">Instructor</th>
                            <th style="width: 10%;">Precio</th>
                            <th style="width: 8%;">Pago</th>
                            <th style="width: 8%;">Cupo</th>
                            <th style="width: 7%; text-align: right;">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Contenido cargado dinámicamente mediante DataTables y AJAX -->
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- Cierre de main-wrapper -->
    </div>

    <!-- Scripts y librerías globales (jQuery, Bootstrap, DataTables, SweetAlert2) -->
    <?php echo $scripts_links; ?>

    <!-- Módulo ES6 específico de Matrícula -->
    <script type="module" src="src/assets/js/matricula/matriculaDataTable.js"></script>
</body>

</html>
