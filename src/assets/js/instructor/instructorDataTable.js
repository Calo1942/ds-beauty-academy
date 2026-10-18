// instructorDataTable.js - Lógica de tabla DataTables, llamadas AJAX y modales para Instructores
import { createDataTable, reloadDataTable, createActionsColumn } from '../helpers/dataTable.js';
import { getById, create, update, remove, executeAjax } from '../helpers/ajax.js';
import { showConfirm, showLoading, closeLoading } from '../helpers/sweetalert.js';
import { animateIn } from '../helpers/animations.js';
import { showSkeleton, hideSkeleton } from '../helpers/skeleton.js';
import { limpiarValidaciones } from '../helpers/validation.js';

const API_URL = window.instructorApiUrl || '?url=instructor';

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
    showSkeleton('#instructorSkeleton', '#instructorTableContainer');

    // 2. Inicializar DataTable
    const tblInstructor = createDataTable('#instructorTable', API_URL, [
        { 
            data: 'id_instructor',
            className: 'text-muted fw-bold'
        },
        { 
            data: null,
            render: (data) => {
                const pNombre = data.primer_nombre_instructor || '';
                const sNombre = data.segundo_nombre_instructor ? ` ${data.segundo_nombre_instructor}` : '';
                const pApellido = data.primer_apellido_instructor ? ` ${data.primer_apellido_instructor}` : '';
                const sApellido = data.segundo_apellido_instructor ? ` ${data.segundo_apellido_instructor}` : '';
                const nombreCompleto = `${pNombre}${sNombre}${pApellido}${sApellido}`.trim();
                
                return `<div class="fw-semibold text-white text-capitalize">${nombreCompleto || 'Sin nombre'}</div>`;
            }
        },
        { 
            data: 'cedula_instructor',
            render: (data) => `<span class="badge bg-dark border border-secondary text-light px-2 py-1">${data || 'N/A'}</span>`
        },
        { 
            data: 'correo_instructor',
            render: (data) => `<span class="text-light">${data || 'N/A'}</span>`
        },
        { 
            data: 'usuario_instagram_instructor',
            render: (data) => data ? `<span class="text-accent" style="color: var(--accent);"><i class="fab fa-instagram me-1"></i>${data}</span>` : '<span class="text-muted">N/A</span>'
        },
        createActionsColumn({
            idField: 'id_instructor',
            btnVer: true,
            btnEditar: true,
            btnEliminar: true
        })
    ], {
        initComplete: function () {
            setTimeout(() => {
                hideSkeleton('#instructorSkeleton', '#instructorTableContainer');
                animateIn('#instructorTableContainer', 300);
            }, 400);
        }
    });

    // 3. Abrir modal Crear
    $(document).on('click', '.btn-registrar-instructor, [data-modal="modalCrear"]', function () {
        const form = document.getElementById('formCrearInstructor');
        if (form) {
            form.reset();
            limpiarValidaciones(form);
        }
        abrirModal('modalCrear');
    });

    // 4. Ver Detalle de Instructor
    $(document).on('click', '.btn-table-ver', async function () {
        const id = this.value;
        if (!id) return;

        showLoading('Cargando detalle...', 'Obteniendo información del instructor');

        await executeAjax(
            getById(API_URL, id),
            null,
            (response) => {
                closeLoading();
                const inst = response.data;
                if (!inst) return;

                $('#detail_id_instructor').text(inst.id_instructor);
                $('#detail_id_instructor_badge').text(inst.id_instructor);
                
                const pNombre = inst.primer_nombre_instructor || '';
                const sNombre = inst.segundo_nombre_instructor ? ` ${inst.segundo_nombre_instructor}` : '';
                const pApellido = inst.primer_apellido_instructor ? ` ${inst.primer_apellido_instructor}` : '';
                const sApellido = inst.segundo_apellido_instructor ? ` ${inst.segundo_apellido_instructor}` : '';
                const nombreCompleto = `${pNombre}${sNombre}${pApellido}${sApellido}`.trim();
                
                $('#detail_nombre_completo_header').text(nombreCompleto.toLowerCase());
                $('#detail_cedula').text(inst.cedula_instructor || 'N/A');
                $('#detail_correo').text(inst.correo_instructor || 'N/A');
                $('#detail_telefono').text(inst.telefono_principal_instructor || 'N/A');
                $('#detail_instagram').text(inst.usuario_instagram_instructor || 'N/A');

                abrirModal('modalDetalle');
            }
        );
    });

    // 5. Submit Formulario Crear
    $(document).on('submit', '#formCrearInstructor', async function (e) {
        e.preventDefault();

        const form = this;
        const formData = new FormData(form);

        showLoading('Registrando...', 'Guardando instructor en el sistema');

        const success = await executeAjax(
            create(API_URL, formData),
            'Instructor registrado exitosamente',
            () => {
                closeLoading();
                cerrarModal('modalCrear');
                form.reset();
                limpiarValidaciones(form);
                reloadDataTable(tblInstructor);
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
                const inst = response.data;
                if (!inst) return;

                const form = document.getElementById('formEditarInstructor');
                if (form) {
                    form.reset();
                    limpiarValidaciones(form);
                }

                $('#edit_id_instructor').val(inst.id_instructor);

                const fields = [
                    'primer_nombre_instructor', 'segundo_nombre_instructor',
                    'primer_apellido_instructor', 'segundo_apellido_instructor',
                    'cedula_instructor', 'correo_instructor',
                    'telefono_principal_instructor', 'telefono_alternativo_instructor',
                    'usuario_instagram_instructor'
                ];

                fields.forEach(field => {
                    $(`#formEditarInstructor [name="${field}"]`).val(inst[field] || '');
                });

                abrirModal('modalEditar');
            }
        );
    });

    // 7. Submit Formulario Editar
    $(document).on('submit', '#formEditarInstructor', async function (e) {
        e.preventDefault();

        const form = this;
        const formData = new FormData(form);

        showLoading('Actualizando...', 'Guardando cambios del instructor');

        const success = await executeAjax(
            update(API_URL, formData),
            'Instructor actualizado exitosamente',
            () => {
                closeLoading();
                cerrarModal('modalEditar');
                form.reset();
                limpiarValidaciones(form);
                reloadDataTable(tblInstructor);
            }
        );

        if (!success) closeLoading();
    });

    // 8. Eliminar Instructor con Confirmación SweetAlert2
    $(document).on('click', '.btn-table-eliminar', async function () {
        const id = this.value;
        if (!id) return;

        const confirmado = await showConfirm(
            '¿Eliminar instructor?',
            'Esta acción cambiará el estatus del instructor a inactivo.',
            'Sí, eliminar',
            'Cancelar'
        );

        if (confirmado) {
            showLoading('Eliminando...', 'Actualizando estado del registro');
            const success = await executeAjax(
                remove(API_URL, id),
                'Instructor eliminado correctamente',
                () => {
                    closeLoading();
                    reloadDataTable(tblInstructor);
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
