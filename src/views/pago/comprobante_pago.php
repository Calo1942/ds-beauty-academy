<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprobantes de Pago | DS Beauty Academy</title>
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
            <i class="fas fa-receipt"></i>
        </div>
        <div>
            <h1 class="page-header-title">Verificación de Pagos</h1>
            <p class="page-header-subtitle">Auditoría, comprobantes y conciliación bancaria</p>
        </div>
    </div>
        <div class="page-header-actions mt-3 mt-md-0 d-flex align-items-center">
            
            <button class="btn-custom-primary btn-registrar-pago" type="button" onclick="abrirModal("modalCrear")">
                <i class="fas fa-plus"></i> Registrar Pago Manual
            </button>
        </div>
</div>
        <div class="panel custom-table-card">
            <!-- Skeleton loader mientras carga la información -->
            <div id="pagoSkeleton" class="skeleton-table">
                <div class="skeleton-header">
                    <div class="skeleton-header-cell" style="flex: 0.5;"></div>
                    <div class="skeleton-header-cell" style="flex: 1.5;"></div>
                    <div class="skeleton-header-cell" style="flex: 2;"></div>
                    <div class="skeleton-header-cell" style="flex: 1.5;"></div>
                    <div class="skeleton-header-cell"></div>
                    <div class="skeleton-header-cell"></div>
                    <div class="skeleton-header-cell"></div>
                    <div class="skeleton-header-cell" style="flex: 0.8;"></div>
                    <div class="skeleton-header-cell"></div>
                    <div class="skeleton-header-cell"></div>
                </div>
                <?php for ($i = 0; $i < 5; $i++): ?>
                    <div class="skeleton-row">
                        <div class="skeleton-cell" style="flex: 0.5;"></div>
                        <div class="skeleton-cell" style="flex: 1.5;"></div>
                        <div class="skeleton-cell" style="flex: 2;"></div>
                        <div class="skeleton-cell" style="flex: 1.5;"></div>
                        <div class="skeleton-cell"></div>
                        <div class="skeleton-cell"></div>
                        <div class="skeleton-cell"></div>
                        <div class="skeleton-cell" style="flex: 0.8;"></div>
                        <div class="skeleton-cell"></div>
                        <div class="skeleton-cell"></div>
                    </div>
                <?php endfor; ?>
            </div>

            <!-- Contenedor de DataTable -->
            <div class="table-responsive" id="pagoTableContainer" style="display: none;">
                <table id="pagoTable" class="table custom-modern-table w-100">
                    <thead>
                        <tr>
                            <th style="width: 5%;">ID</th>
                            <th style="width: 13%;">Referencia</th>
                            <th style="width: 17%;">Estudiante</th>
                            <th style="width: 16%;">Curso</th>
                            <th style="width: 12%;">Banco</th>
                            <th style="width: 9%;">Monto</th>
                            <th style="width: 10%;">Fecha</th>
                            <th style="width: 8%;">Adjunto</th>
                            <th style="width: 10%;">Estado</th>
                            <th style="width: 10%; text-align: right;">Acciones</th>
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
    <?php include __DIR__ . '/components/modales/pagoModalCrear.php'; ?>
    <?php include __DIR__ . '/components/modales/pagoModalDetalle.php'; ?>

    <!-- Scripts y librerías globales (jQuery, Bootstrap, DataTables, SweetAlert2) -->
    <?php echo $scripts_links; ?>

    <!-- Módulo ES6 específico de Comprobantes de Pago -->
    <script type="module" src="src/assets/js/pago/pagoDataTable.js"></script>
</body>

</html>