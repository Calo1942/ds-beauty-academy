<div class="panel">
    <!-- Skeleton loader mientras carga la información -->
    <div id="estudianteSkeleton" class="skeleton-table">
        <div class="skeleton-header">
            <div class="skeleton-header-cell" style="flex: 0.5;"></div>
            <div class="skeleton-header-cell" style="flex: 2;"></div>
            <div class="skeleton-header-cell"></div>
            <div class="skeleton-header-cell" style="flex: 1.5;"></div>
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
            </div>
        <?php endfor; ?>
    </div>

    <!-- Contenedor de DataTable -->
    <div class="table-responsive" id="estudianteTableContainer" style="display: none;">
        <table id="estudianteTable" class="table table-dark table-hover w-100">
            <thead>
                <tr>
                    <th style="width: 8%;">ID</th>
                    <th style="width: 25%;">Nombre Completo</th>
                    <th style="width: 15%;">Cédula</th>
                    <th style="width: 25%;">Correo</th>
                    <th style="width: 15%;">Instagram</th>
                    <th style="width: 12%; text-align: right;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <!-- Contenido cargado dinámicamente mediante DataTables y AJAX -->
            </tbody>
        </table>
    </div>
</div>