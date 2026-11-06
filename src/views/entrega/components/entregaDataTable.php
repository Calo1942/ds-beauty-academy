<div class="panel custom-table-card">
    <!-- Skeleton loader mientras carga la información -->
    <div id="entregaSkeleton" class="skeleton-table">
        <div class="skeleton-header">
            <div class="skeleton-header-cell" style="flex: 0.5;"></div>
            <div class="skeleton-header-cell" style="flex: 1.8;"></div>
            <div class="skeleton-header-cell" style="flex: 1.5;"></div>
            <div class="skeleton-header-cell" style="flex: 1.5;"></div>
            <div class="skeleton-header-cell"></div>
            <div class="skeleton-header-cell" style="flex: 0.8;"></div>
            <div class="skeleton-header-cell"></div>
            <div class="skeleton-header-cell"></div>
            <div class="skeleton-header-cell" style="flex: 1.2;"></div>
        </div>
        <?php for ($i = 0; $i < 5; $i++): ?>
            <div class="skeleton-row">
                <div class="skeleton-cell" style="flex: 0.5;"></div>
                <div class="skeleton-cell" style="flex: 1.8;"></div>
                <div class="skeleton-cell" style="flex: 1.5;"></div>
                <div class="skeleton-cell" style="flex: 1.5;"></div>
                <div class="skeleton-cell"></div>
                <div class="skeleton-cell" style="flex: 0.8;"></div>
                <div class="skeleton-cell"></div>
                <div class="skeleton-cell"></div>
                <div class="skeleton-cell" style="flex: 1.2;"></div>
            </div>
        <?php endfor; ?>
    </div>

    <!-- Contenedor de DataTable -->
    <div class="table-responsive" id="entregaTableContainer" style="display: none;">
        <table id="entregaTable" class="table custom-modern-table w-100">
            <thead>
                <tr>
                    <th style="width: 5%;">ID</th>
                    <th style="width: 17%;">Estudiante</th>
                    <th style="width: 16%;">Curso</th>
                    <th style="width: 16%;">Evaluación</th>
                    <th style="width: 11%;">Fecha</th>
                    <th style="width: 7%;">Adjunto</th>
                    <th style="width: 8%;">Nota</th>
                    <th style="width: 9%;">Estado</th>
                    <th style="width: 11%; text-align: right;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <!-- Contenido cargado dinámicamente mediante DataTables y AJAX -->
            </tbody>
        </table>
    </div>
</div>
