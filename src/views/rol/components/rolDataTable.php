<div class="panel custom-table-card">
    <!-- Skeleton loader mientras carga la información -->
    <div id="rolSkeleton" class="skeleton-table">
        <div class="skeleton-header">
            <div class="skeleton-header-cell" style="flex: 0.5;"></div>
            <div class="skeleton-header-cell" style="flex: 2;"></div>
            <div class="skeleton-header-cell" style="flex: 3;"></div>
            <div class="skeleton-header-cell" style="flex: 1.2;"></div>
            <div class="skeleton-header-cell" style="flex: 1;"></div>
            <div class="skeleton-header-cell" style="flex: 1.2;"></div>
        </div>
        <?php for ($i = 0; $i < 4; $i++): ?>
            <div class="skeleton-row">
                <div class="skeleton-cell" style="flex: 0.5;"></div>
                <div class="skeleton-cell" style="flex: 2;"></div>
                <div class="skeleton-cell" style="flex: 3;"></div>
                <div class="skeleton-cell" style="flex: 1.2;"></div>
                <div class="skeleton-cell" style="flex: 1;"></div>
                <div class="skeleton-cell" style="flex: 1.2;"></div>
            </div>
        <?php endfor; ?>
    </div>

    <!-- Contenedor de DataTable -->
    <div class="table-responsive" id="rolTableContainer" style="display: none;">
        <table id="rolTable" class="table custom-modern-table w-100">
            <thead>
                <tr>
                    <th style="width: 8%;">ID</th>
                    <th style="width: 24%;">Nombre del Rol</th>
                    <th style="width: 36%;">Descripción</th>
                    <th style="width: 12%;">Usuarios</th>
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
