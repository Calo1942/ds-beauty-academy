// dataTable.js - Factoría y utilidades para DataTables (DS Beauty Academy)

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
    $footer.find('.dt-custom-info').text(`Mostrando ${start} a ${end} de ${total} registros`);

    // B. Actualizar botones de paginación
    const totalPages = info.pages;
    const currentPage = info.page;
    const $pagination = $footer.find('.dt-custom-pagination');
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
    const $card = $table.closest('.custom-table-card, .panel');
    if (!$card.length) return;

    // Si ya existe una barra de filtros previa (por ejemplo cursoFiltros.php), no duplicar
    if ($card.prev('.custom-filter-card').length || $('#filterSearchInput').length) {
        return;
    }

    // Detectar si la tabla tiene una columna de Estado / Estatus
    let statusColIdx = -1;
    $table.find('thead th').each(function (index) {
        const text = $(this).text().trim().toLowerCase();
        if (text === 'estado' || text === 'estatus') {
            statusColIdx = index;
        }
    });

    const statusDropdownHtml = statusColIdx !== -1 ? `
        <div class="custom-dropdown auto-filter-status" id="autoDropdownStatus_${$table.attr('id')}">
            <button type="button" class="custom-dropdown-toggle" aria-expanded="false">
                <span class="dropdown-selected-text">Todos los estados</span>
                <i class="fas fa-chevron-down dropdown-arrow"></i>
            </button>
            <div class="custom-dropdown-menu">
                <div class="custom-dropdown-item active" data-value="">Todos los estados</div>
                <div class="custom-dropdown-item" data-value="Activo">Activo</div>
                <div class="custom-dropdown-item" data-value="Inactivo">Inactivo</div>
                <div class="custom-dropdown-item" data-value="Pendiente">Pendiente</div>
                <div class="custom-dropdown-item" data-value="Completado">Completado</div>
            </div>
        </div>
    ` : '';

    const filterBarHtml = `
        <div class="custom-filter-card mb-4 auto-generated-filter">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div class="filter-search-container flex-grow-1" style="max-width: 420px;">
                    <div class="custom-search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" class="custom-filter-input auto-filter-search" placeholder="Buscar registro..." autocomplete="off">
                    </div>
                </div>
                <div class="d-flex align-items-center gap-3 ms-auto flex-wrap">
                    ${statusDropdownHtml}
                </div>
            </div>
        </div>
    `;

    const $filterBar = $(filterBarHtml);
    $card.before($filterBar);

    // Evento de búsqueda en vivo
    $filterBar.find('.auto-filter-search').on('keyup input', function () {
        dtApi.search(this.value).draw();
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
    language: {
        emptyTable: "No hay registros disponibles",
        zeroRecords: "No se encontraron coincidencias"
    },
    dom: 'rt' // Oculta controles nativos para usar el diseño moderno custom
};

export const createActionsColumn = (options = {}) => ({
    data: null,
    render: (data, type, row) => {
        // Soporta claves primarias comunes del sistema
        const id = row.id_estudiante || 
                   row.id_instructor || 
                   row.id_curso || 
                   row.id_banco || 
                   row.id_especialidad || 
                   row.id_clase || 
                   row.id_diploma || 
                   row.id_recurso || 
                   row.id_evaluacion ||
                   row.id_entrega ||
                   row.id_matricula ||
                   row.id_comprobante_pago ||
                   row.id || 
                   '';

        const buttons = [];

        if (options.btnVer !== false) {
            buttons.push(`
                <button value="${id}" class="btn-circle-action btn-action btn-view btn-table-ver" title="Ver Detalle" type="button">
                    <i class="fas fa-eye"></i>
                </button>
            `);
        }

        if (options.btnEditar !== false) {
            buttons.push(`
                <button value="${id}" class="btn-circle-action btn-action btn-edit btn-table-editar" title="Editar" type="button">
                    <i class="fas fa-pencil-alt"></i>
                </button>
            `);
        }

        if (options.btnEliminar !== false) {
            buttons.push(`
                <button value="${id}" class="btn-circle-action btn-action btn-delete btn-table-eliminar" title="Eliminar" type="button">
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

export const reloadDataTable = (dataTable) => {
    if (dataTable && typeof dataTable.ajax !== 'undefined') {
        dataTable.ajax.reload(null, false);
    }
};
