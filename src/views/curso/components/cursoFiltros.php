<div class="custom-filter-card mb-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
        <!-- Input de búsqueda por texto a la izquierda -->
        <div class="filter-search-container flex-grow-1" style="max-width: 420px;">
            <div class="custom-search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="filterSearchInput" class="custom-filter-input" placeholder="Buscar por nombre del curso..." autocomplete="off">
            </div>
        </div>

        <!-- Filtros al lado contrario (derecha) -->
        <div class="d-flex align-items-center gap-3 ms-auto flex-wrap">
            <!-- Custom Dropdown: Tipo de Curso -->
            <div class="custom-dropdown" id="dropdownTipoCurso">
                <button type="button" class="custom-dropdown-toggle" aria-expanded="false">
                    <span class="dropdown-selected-text">Todos los tipos</span>
                    <i class="fas fa-chevron-down dropdown-arrow"></i>
                </button>
                <div class="custom-dropdown-menu">
                    <div class="custom-dropdown-item active" data-value="">Todos los tipos</div>
                    <div class="custom-dropdown-item" data-value="Curso completo">Curso completo</div>
                    <div class="custom-dropdown-item" data-value="Masterclass">Masterclass</div>
                </div>
                <input type="hidden" id="filterTipoCurso" value="">
            </div>

            <!-- Custom Dropdown: Estado -->
            <div class="custom-dropdown" id="dropdownEstadoCurso">
                <button type="button" class="custom-dropdown-toggle" aria-expanded="false">
                    <span class="dropdown-selected-text">Todos los estados</span>
                    <i class="fas fa-chevron-down dropdown-arrow"></i>
                </button>
                <div class="custom-dropdown-menu">
                    <div class="custom-dropdown-item active" data-value="">Todos los estados</div>
                    <div class="custom-dropdown-item" data-value="Activo">Activo (Venta / Preventa)</div>
                    <div class="custom-dropdown-item" data-value="Venta Normal">Venta Normal</div>
                    <div class="custom-dropdown-item" data-value="Preventa">Preventa</div>
                    <div class="custom-dropdown-item" data-value="En pausa">En pausa / Borrador</div>
                    <div class="custom-dropdown-item" data-value="Cerrado">Cerrado</div>
                </div>
                <input type="hidden" id="filterEstadoCurso" value="">
            </div>
        </div>
    </div>
</div>
