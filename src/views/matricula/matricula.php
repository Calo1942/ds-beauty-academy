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
        <div class="custom-page-header d-flex flex-wrap align-items-center justify-content-between mb-4">
    <div class="d-flex align-items-center gap-3">
        <div class="page-header-icon-badge">
            <i class="fas fa-id-card"></i>
        </div>
        <div>
            <h1 class="page-header-title">Gestión de Matrículas</h1>
            <p class="page-header-subtitle">Inscripciones activas y asignación de cupos</p>
        </div>
    </div>
        <div class="page-header-actions mt-3 mt-md-0 d-flex align-items-center">
            
            <button class="btn-custom-primary btn-registrar-matricula" type="button" onclick="abrirModal('modalCrear')">
                <i class="fas fa-plus"></i> Nueva Matrícula
            </button>
        </div>
</div>
        <div class="panel custom-table-card">
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
                <table id="matriculaTable" class="table custom-modern-table w-100">
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

    <!-- Modales del Módulo -->
    <?php include __DIR__ . '/components/modales/matriculaModalCrear.php'; ?>

    <!-- Scripts y librerías globales (jQuery, Bootstrap, DataTables, SweetAlert2) -->
    <?php echo $scripts_links; ?>

    <!-- Módulo ES6 específico de Matrícula -->
    <script type="module" src="src/assets/js/matricula/matriculaDataTable.js"></script>
</body>

</html>
