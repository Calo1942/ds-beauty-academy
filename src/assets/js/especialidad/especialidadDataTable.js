// especialidadDataTable.js - Lógica de tabla DataTables, llamadas AJAX y modales para Especialidades
import { createDataTable, reloadDataTable, createActionsColumn } from '../helpers/dataTable.js';
import { getById, create, update, remove, executeAjax } from '../helpers/ajax.js';
import { showConfirm, showLoading, closeLoading } from '../helpers/sweetalert.js';
import { animateIn } from '../helpers/animations.js';
import { showSkeleton, hideSkeleton } from '../helpers/skeleton.js';
import { limpiarValidaciones } from '../helpers/validation.js';

const API_URL = window.especialidadApiUrl || '?url=especialidad';

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
    showSkeleton('#especialidadSkeleton', '#especialidadTableContainer');

    // 2. Inicializar DataTable
    const tblEspecialidad = createDataTable('#especialidadTable', API_URL, [
        { 
            data: 'id_especialidad',
            className: 'text-muted fw-bold'
        },
        { 
            data: 'nombre_especialidad',
            render: (data) => `<span class="fw-semibold text-white"><i class="fas fa-certificate me-2 text-muted"></i>${data || 'Sin nombre'}</span>`
        },
        createActionsColumn({
            idField: 'id_especialidad',
            btnVer: true,
            btnEditar: true,
            btnEliminar: true
        })
    ], {
        initComplete: function () {
            setTimeout(() => {
                hideSkeleton('#especialidadSkeleton', '#especialidadTableContainer');
                animateIn('#especialidadTableContainer', 300);
            }, 400);
        }
    });

    // 3. Abrir modal Crear
    $(document).on('click', '.btn-registrar-especialidad, [data-modal="modalCrear"]', function () {
        const form = document.getElementById('formCrearEspecialidad');
        if (form) {
            form.reset();
            limpiarValidaciones(form);
        }
        abrirModal('modalCrear');
    });

    // 4. Ver Detalle de Especialidad
    $(document).on('click', '.btn-table-ver', async function () {
        const id = this.value;
        if (!id) return;

        showLoading('Cargando detalle...', 'Obteniendo información de la especialidad');

        await executeAjax(
            getById(API_URL, id),
            null,
            (response) => {
                closeLoading();
                const esp = response.data;
                if (!esp) return;

                $('#detail_id_especialidad').text(esp.id_especialidad);
                $('#detail_nombre_especialidad').text(esp.nombre_especialidad || 'N/A');

                abrirModal('modalDetalle');
            }
        );
    });

    // 5. Submit Formulario Crear
    $(document).on('submit', '#formCrearEspecialidad', async function (e) {
        e.preventDefault();

        const form = this;
        const formData = new FormData(form);

        showLoading('Registrando...', 'Guardando especialidad en el sistema');

        const success = await executeAjax(
            create(API_URL, formData),
            'Especialidad registrada exitosamente',
            () => {
                closeLoading();
                cerrarModal('modalCrear');
                form.reset();
                limpiarValidaciones(form);
                reloadDataTable(tblEspecialidad);
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
                const esp = response.data;
                if (!esp) return;

                const form = document.getElementById('formEditarEspecialidad');
                if (form) {
                    form.reset();
                    limpiarValidaciones(form);
                }

                $('#edit_id_especialidad').val(esp.id_especialidad);
                $('#formEditarEspecialidad [name="nombre_especialidad"]').val(esp.nombre_especialidad || '');

                abrirModal('modalEditar');
            }
        );
    });

    // 7. Submit Formulario Editar
    $(document).on('submit', '#formEditarEspecialidad', async function (e) {
        e.preventDefault();

        const form = this;
        const formData = new FormData(form);

        showLoading('Actualizando...', 'Guardando cambios de la especialidad');

        const success = await executeAjax(
            update(API_URL, formData),
            'Especialidad actualizada exitosamente',
            () => {
                closeLoading();
                cerrarModal('modalEditar');
                form.reset();
                limpiarValidaciones(form);
                reloadDataTable(tblEspecialidad);
            }
        );

        if (!success) closeLoading();
    });

    // 8. Eliminar Especialidad con Confirmación SweetAlert2
    $(document).on('click', '.btn-table-eliminar', async function () {
        const id = this.value;
        if (!id) return;

        const confirmado = await showConfirm(
            '¿Eliminar especialidad?',
            'Esta acción cambiará el estatus de la especialidad a inactivo.',
            'Sí, eliminar',
            'Cancelar'
        );

        if (confirmado) {
            showLoading('Eliminando...', 'Actualizando estado del registro');
            const success = await executeAjax(
                remove(API_URL, id),
                'Especialidad eliminada correctamente',
                () => {
                    closeLoading();
                    reloadDataTable(tblEspecialidad);
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
