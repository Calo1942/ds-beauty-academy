// recursoDataTable.js - Lógica de tabla DataTables, llamadas AJAX y modales para Recursos
import { createDataTable, reloadDataTable, createActionsColumn } from '../helpers/dataTable.js';
import { getById, create, update, remove, executeAjax } from '../helpers/ajax.js';
import { showConfirm, showLoading, closeLoading, showToast } from '../helpers/sweetalert.js';
import { animateIn } from '../helpers/animations.js';
import { showSkeleton, hideSkeleton } from '../helpers/skeleton.js';
import { limpiarValidaciones } from '../helpers/validation.js';

const API_URL = window.recursoApiUrl || '?url=recurso';

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
    showSkeleton('#recursoSkeleton', '#recursoTableContainer');

    // 2. Inicializar DataTable
    const tblRecurso = createDataTable('#recursoTable', API_URL, [
        { 
            data: 'id_recurso',
            className: 'text-muted fw-bold'
        },
        { 
            data: 'nombre_recurso',
            render: (data) => `<span class="fw-semibold text-white"><i class="fas fa-file-alt me-2 text-accent" style="color: var(--accent);"></i>${data || 'Sin nombre'}</span>`
        },
        createActionsColumn({
            idField: 'id_recurso',
            btnVer: true,
            btnEditar: true,
            btnEliminar: true
        })
    ], {
        initComplete: function () {
            setTimeout(() => {
                hideSkeleton('#recursoSkeleton', '#recursoTableContainer');
                animateIn('#recursoTableContainer', 300);
            }, 400);
        }
    });

    // 3. Abrir modal Crear
    $(document).on('click', '.btn-registrar-recurso, [data-modal="modalCrear"]', function () {
        const form = document.getElementById('formCrearRecurso');
        if (form) {
            form.reset();
            limpiarValidaciones(form);
        }
        abrirModal('modalCrear');
    });

    // 4. Ver Detalle de Recurso
    $(document).on('click', '.btn-table-ver', async function () {
        const id = this.value;
        if (!id) return;

        showLoading('Cargando detalle...', 'Obteniendo información del recurso');

        await executeAjax(
            getById(API_URL, id),
            null,
            (response) => {
                closeLoading();
                const r = response.data;
                if (!r) return;

                $('#detail_id_recurso').text(r.id_recurso);
                $('#detail_nombre_recurso').text(r.nombre_recurso || 'N/A');
                $('#detail_descripcion_recurso').text(r.descripcion_recurso || 'Sin descripción');
                
                if (r.url_archivo_recurso) {
                    $('#detail_url_archivo_recurso').html(`<a href="${r.url_archivo_recurso}" target="_blank" class="text-accent" style="color: var(--accent);"><i class="fas fa-external-link-alt me-1"></i>Abrir Archivo</a>`);
                } else {
                    $('#detail_url_archivo_recurso').text('No especificada');
                }

                abrirModal('modalDetalle');
            }
        );
    });

    // 5. Submit Formulario Crear
    $(document).on('submit', '#formCrearRecurso', async function (e) {
        e.preventDefault();

        const form = this;
        const formData = new FormData(form);

        showLoading('Registrando...', 'Guardando recurso en el sistema');

        const success = await executeAjax(
            create(API_URL, formData),
            'Recurso registrado exitosamente',
            () => {
                closeLoading();
                cerrarModal('modalCrear');
                form.reset();
                limpiarValidaciones(form);
                reloadDataTable(tblRecurso);
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
                const r = response.data;
                if (!r) return;

                const form = document.getElementById('formEditarRecurso');
                if (form) {
                    form.reset();
                    limpiarValidaciones(form);
                }

                $('#edit_id_recurso').val(r.id_recurso);
                $('#formEditarRecurso [name="nombre_recurso"]').val(r.nombre_recurso || '');
                $('#formEditarRecurso [name="descripcion_recurso"]').val(r.descripcion_recurso || '');
                $('#formEditarRecurso [name="url_archivo_recurso"]').val(r.url_archivo_recurso || '');

                abrirModal('modalEditar');
            }
        );
    });

    // 7. Submit Formulario Editar
    $(document).on('submit', '#formEditarRecurso', async function (e) {
        e.preventDefault();

        const form = this;
        const formData = new FormData(form);

        showLoading('Actualizando...', 'Guardando cambios del recurso');

        const success = await executeAjax(
            update(API_URL, formData),
            'Recurso actualizado exitosamente',
            () => {
                closeLoading();
                cerrarModal('modalEditar');
                form.reset();
                limpiarValidaciones(form);
                reloadDataTable(tblRecurso);
            }
        );

        if (!success) closeLoading();
    });

    // 8. Eliminar Recurso con Confirmación SweetAlert2
    $(document).on('click', '.btn-table-eliminar', async function () {
        const id = this.value;
        if (!id) return;

        const confirmado = await showConfirm(
            '¿Eliminar recurso?',
            'Esta acción eliminará el recurso seleccionado.',
            'Sí, eliminar',
            'Cancelar'
        );

        if (confirmado) {
            showLoading('Eliminando...', 'Actualizando estado del registro');
            const success = await executeAjax(
                remove(API_URL, id),
                'Recurso eliminado correctamente',
                () => {
                    closeLoading();
                    reloadDataTable(tblRecurso);
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
