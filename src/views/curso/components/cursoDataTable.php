<div class="panel">
    <!-- Skeleton loader mientras carga la información -->
    <div id="cursoSkeleton" class="skeleton-table">
        <div class="skeleton-header">
            <div class="skeleton-header-cell" style="flex: 0.5;"></div>
            <div class="skeleton-header-cell" style="flex: 2;"></div>
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
                <div class="skeleton-cell"></div>
                <div class="skeleton-cell"></div>
                <div class="skeleton-cell"></div>
            </div>
        <?php endfor; ?>
    </div>

    <!-- Contenedor de DataTable -->
    <div class="table-responsive" id="cursoTableContainer" style="display: none;">
        <table id="cursoTable" class="table table-dark table-hover w-100">
            <thead>
                <tr>
                    <th style="width: 8%;">ID</th>
                    <th style="width: 30%;">Nombre del Curso</th>
                    <th style="width: 15%;">Tipo</th>
                    <th style="width: 15%;">Precio</th>
                    <th style="width: 15%;">Estatus</th>
                    <th style="width: 17%; text-align: right;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <!-- Contenido cargado dinámicamente mediante DataTables y AJAX -->
            </tbody>
        </table>
    </div>
</div>