// cursoDataTable.js - Lógica de tabla DataTables, llamadas AJAX y modales para Cursos
import { createDataTable, reloadDataTable, createActionsColumn } from '../helpers/dataTable.js';
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

    // 2. Inicializar DataTable
    const tblCurso = createDataTable('#cursoTable', API_URL, [
        { 
            data: 'id_curso',
            className: 'text-muted fw-bold'
        },
        { 
            data: 'nombre_curso',
            render: (data) => `<span class="fw-semibold text-white">${data || 'Sin nombre'}</span>`
        },
        { 
            data: 'tipo_curso',
            render: (data) => `<span class="badge bg-secondary text-light px-2 py-1">${data || 'General'}</span>`
        },
        { 
            data: null,
            render: (data) => {
                const normal = parseFloat(data.precio_normal || 0).toFixed(2);
                const preventa = data.precio_preventa ? parseFloat(data.precio_preventa).toFixed(2) : null;
                if (preventa && preventa > 0) {
                    return `<div><span class="text-accent fw-bold" style="color: var(--accent);">$${preventa}</span> <small class="text-muted text-decoration-line-through">$${normal}</small></div>`;
                }
                return `<span class="text-light fw-bold">$${normal}</span>`;
            }
        },
        { 
            data: 'estatus_curso',
            render: (data) => {
                let badgeClass = 'bg-secondary';
                if (data === 'Preventa') badgeClass = 'bg-warning text-dark';
                else if (data === 'Venta Normal') badgeClass = 'bg-success text-white';
                else if (data === 'Cerrado') badgeClass = 'bg-danger text-white';
                return `<span class="badge ${badgeClass} px-2 py-1">${data || 'Borrador'}</span>`;
            }
        },
        createActionsColumn({
            idField: 'id_curso',
            btnVer: true,
            btnEditar: true,
            btnEliminar: true
        })
    ], {
        initComplete: function () {
            setTimeout(() => {
                hideSkeleton('#cursoSkeleton', '#cursoTableContainer');
                animateIn('#cursoTableContainer', 300);
            }, 400);
        }
    });

    // 3. Abrir modal Crear
    $(document).on('click', '.btn-registrar-curso, [data-modal="modalCrear"]', function () {
        const form = document.getElementById('formCrearCurso');
        if (form) {
            form.reset();
            limpiarValidaciones(form);
        }
        abrirModal('modalCrear');
    });

    // 4. Ver Detalle de Curso
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

    // 5. Submit Formulario Crear
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

    // 7. Submit Formulario Editar
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

    // 8. Eliminar Curso con Confirmación SweetAlert2
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

    // 9. Cierre de modales con botones de cancelar / cruz
    $(document).on('click', '.close-modal, .btn-cerrar-modal, [data-close-modal]', function () {
        const modalId = $(this).closest('.modal').attr('id');
        if (modalId) cerrarModal(modalId);
    });
});
