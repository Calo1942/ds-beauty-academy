<div class="panel custom-table-card">
    <!-- Skeleton loader mientras carga la información -->
    <div id="usuarioSkeleton" class="skeleton-table">
        <div class="skeleton-header">
            <div class="skeleton-header-cell" style="flex: 0.5;"></div>
            <div class="skeleton-header-cell" style="flex: 2;"></div>
            <div class="skeleton-header-cell" style="flex: 2;"></div>
            <div class="skeleton-header-cell" style="flex: 1.5;"></div>
            <div class="skeleton-header-cell" style="flex: 1;"></div>
            <div class="skeleton-header-cell" style="flex: 1.2;"></div>
            <div class="skeleton-header-cell" style="flex: 1;"></div>
        </div>
        <?php for ($i = 0; $i < 5; $i++): ?>
            <div class="skeleton-row">
                <div class="skeleton-cell" style="flex: 0.5;"></div>
                <div class="skeleton-cell" style="flex: 2;"></div>
                <div class="skeleton-cell" style="flex: 2;"></div>
                <div class="skeleton-cell" style="flex: 1.5;"></div>
                <div class="skeleton-cell" style="flex: 1;"></div>
                <div class="skeleton-cell" style="flex: 1.2;"></div>
                <div class="skeleton-cell" style="flex: 1;"></div>
            </div>
        <?php endfor; ?>
    </div>

    <!-- Contenedor de DataTable -->
    <div class="table-responsive" id="usuarioTableContainer" style="display: none;">
        <table id="usuarioTable" class="table custom-modern-table w-100">
            <thead>
                <tr>
                    <th style="width: 7%;">ID</th>
                    <th style="width: 25%;">Persona Vinculada</th>
                    <th style="width: 25%;">Correo Electrónico</th>
                    <th style="width: 15%;">Rol</th>
                    <th style="width: 12%;">Estado</th>
                    <th style="width: 16%; text-align: right;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <!-- Contenido cargado dinámicamente mediante DataTables y AJAX -->
            </tbody>
        </table>
    </div>
</div>

