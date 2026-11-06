<div class="custom-table-card">
    <!-- Skeleton loader mientras carga la información -->
    <div id="cursoSkeleton" class="skeleton-table">
        <div class="skeleton-header">
            <div class="skeleton-header-cell" style="flex: 0.4;"></div>
            <div class="skeleton-header-cell" style="flex: 2.5;"></div>
            <div class="skeleton-header-cell" style="flex: 1.2;"></div>
            <div class="skeleton-header-cell" style="flex: 1;"></div>
            <div class="skeleton-header-cell" style="flex: 1;"></div>
            <div class="skeleton-header-cell" style="flex: 1.2;"></div>
            <div class="skeleton-header-cell" style="flex: 1;"></div>
        </div>
        <?php for ($i = 0; $i < 5; $i++): ?>
            <div class="skeleton-row">
                <div class="skeleton-cell" style="flex: 0.4;"></div>
                <div class="skeleton-cell" style="flex: 2.5;"></div>
                <div class="skeleton-cell" style="flex: 1.2;"></div>
                <div class="skeleton-cell" style="flex: 1;"></div>
                <div class="skeleton-cell" style="flex: 1;"></div>
                <div class="skeleton-cell" style="flex: 1.2;"></div>
                <div class="skeleton-cell" style="flex: 1;"></div>
            </div>
        <?php endfor; ?>
    </div>

    <!-- Contenedor de DataTable -->
    <div class="table-responsive" id="cursoTableContainer" style="display: none;">
        <table id="cursoTable" class="table custom-modern-table w-100">
            <thead>
                <tr>
                    <th style="width: 6%;">ID</th>
                    <th style="width: 31%;">Nombre del Curso</th>
                    <th style="width: 15%;">Tipo</th>
                    <th style="width: 12%;">Precio</th>
                    <th style="width: 12%;">Estado</th>
                    <th style="width: 12%;">Fecha de Inicio</th>
                    <th style="width: 12%; text-align: right;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <!-- Contenido cargado dinámicamente mediante DataTables y AJAX -->
            </tbody>
        </table>

        <!-- Footer y Paginación 100% personalizada (Reemplaza DataTables Bootstrap) -->
        <div class="custom-table-footer d-flex flex-wrap align-items-center justify-content-between gap-3 mt-4 pt-3">
            <div class="d-flex align-items-center gap-3">
                <div class="custom-info-text" id="customTableInfo">
                    Mostrando 0 a 0 de 0 registros
                </div>

                <!-- Selector personalizado de cantidad de filas -->
                <div class="d-flex align-items-center gap-2">
                    <span class="custom-per-page-label">Mostrar:</span>
                    <div class="custom-dropdown dropdown-per-page" id="dropdownPerPage">
                        <button type="button" class="custom-dropdown-toggle toggle-sm" aria-expanded="false">
                            <span class="dropdown-selected-text"></span>
                            <i class="fas fa-chevron-down dropdown-arrow"></i>
                        </button>
                        <div class="custom-dropdown-menu menu-sm">
                            <div class="custom-dropdown-item active" data-value="5">5</div>
                            <div class="custom-dropdown-item" data-value="10">10</div>
                            <div class="custom-dropdown-item" data-value="15">15</div>
                            <div class="custom-dropdown-item" data-value="20">20</div>
                            <div class="custom-dropdown-item" data-value="25">25</div>
                            <div class="custom-dropdown-item" data-value="30">30</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botones y números de paginación custom -->
            <div class="custom-pagination-btns d-flex align-items-center gap-2" id="customPagination">
                <!-- Se inyecta dinámicamente desde JS -->
            </div>
        </div>
    </div>
</div>
