// dataTable.js - Factoría y utilidades para DataTables (DS Beauty Academy)

/**
 * Helpers universales para gestión de modales (DS Beauty Academy)
 * Limpia automáticamente formularios, clases de validación y restaura botones.
 */
export function abrirModal(id) {
    const modal = typeof id === 'string' ? document.getElementById(id) : id;
    if (modal) {
        modal.classList.add('show');
        document.body.classList.add('modal-open');
    }
}

export function cerrarModal(id) {
    const modal = typeof id === 'string' ? document.getElementById(id) : id;
    if (modal) {
        modal.classList.remove('show');
        if (!document.querySelector('.modal.show, .custom-modal.show')) {
            document.body.classList.remove('modal-open');
        }
        // Limpieza automática del formulario dentro del modal
        const form = modal.querySelector('form');
        if (form) {
            form.reset();
            const $form = $(form);
            $form.find('.is-valid, .is-invalid').removeClass('is-valid is-invalid');
            $form.find('.invalid-feedback').text('').hide();
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            }
        }
    }
}

// Registro global y listeners para backdrop, tecla Escape y botones de cierre
if (typeof window !== 'undefined') {
    window.abrirModal = abrirModal;
    window.cerrarModal = cerrarModal;

    $(document).on('click', '[data-modal-close], .btn-close-modal, .modal-close, [data-bs-dismiss="modal"]', function (e) {
        e.preventDefault();
        const $modal = $(this).closest('.modal, .custom-modal, [id^="modal"]');
        if ($modal.length) {
            cerrarModal($modal[0]);
        }
    });

    $(document).on('click', '.modal, .custom-modal', function (e) {
        if (e.target === this) {
            cerrarModal(this);
        }
    });

    $(document).on('keydown', function (e) {
        if (e.key === 'Escape') {
            const activeModal = document.querySelector('.modal.show, .custom-modal.show');
            if (activeModal) cerrarModal(activeModal);
        }
    });
}

/**
 * Renderiza la paginación y la información personalizada (Puro CSS y JS, sin azul nativo)
 */
export function renderCustomPagination(dtApi, containerSelector) {
    if (!dtApi || typeof dtApi.page !== 'function') return;

    let info;
    try {
        info = dtApi.page.info();
    } catch (e) {
        return;
    }
    if (!info) return;

    const $container = $(containerSelector);
    if (!$container.length) return;

    // Buscar o inyectar el footer custom si no existe aún
    let $footer = $container.find('.custom-table-footer');
    if (!$footer.length) {
        $footer = $(`
            <div class="custom-table-footer d-flex flex-wrap align-items-center justify-content-between gap-3 mt-4 pt-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="custom-info-text dt-custom-info"></div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="custom-per-page-label">Mostrar:</span>
                        <div class="custom-dropdown dropdown-per-page">
                            <button type="button" class="custom-dropdown-toggle toggle-sm" aria-expanded="false">
                                <span class="dropdown-selected-text">${info.length || 5}</span>
                                <i class="fas fa-chevron-down dropdown-arrow"></i>
                            </button>
                            <div class="custom-dropdown-menu menu-sm">
                                <div class="custom-dropdown-item ${info.length === 5 ? 'active' : ''}" data-value="5">5</div>
                                <div class="custom-dropdown-item ${info.length === 10 ? 'active' : ''}" data-value="10">10</div>
                                <div class="custom-dropdown-item ${info.length === 15 ? 'active' : ''}" data-value="15">15</div>
                                <div class="custom-dropdown-item ${info.length === 20 ? 'active' : ''}" data-value="20">20</div>
                                <div class="custom-dropdown-item ${info.length === 25 ? 'active' : ''}" data-value="25">25</div>
                                <div class="custom-dropdown-item ${info.length === 30 ? 'active' : ''}" data-value="30">30</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="custom-pagination-btns d-flex align-items-center gap-2 dt-custom-pagination"></div>
            </div>
        `);
        $container.append($footer);

        // Bind evento dropdown de filas por página
        $footer.on('click', '.custom-dropdown-toggle', function (e) {
            e.stopPropagation();
            const $dd = $(this).closest('.custom-dropdown');
            $('.custom-dropdown').not($dd).removeClass('active');
            $dd.toggleClass('active');
        });

        $footer.on('click', '.custom-dropdown-item', function (e) {
            e.stopPropagation();
            const $item = $(this);
            const $dd = $item.closest('.custom-dropdown');
            const val = parseInt($item.data('value'), 10);
            $dd.find('.custom-dropdown-item').removeClass('active');
            $item.addClass('active');
            $dd.find('.dropdown-selected-text').text(val);
            $dd.removeClass('active');
            dtApi.page.len(val).draw();
        });
    }

    // A. Actualizar texto de información "Mostrando X a Y de Z registros"
    const start = info.recordsDisplay === 0 ? 0 : info.start + 1;
    const end = info.end;
    const total = info.recordsDisplay;
    $footer.find('.dt-custom-info, #customTableInfo').text(`Mostrando ${start} a ${end} de ${total} registros`);

    // B. Actualizar botones de paginación
    const totalPages = info.pages;
    const currentPage = info.page;
    const $pagination = $footer.find('.dt-custom-pagination, #customPagination');
    $pagination.empty();

    if (totalPages <= 1) {
        $pagination.append(`<button type="button" class="custom-page-btn" disabled>Anterior</button>`);
        $pagination.append(`<button type="button" class="custom-page-btn active">1</button>`);
        $pagination.append(`<button type="button" class="custom-page-btn" disabled>Siguiente ></button>`);
        return;
    }

    // Botón Anterior
    const $btnPrev = $(`<button type="button" class="custom-page-btn">Anterior</button>`);
    if (currentPage === 0) {
        $btnPrev.prop('disabled', true);
    } else {
        $btnPrev.on('click', () => dtApi.page('previous').draw('page'));
    }
    $pagination.append($btnPrev);

    // Números de páginas
    for (let i = 0; i < totalPages; i++) {
        const pageNum = i + 1;
        const $btnPage = $(`<button type="button" class="custom-page-btn ${i === currentPage ? 'active' : ''}">${pageNum}</button>`);
        if (i !== currentPage) {
            $btnPage.on('click', function () {
                dtApi.page(i).draw('page');
            });
        }
        $pagination.append($btnPage);
    }

    // Botón Siguiente
    const $btnNext = $(`<button type="button" class="custom-page-btn">Siguiente ></button>`);
    if (currentPage >= totalPages - 1) {
        $btnNext.prop('disabled', true);
    } else {
        $btnNext.on('click', () => dtApi.page('next').draw('page'));
    }
    $pagination.append($btnNext);
}

/**
 * Renderiza e inyecta la barra de búsqueda y filtro de estado (Puro CSS y JS)
 */
export function renderCustomFilterBar(dtApi, tableSelector) {
    if (!dtApi || !$(tableSelector).length) return;

    const $table = $(tableSelector);
    const tableId = $table.attr('id') || 'table';
    const $card = $table.closest('.custom-table-card, .panel');
    if (!$card.length) return;

    // Si ya existe una barra de filtros previa de este módulo o específica, no duplicar
    if ($card.prev(`.custom-filter-card[data-for="${tableId}"]`).length || $table.data('filter-initialized')) {
        return;
    }

    // Si la vista ya tiene su propio componente de filtros (ej: Cursos)
    if ($('#filterSearchInput').length && tableId === 'cursoTable') {
        return;
    }

    // Detectar si la tabla tiene una columna de Estado / Estatus
    let statusColIdx = -1;
    $table.find('thead th').each(function (index) {
        const text = $(this).text().trim().toLowerCase();
        if (text === 'estado' || text === 'estatus' || text.includes('estado')) {
            statusColIdx = index;
        }
    });

    // Barra de filtros por estado si aplica
    const statusDropdownHtml = statusColIdx !== -1 ? `
        <div class="custom-dropdown auto-filter-status" id="autoDropdownStatus_${tableId}">
            <button type="button" class="custom-dropdown-toggle" aria-expanded="false">
                <span class="dropdown-selected-text">Todos los estados</span>
                <i class="fas fa-chevron-down dropdown-arrow"></i>
            </button>
            <div class="custom-dropdown-menu">
                <div class="custom-dropdown-item active" data-value="">Todos los estados</div>
                <div class="custom-dropdown-item" data-value="Activo">Activo</div>
                <div class="custom-dropdown-item" data-value="Inactivo">Inactivo</div>
                <div class="custom-dropdown-item" data-value="Pendiente">Pendiente</div>
                <div class="custom-dropdown-item" data-value="Aprobada">Aprobada</div>
                <div class="custom-dropdown-item" data-value="Repetir">Repetir</div>
                <div class="custom-dropdown-item" data-value="Verificado">Verificado</div>
                <div class="custom-dropdown-item" data-value="Rechazado">Rechazado</div>
            </div>
        </div>
    ` : '';

    const clearButtonHtml = `
        <button type="button" class="btn-clear-filters custom-btn-clear" title="Restablecer filtros">
            <i class="fas fa-undo-alt"></i> <span>Limpiar filtros</span>
        </button>
    `;

    const filterBarHtml = `
        <div class="custom-filter-card mb-4 auto-generated-filter" data-for="${tableId}">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div class="filter-search-container flex-grow-1" style="max-width: 420px;">
                    <div class="custom-search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" class="custom-filter-input auto-filter-search" placeholder="Buscar registro..." autocomplete="off">
                    </div>
                </div>
                <div class="d-flex align-items-center gap-3 ms-auto flex-wrap">
                    ${statusDropdownHtml}
                    ${clearButtonHtml}
                </div>
            </div>
        </div>
    `;

    const $filterBar = $(filterBarHtml);
    $card.before($filterBar);
    $table.data('filter-initialized', true);

    // Evento de búsqueda en vivo en la tabla
    $filterBar.find('.auto-filter-search').on('keyup input', function () {
        dtApi.search(this.value).draw();
    });

    // Evento para limpiar y restablecer filtros
    $filterBar.find('.btn-clear-filters').on('click', function () {
        $filterBar.find('.auto-filter-search').val('');
        if (statusColIdx !== -1) {
            const $dd = $filterBar.find('.auto-filter-status');
            $dd.find('.custom-dropdown-item').removeClass('active');
            $dd.find('.custom-dropdown-item[data-value=""]').addClass('active');
            $dd.find('.dropdown-selected-text').text('Todos los estados');
            $dd.removeClass('active');
        }
        dtApi.search('').columns().search('').draw();
    });

    // Evento de dropdown de estado si aplica
    if (statusColIdx !== -1) {
        $filterBar.find('.auto-filter-status').on('click', '.custom-dropdown-toggle', function (e) {
            e.stopPropagation();
            const $dd = $(this).closest('.custom-dropdown');
            $('.custom-dropdown').not($dd).removeClass('active');
            $dd.toggleClass('active');
        });

        $filterBar.find('.auto-filter-status').on('click', '.custom-dropdown-item', function (e) {
            e.stopPropagation();
            const $item = $(this);
            const $dd = $item.closest('.custom-dropdown');
            const val = $item.data('value');
            const text = $item.text();

            $dd.find('.custom-dropdown-item').removeClass('active');
            $item.addClass('active');
            $dd.find('.dropdown-selected-text').text(text);
            $dd.removeClass('active');

            if (val === '') {
                dtApi.column(statusColIdx).search('').draw();
            } else {
                dtApi.column(statusColIdx).search(val).draw();
            }
        });
    }
}

// Cerrar dropdowns personalizados al hacer clic fuera
$(document).on('click', function () {
    $('.custom-dropdown').removeClass('active');
});

const defaultConfig = {
    autoWidth: false,
    responsive: true,
    pageLength: 5,
    order: [[0, 'desc']], // Ordenar por ID descendente por defecto (más recientes primero)
    processing: true,     // Activa loader y estado de procesamiento
    language: {
        emptyTable: "No hay registros disponibles",
        zeroRecords: "No se encontraron coincidencias",
        processing: `
            <div class="table-luxury-loader">
                <div class="luxury-spinner">
                    <div class="spinner-ring"></div>
                    <div class="spinner-ring ring-glow"></div>
                    <i class="fas fa-gem spinner-icon"></i>
                </div>
                <span class="loader-text">Actualizando datos...</span>
            </div>
        `
    },
    dom: 'rt' // Oculta controles nativos para usar el diseño moderno custom
};

export const createActionsColumn = (options = {}) => ({
    data: null,
    render: (data, type, row) => {
        // Priorizar idField configurado explícitamente o claves primarias comunes
        const idField = options.idField;
        const id = (idField && row && row[idField] !== undefined && row[idField] !== null)
            ? row[idField]
            : (row.id_diploma || 
               row.id_usuario || 
               row.id_estudiante || 
               row.id_curso || 
               row.id_instructor || 
               row.id_banco || 
               row.id_especialidad || 
               row.id_clase || 
               row.id_recurso || 
               row.id_evaluacion ||
               row.id_entrega_evaluacion ||
               row.id_entrega ||
               row.id_matricula ||
               row.id_comprobante_pago ||
               row.id || 
               '');

        const buttons = [];

        if (options.btnVer !== false) {
            buttons.push(`
                <button value="${id}" data-id="${id}" class="btn-action btn-view btn-table-ver" title="Ver Detalle" type="button">
                    <i class="fas fa-eye"></i>
                </button>
            `);
        }

        if (options.btnEditar !== false) {
            buttons.push(`
                <button value="${id}" data-id="${id}" class="btn-action btn-edit btn-table-editar" title="Editar" type="button">
                    <i class="fas fa-pencil-alt"></i>
                </button>
            `);
        }

        if (options.btnEliminar !== false) {
            buttons.push(`
                <button value="${id}" data-id="${id}" class="btn-action btn-delete btn-table-eliminar" title="Eliminar" type="button">
                    <i class="fas fa-trash-alt"></i>
                </button>
            `);
        }

        return `<div class="modern-action-btns action-btns d-flex gap-2 justify-content-end">${buttons.join('')}</div>`;
    },
    orderable: false,
    searchable: false,
    className: 'text-end align-middle'
});

export const createDataTable = (tableId, url, columns, customConfig = {}) => {
    // Asegurar que la tabla tenga la clase custom-modern-table
    $(tableId).addClass('custom-modern-table');
    const containerSelector = $(tableId).closest('.table-responsive, .custom-table-card, .panel');

    const config = {
        ...defaultConfig,
        ajax: {
            url,
            method: 'POST',
            data: { buscarTodos: 'true' },
            dataSrc: 'data'
        },
        columns,
        ...customConfig,
        initComplete: function (settings, json) {
            const api = this.api();
            renderCustomFilterBar(api, tableId);
            renderCustomPagination(api, containerSelector);
            if (typeof customConfig.initComplete === 'function') {
                customConfig.initComplete.call(this, settings, json);
            }
        },
        drawCallback: function (settings) {
            const api = this.api();
            renderCustomFilterBar(api, tableId);
            renderCustomPagination(api, containerSelector);
            if (typeof customConfig.drawCallback === 'function') {
                customConfig.drawCallback.call(this, settings);
            }
        }
    };

    return $(tableId).DataTable(config);
};

export const showTableLoader = (container, message = 'Actualizando registros...') => {
    const $container = $(container);
    let $overlay = $container.find('> .table-loading-overlay');
    if (!$overlay.length) {
        $overlay = $(`
            <div class="table-loading-overlay">
                <div class="table-luxury-loader">
                    <div class="luxury-spinner">
                        <div class="spinner-ring"></div>
                        <div class="spinner-ring ring-glow"></div>
                        <i class="fas fa-gem spinner-icon"></i>
                    </div>
                    <span class="loader-text">${message}</span>
                </div>
            </div>
        `);
        $container.append($overlay);
    } else {
        $overlay.find('.loader-text').text(message);
    }
    $overlay.stop(true, true).fadeIn(180);
};

export const hideTableLoader = (container) => {
    const $container = $(container);
    const $overlay = $container.find('> .table-loading-overlay');
    if ($overlay.length) {
        $overlay.stop(true, true).fadeOut(250);
    }
};

export const reloadDataTable = (dataTable, message = 'Actualizando registros...') => {
    if (dataTable && typeof dataTable.ajax !== 'undefined') {
        const $table = $(dataTable.table().node());
        const $container = $table.closest('.custom-table-card, .table-responsive, .panel');

        // Mostrar animación de carga en la tabla con diseño Luxury
        showTableLoader($container, message);

        const startTime = Date.now();
        dataTable.ajax.reload(() => {
            // Asegurar que la animación se aprecie de forma fluida (mínimo 550ms)
            const elapsed = Date.now() - startTime;
            const remaining = Math.max(0, 550 - elapsed);
            setTimeout(() => {
                hideTableLoader($container);
            }, remaining);
        }, false);
    }
};
