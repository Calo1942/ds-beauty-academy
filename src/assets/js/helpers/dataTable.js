// dataTable.js - Factoría y utilidades para DataTables
const defaultConfig = {
    autoWidth: false,
    responsive: true,
    pageLength: 10,
    language: {
        decimal: "",
        emptyTable: "No hay datos disponibles en la tabla",
        info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
        infoEmpty: "Mostrando 0 a 0 de 0 registros",
        infoFiltered: "(filtrado de _MAX_ registros totales)",
        infoPostFix: "",
        thousands: ",",
        lengthMenu: "Mostrar _MENU_ registros",
        loadingRecords: "Cargando...",
        processing: "Procesando...",
        search: "",
        searchPlaceholder: "Buscar...",
        zeroRecords: "No se encontraron coincidencias",
        paginate: {
            first: "Primero",
            last: "Último",
            next: "Siguiente",
            previous: "Anterior"
        },
        aria: {
            sortAscending: ": activar para ordenar la columna ascendente",
            sortDescending: ": activar para ordenar la columna descendente"
        }
    },
    dom: '<"dt-custom-header d-flex justify-content-between align-items-center mb-3"<"dt-length"l><"dt-search"f>>rt<"dt-custom-footer d-flex justify-content-between align-items-center mt-3"<"dt-info"i><"dt-pagination"p>>'
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
                   row.id || 
                   '';

        const buttons = [];

        if (options.btnVer !== false) {
            buttons.push(`<button value="${id}" class="btn-action btn-view btn-table-ver" title="Ver Detalle" type="button"><i class="fas fa-eye"></i></button>`);
        }

        if (options.btnEditar !== false) {
            buttons.push(`<button value="${id}" class="btn-action btn-edit btn-table-editar" title="Editar" type="button"><i class="fas fa-pen"></i></button>`);
        }

        if (options.btnEliminar !== false) {
            buttons.push(`<button value="${id}" class="btn-action btn-delete btn-table-eliminar" title="Eliminar" type="button"><i class="fas fa-trash"></i></button>`);
        }

        return `<div class="action-btns d-flex gap-1 justify-content-end">${buttons.join('')}</div>`;
    },
    orderable: false,
    searchable: false,
    className: 'text-end align-middle'
});

export const createDataTable = (tableId, url, columns, customConfig = {}) => {
    const config = {
        ...defaultConfig,
        ajax: {
            url,
            method: 'POST',
            data: { buscarTodos: 'true' },
            dataSrc: 'data'
        },
        columns,
        ...customConfig
    };

    return $(tableId).DataTable(config);
};

export const reloadDataTable = (dataTable) => {
    if (dataTable && typeof dataTable.ajax !== 'undefined') {
        dataTable.ajax.reload(null, false);
    }
};
