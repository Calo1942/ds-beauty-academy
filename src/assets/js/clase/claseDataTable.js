// claseDataTable.js - Lógica de tabla DataTables, llamadas AJAX y modales para Clases
import { createDataTable, reloadDataTable, createActionsColumn } from '../helpers/dataTable.js';
import { getById, create, update, remove, executeAjax } from '../helpers/ajax.js';
import { showConfirm, showLoading, closeLoading } from '../helpers/sweetalert.js';
import { animateIn } from '../helpers/animations.js';
import { showSkeleton, hideSkeleton } from '../helpers/skeleton.js';
import { limpiarValidaciones } from '../helpers/validation.js';

const API_URL = window.claseApiUrl || '?url=clase';

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
    showSkeleton('#claseSkeleton', '#claseTableContainer');

    // 2. Inicializar DataTable
    const tblClase = createDataTable('#claseTable', API_URL, [
        { 
            data: 'id_clase',
            className: 'text-muted fw-bold'
        },
        { 
            data: 'titulo_clase',
            render: (data) => `<span class="fw-semibold text-white"><i class="fas fa-play-circle me-2 text-accent" style="color: var(--accent);"></i>${data || 'Sin título'}</span>`
        },
        { 
            data: 'orden_clase',
            render: (data) => `<span class="badge bg-secondary text-light px-2 py-1">Clase ${data || 1}</span>`
        },
        { 
            data: 'id_instructor_curso',
            render: (data) => `<span class="text-light">Asignación #${data || 'N/A'}</span>`
        },
        { 
            data: 'estatus_clase',
            render: (data) => {
                const activo = data == 1 || data === 'Activo';
                return `<span class="badge ${activo ? 'bg-success text-white' : 'bg-danger text-white'} px-2 py-1">${activo ? 'Activa' : 'Inactiva'}</span>`;
            }
        },
        createActionsColumn({
            idField: 'id_clase',
            btnVer: true,
            btnEditar: true,
            btnEliminar: true
        })
    ], {
        initComplete: function () {
            setTimeout(() => {
                hideSkeleton('#claseSkeleton', '#claseTableContainer');
                animateIn('#claseTableContainer', 300);
            }, 400);
        }
    });

    // 3. Abrir modal Crear
    $(document).on('click', '.btn-registrar-clase, [data-modal="modalCrear"]', function () {
        const form = document.getElementById('formCrearClase');
        if (form) {
            form.reset();
            limpiarValidaciones(form);
        }
        abrirModal('modalCrear');
    });

    // 4. Ver Detalle de Clase
    $(document).on('click', '.btn-table-ver', async function () {
        const id = this.value;
        if (!id) return;

        showLoading('Cargando detalle...', 'Obteniendo información de la clase');

        await executeAjax(
            getById(API_URL, id),
            null,
            (response) => {
                closeLoading();
                const cl = response.data;
                if (!cl) return;

                $('#detail_id_clase').text(cl.id_clase);
                $('#detail_titulo_clase').text(cl.titulo_clase || 'N/A');
                $('#detail_descripcion_clase').text(cl.descripcion_clase || 'Sin descripción');
                $('#detail_url_video_clase').text(cl.url_video_clase || 'N/A');
                $('#detail_orden_clase').text(cl.orden_clase || 'N/A');
                $('#detail_id_instructor_curso').text(cl.id_instructor_curso || 'N/A');
                $('#detail_estatus_clase').text((cl.estatus_clase == 1) ? 'Activa' : 'Inactiva');

                abrirModal('modalDetalle');
            }
        );
    });

    // 5. Submit Formulario Crear
    $(document).on('submit', '#formCrearClase', async function (e) {
        e.preventDefault();

        const form = this;
        const formData = new FormData(form);

        showLoading('Registrando...', 'Guardando clase en el sistema');

        const success = await executeAjax(
            create(API_URL, formData),
            'Clase registrada exitosamente',
            () => {
                closeLoading();
                cerrarModal('modalCrear');
                form.reset();
                limpiarValidaciones(form);
                reloadDataTable(tblClase);
            }
        );

        if (!success) closeLoading();
    });

    // 6. Cargar y abrir Modal Editar
    $(document).on('click', '.btn-table-editar', async function () {
        const id = this.value;
        if (!id) return;

        showLoading('Cargando datos...', 'Obteniendo información para edición');

        await executeAjax(
            getById(API_URL, id),
            null,
            (response) => {
                closeLoading();
                const cl = response.data;
                if (!cl) return;

                const form = document.getElementById('formEditarClase');
                if (form) {
                    form.reset();
                    limpiarValidaciones(form);
                }

                $('#edit_id_clase').val(cl.id_clase);

                const fields = [
                    'titulo_clase', 'descripcion_clase', 'url_video_clase',
                    'orden_clase', 'id_instructor_curso', 'estatus_clase'
                ];

                fields.forEach(field => {
                    $(`#formEditarClase [name="${field}"]`).val(cl[field] || '');
                });

                abrirModal('modalEditar');
            }
        );
    });

    // 7. Submit Formulario Editar
    $(document).on('submit', '#formEditarClase', async function (e) {
        e.preventDefault();

        const form = this;
        const formData = new FormData(form);

        showLoading('Actualizando...', 'Guardando cambios de la clase');

        const success = await executeAjax(
            update(API_URL, formData),
            'Clase actualizada exitosamente',
            () => {
                closeLoading();
                cerrarModal('modalEditar');
                form.reset();
                limpiarValidaciones(form);
                reloadDataTable(tblClase);
            }
        );

        if (!success) closeLoading();
    });

    // 8. Eliminar Clase con Confirmación SweetAlert2
    $(document).on('click', '.btn-table-eliminar', async function () {
        const id = this.value;
        if (!id) return;

        const confirmado = await showConfirm(
            '¿Eliminar clase?',
            'Esta acción cambiará el estatus de la clase a inactivo.',
            'Sí, eliminar',
            'Cancelar'
        );

        if (confirmado) {
            showLoading('Eliminando...', 'Actualizando estado del registro');
            const success = await executeAjax(
                remove(API_URL, id),
                'Clase eliminada correctamente',
                () => {
                    closeLoading();
                    reloadDataTable(tblClase);
                }
            );
            if (!success) closeLoading();
        }
    });

    // 9. Cierre de modales con botones de cancelar / cruz
    $(document).on('click', '.close-modal, .btn-cerrar-modal, [data-close-modal]', function () {
        const modalId = $(this).closest('.modal').attr('id');
        if (modalId) cerrarModal(modalId);
    });
});
