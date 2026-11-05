// cursoDataTable.js - Lógica de tabla DataTables, llamadas AJAX y modales para Cursos
import { reloadDataTable } from '../helpers/dataTable.js';
import { getById, create, update, remove, executeAjax } from '../helpers/ajax.js';
import { showConfirm, showLoading, closeLoading, showToast } from '../helpers/sweetalert.js';
import { animateIn } from '../helpers/animations.js';
import { showSkeleton, hideSkeleton } from '../helpers/skeleton.js';
import { limpiarValidaciones } from '../helpers/validation.js';

const API_URL = window.cursoApiUrl || '?url=curso';

// Helpers para abrir y cerrar modales del sistema
export function abrirModal(id) {
    const modal = document.getElementById(id);
    if (modal) modal.classList.add('show');
}

export function cerrarModal(id) {
    const modal = document.getElementById(id);
    if (modal) modal.classList.remove('show');
}

window.abrirModal = abrirModal;
window.cerrarModal = cerrarModal;

$(document).ready(function () {
    // 1. Mostrar skeleton mientras carga la tabla inicial
    showSkeleton('#cursoSkeleton', '#cursoTableContainer');

    let tblCurso = null;

    // Función para renderizar la paginación y la información personalizada de forma segura
    function actualizarPaginacionYInfo(dtInstance) {
        const api = dtInstance || tblCurso;
        if (!api || typeof api.page !== 'function') return;

        let info;
        try {
            info = api.page.info();
        } catch (e) {
            return;
        }
        if (!info) return;

        // A. Actualizar texto de información "Mostrando X a Y de Z registros"
        const start = info.recordsDisplay === 0 ? 0 : info.start + 1;
        const end = info.end;
        const total = info.recordsDisplay;
        $('#customTableInfo').text(`Mostrando ${start} a ${end} de ${total} registros`);

        // B. Renderizar botones de paginación
        const totalPages = info.pages;
        const currentPage = info.page; // Base 0
        const $pagination = $('#customPagination');
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
            $btnPrev.on('click', () => api.page('previous').draw('page'));
        }
        $pagination.append($btnPrev);

        // Números de páginas
        for (let i = 0; i < totalPages; i++) {
            const pageNum = i + 1;
            const $btnPage = $(`<button type="button" class="custom-page-btn ${i === currentPage ? 'active' : ''}">${pageNum}</button>`);
            if (i !== currentPage) {
                $btnPage.on('click', function () {
                    api.page(i).draw('page');
                });
            }
            $pagination.append($btnPage);
        }

        // Botón Siguiente
        const $btnNext = $(`<button type="button" class="custom-page-btn">Siguiente ></button>`);
        if (currentPage >= totalPages - 1) {
            $btnNext.prop('disabled', true);
        } else {
            $btnNext.on('click', () => api.page('next').draw('page'));
        }
        $pagination.append($btnNext);
    }

    // 2. Inicializar DataTable con tamaño de página predeterminado de 5
    tblCurso = $('#cursoTable').DataTable({
        autoWidth: false,
        responsive: true,
        pageLength: 5,
        dom: 'rt', // Sin controles nativos de DataTables/Bootstrap
        language: {
            emptyTable: "No hay cursos registrados",
            zeroRecords: "No se encontraron cursos que coincidan con la búsqueda"
        },
        ajax: {
            url: API_URL,
            method: 'POST',
            data: { buscarTodos: 'true' },
            dataSrc: 'data'
        },
        columns: [
            // 0: # (Numeración secuencial)
            // 0: ID (ID del registro)
            {
                data: 'id_curso',
                className: 'fw-bold text-muted',
                render: (data, type, row, meta) => `<span>${meta.row + 1}</span>`
            },
            // 1: Nombre del Curso
            {
                data: 'nombre_curso',
                render: (data) => `<span class="fw-semibold text-white">${data || 'Sin nombre'}</span>`
            },
            // 2: Tipo de Curso (Morado para "Curso completo", Rosado para "Masterclass")
            {
                data: 'tipo_curso',
                render: (data) => {
                    const tipo = data || 'Curso completo';
                    if (tipo.toLowerCase().includes('masterclass')) {
                        return `<span class="badge-tipo-curso tipo-masterclass">${tipo}</span>`;
                    }
                    return `<span class="badge-tipo-curso tipo-completo">${tipo}</span>`;
                }
            },
            // 3: Precio (Rosa para el precio activo, Morado claro pálido para el tachado)
            {
                data: null,
                render: (data) => {
                    const normal = parseFloat(data.precio_normal || 0).toFixed(2);
                    const preventa = data.precio_preventa ? parseFloat(data.precio_preventa).toFixed(2) : null;
                    if (preventa && parseFloat(preventa) > 0) {
                        return `<div><span class="price-tag-modern">$${preventa}</span><span class="price-tag-struck">$${normal}</span></div>`;
                    }
                    return `<span class="price-tag-modern">$${normal}</span>`;
                }
            },
            // 4: Estado (Dot indicador: Activo, En pausa, Cerrado)
            {
                data: 'estatus_curso',
                render: (data) => {
                    const status = data || 'Borrador';
                    if (status === 'Venta Normal' || status === 'Activo') {
                        return `<span class="badge-status-dot status-active">Activo</span>`;
                    } else if (status === 'Preventa') {
                        return `<span class="badge-status-dot status-active">Preventa</span>`;
                    } else if (status === 'Borrador' || status === 'En pausa') {
                        return `<span class="badge-status-dot status-pause">En pausa</span>`;
                    } else if (status === 'Cerrado') {
                        return `<span class="badge-status-dot status-closed">Cerrado</span>`;
                    }
                    return `<span class="badge-status-dot status-pause">${status}</span>`;
                }
            },
            // 5: Fecha de Inicio (formato DD/MM/YYYY)
            {
                data: 'fecha_inicio_curso',
                render: (data) => {
                    if (!data) return '<span class="text-muted">-</span>';
                    const parts = data.split(' ')[0].split('-');
                    if (parts.length === 3) {
                        return `<span>${parts[2]}/${parts[1]}/${parts[0]}</span>`;
                    }
                    return `<span>${data}</span>`;
                }
            },
            // 6: Acciones (Iconos Circulares Rosas: Ver, Editar, Eliminar)
            {
                data: null,
                orderable: false,
                searchable: false,
                className: 'text-end align-middle',
                render: (data, type, row) => {
                    const id = row.id_curso || '';
                    return `
                        <div class="modern-action-btns">
                            <button value="${id}" class="btn-circle-action btn-table-ver" title="Ver Detalle" type="button">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button value="${id}" class="btn-circle-action btn-table-editar" title="Editar" type="button">
                                <i class="fas fa-pencil-alt"></i>
                            </button>
                            <button value="${id}" class="btn-circle-action btn-table-eliminar" title="Eliminar" type="button">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    `;
                }
            }
        ],
        initComplete: function () {
            const api = this.api();
            setTimeout(() => {
                hideSkeleton('#cursoSkeleton', '#cursoTableContainer');
                animateIn('#cursoTableContainer', 300);
            }, 300);
            actualizarPaginacionYInfo(api);
        },
        drawCallback: function () {
            actualizarPaginacionYInfo(this.api());
        }
    });

    // 3. Lógica de Dropdowns Personalizados (Puro CSS y JS, sin azul nativo)
    $(document).on('click', '.custom-dropdown-toggle', function (e) {
        e.stopPropagation();
        const $parent = $(this).closest('.custom-dropdown');
        $('.custom-dropdown').not($parent).removeClass('active');
        $parent.toggleClass('active');
    });

    $(document).on('click', function () {
        $('.custom-dropdown').removeClass('active');
    });

    // Selección de opción en dropdowns
    $(document).on('click', '.custom-dropdown-item', function (e) {
        e.stopPropagation();
        const $item = $(this);
        const $parent = $item.closest('.custom-dropdown');
        const val = $item.data('value');
        const text = $item.text();

        $parent.find('.custom-dropdown-item').removeClass('active');
        $item.addClass('active');
        $parent.find('.dropdown-selected-text').text(text);
        $parent.find('input[type="hidden"]').val(val);
        $parent.removeClass('active');

        // Trigger para filtrar según el dropdown
        if ($parent.attr('id') === 'dropdownTipoCurso') {
            tblCurso.column(2).search(val).draw();
        } else if ($parent.attr('id') === 'dropdownEstadoCurso') {
            if (val === 'Activo') {
                tblCurso.column(4).search('Activo|Preventa', true, false).draw();
            } else {
                tblCurso.column(4).search(val).draw();
            }
        } else if ($parent.attr('id') === 'dropdownPerPage') {
            tblCurso.page.len(parseInt(val, 10)).draw();
        }
    });

    // 4. Búsqueda en vivo por nombre del curso
    $('#filterSearchInput').on('keyup input', function () {
        tblCurso.column(1).search(this.value).draw();
    });

    // 5. Abrir modal Crear
    $(document).on('click', '.btn-registrar-curso, [data-modal="modalCrear"]', function () {
        const form = document.getElementById('formCrearCurso');
        if (form) {
            form.reset();
            limpiarValidaciones(form);
        }
        abrirModal('modalCrear');
    });

    // 6. Ver Detalle de Curso
    $(document).on('click', '.btn-table-ver', async function () {
        const id = this.value;
        if (!id) return;

        showLoading('Cargando detalle...', 'Obteniendo información del curso');

        await executeAjax(
            getById(API_URL, id),
            null,
            (response) => {
                closeLoading();
                const c = response.data;
                if (!c) return;

                $('#detail_id_curso').text(c.id_curso);
                $('#detail_nombre_curso').text(c.nombre_curso || 'N/A');
                $('#detail_descripcion_curso').text(c.descripcion_curso || 'Sin descripción');
                $('#detail_tipo_curso').text(c.tipo_curso || 'N/A');
                $('#detail_precio_preventa').text(c.precio_preventa ? `$${parseFloat(c.precio_preventa).toFixed(2)}` : 'N/A');
                $('#detail_precio_normal').text(`$${parseFloat(c.precio_normal || 0).toFixed(2)}`);
                $('#detail_fecha_fin_preventa').text(c.fecha_fin_preventa || 'N/A');
                $('#detail_fecha_inicio_curso').text(c.fecha_inicio_curso || 'N/A');
                $('#detail_fecha_fin_curso').text(c.fecha_fin_curso || 'N/A');
                $('#detail_limite_cupos').text(c.limite_cupos || 'Ilimitado');
                $('#detail_estatus_curso').text(c.estatus_curso || 'N/A');

                abrirModal('modalDetalle');
            }
        );
    });

    // 7. Submit Formulario Crear
    $(document).on('submit', '#formCrearCurso', async function (e) {
        e.preventDefault();

        const form = this;
        const formData = new FormData(form);

        showLoading('Registrando...', 'Guardando curso en el sistema');

        const success = await executeAjax(
            create(API_URL, formData),
            'Curso registrado exitosamente',
            () => {
                closeLoading();
                cerrarModal('modalCrear');
                form.reset();
                limpiarValidaciones(form);
                reloadDataTable(tblCurso);
            }
        );

        if (!success) closeLoading();
    });

    // 8. Cargar y abrir Modal Editar
    $(document).on('click', '.btn-table-editar', async function () {
        const id = this.value;
        if (!id) return;

        showLoading('Cargando datos...', 'Obteniendo información para edición');

        await executeAjax(
            getById(API_URL, id),
            null,
            (response) => {
                closeLoading();
                const c = response.data;
                if (!c) return;

                const form = document.getElementById('formEditarCurso');
                if (form) {
                    form.reset();
                    limpiarValidaciones(form);
                }

                $('#edit_id_curso').val(c.id_curso);

                const fields = [
                    'nombre_curso', 'descripcion_curso', 'tipo_curso',
                    'precio_preventa', 'precio_normal', 'fecha_fin_preventa',
                    'fecha_inicio_curso', 'fecha_fin_curso', 'limite_cupos', 'estatus_curso'
                ];

                fields.forEach(field => {
                    $(`#formEditarCurso [name="${field}"]`).val(c[field] || '');
                });

                abrirModal('modalEditar');
            }
        );
    });

    // 9. Submit Formulario Editar
    $(document).on('submit', '#formEditarCurso', async function (e) {
        e.preventDefault();

        const form = this;
        const formData = new FormData(form);

        showLoading('Actualizando...', 'Guardando cambios del curso');

        const success = await executeAjax(
            update(API_URL, formData),
            'Curso actualizado exitosamente',
            () => {
                closeLoading();
                cerrarModal('modalEditar');
                form.reset();
                limpiarValidaciones(form);
                reloadDataTable(tblCurso);
            }
        );

        if (!success) closeLoading();
    });

    // 10. Eliminar Curso con Confirmación SweetAlert2
    $(document).on('click', '.btn-table-eliminar', async function () {
        const id = this.value;
        if (!id) return;

        const confirmado = await showConfirm(
            '¿Eliminar curso?',
            'Esta acción cambiará el estatus del curso a inactivo.',
            'Sí, eliminar',
            'Cancelar'
        );

        if (confirmado) {
            showLoading('Eliminando...', 'Actualizando estado del registro');
            const success = await executeAjax(
                remove(API_URL, id),
                'Curso eliminado correctamente',
                () => {
                    closeLoading();
                    reloadDataTable(tblCurso);
                }
            );
            if (!success) closeLoading();
        }
    });

    // 11. Cierre de modales con botones de cancelar / cruz
    $(document).on('click', '.close-modal, .btn-cerrar-modal, [data-close-modal]', function () {
        const modalId = $(this).closest('.modal').attr('id');
        if (modalId) cerrarModal(modalId);
    });
});
