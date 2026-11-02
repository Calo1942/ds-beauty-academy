// evaluacionDataTable.js - Lógica de tabla DataTables, llamadas AJAX y modales para Evaluaciones
import { createDataTable, reloadDataTable, createActionsColumn } from '../helpers/dataTable.js';
import { getById, create, update, remove, executeAjax } from '../helpers/ajax.js';
import { showConfirm, showLoading, closeLoading, showToast } from '../helpers/sweetalert.js';
import { animateIn } from '../helpers/animations.js';
import { showSkeleton, hideSkeleton } from '../helpers/skeleton.js';
import { limpiarValidaciones } from '../helpers/validation.js';

const API_URL = window.evaluacionApiUrl || '?url=evaluacion';

// Cargar opciones de Oferta Académica (instructor_curso) vía AJAX
async function cargarAsignacionesAcademicas(selectSelector, valorSeleccionado = null) {
    const $select = $(selectSelector);
    if (!$select.length) return;

    try {
        const response = await $.ajax({
            url: '?url=instructor_curso',
            method: 'POST',
            data: { buscarTodos: true },
            dataType: 'json'
        });

        if (response && response.status === 'success' && Array.isArray(response.data)) {
            let options = '<option value="">-- Seleccione una asignación académica --</option>';
            response.data.forEach(item => {
                const id = item.id_instructor_curso;
                const curso = item.nombre_curso || 'Curso sin nombre';
                const instructor = item.nombre_instructor || 'Sin instructor';
                const selected = (valorSeleccionado && valorSeleccionado == id) ? 'selected' : '';
                options += `<option value="${id}" ${selected}>${curso} - Prof. ${instructor}</option>`;
            });
            $select.html(options);
            if (valorSeleccionado) {
                $select.val(valorSeleccionado);
            }
        }
    } catch (err) {
        console.error('Error cargando asignaciones académicas:', err);
    }
}

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
    showSkeleton('#evaluacionSkeleton', '#evaluacionTableContainer');

    // 2. Inicializar DataTable
    const tblEvaluacion = createDataTable('#evaluacionTable', API_URL, [
        { 
            data: 'id_evaluacion',
            className: 'text-muted fw-bold'
        },
        { 
            data: 'titulo_evaluacion',
            render: (data) => `<span class="fw-semibold text-white"><i class="fas fa-clipboard-check me-2 text-accent" style="color: var(--accent);"></i>${data || 'Sin título'}</span>`
        },
        { 
            data: 'tipo_evaluacion',
            render: (data) => {
                const isPractica = (data === 'practica');
                return `<span class="badge ${isPractica ? 'bg-primary' : 'bg-info'} text-white px-2 py-1 text-capitalize">${data || 'General'}</span>`;
            }
        },
        { 
            data: 'orden_evaluacion',
            render: (data) => `<span class="badge bg-secondary text-light px-2 py-1">Eval ${data || 1}</span>`
        },
        { 
            data: 'id_instructor_curso',
            render: (data) => `<span class="text-light">Asignación #${data || 'N/A'}</span>`
        },
        { 
            data: 'estatus_evaluacion',
            render: (data) => {
                const activo = data == 1 || data === 'Activa';
                return `<span class="badge ${activo ? 'bg-success text-white' : 'bg-danger text-white'} px-2 py-1">${activo ? 'Activa' : 'Inactiva'}</span>`;
            }
        },
        createActionsColumn({
            idField: 'id_evaluacion',
            btnVer: true,
            btnEditar: true,
            btnEliminar: true
        })
    ], {
        initComplete: function () {
            setTimeout(() => {
                hideSkeleton('#evaluacionSkeleton', '#evaluacionTableContainer');
                animateIn('#evaluacionTableContainer', 300);
            }, 400);
        }
    });

    // 3. Abrir modal Crear
    $(document).on('click', '.btn-registrar-evaluacion, [data-modal="modalCrear"]', function () {
        const form = document.getElementById('formCrearEvaluacion');
        if (form) {
            form.reset();
            limpiarValidaciones(form);
        }
        cargarAsignacionesAcademicas('#select_instructor_curso');
        abrirModal('modalCrear');
    });

    // 4. Ver Detalle de Evaluación
    $(document).on('click', '.btn-table-ver', async function () {
        const id = this.value;
        if (!id) return;

        showLoading('Cargando detalle...', 'Obteniendo información de la evaluación');

        await executeAjax(
            getById(API_URL, id),
            null,
            (response) => {
                closeLoading();
                const ev = response.data;
                if (!ev) return;

                $('#detail_id_evaluacion').text(ev.id_evaluacion);
                $('#detail_titulo_evaluacion').text(ev.titulo_evaluacion || 'N/A');
                $('#detail_tipo_evaluacion').text(ev.tipo_evaluacion || 'N/A');
                $('#detail_orden_evaluacion').text(ev.orden_evaluacion || 'N/A');
                $('#detail_descripcion_evaluacion').text(ev.descripcion_evaluacion || 'Sin descripción');
                $('#detail_id_instructor_curso').text(ev.id_instructor_curso || 'N/A');

                abrirModal('modalDetalle');
            }
        );
    });

    // 5. Submit Formulario Crear
    $(document).on('submit', '#formCrearEvaluacion', async function (e) {
        e.preventDefault();

        const form = this;
        const formData = new FormData(form);

        showLoading('Registrando...', 'Guardando evaluación en el sistema');

        const success = await executeAjax(
            create(API_URL, formData),
            'Evaluación registrada exitosamente',
            () => {
                closeLoading();
                cerrarModal('modalCrear');
                form.reset();
                limpiarValidaciones(form);
                reloadDataTable(tblEvaluacion);
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
                const ev = response.data;
                if (!ev) return;

                const form = document.getElementById('formEditarEvaluacion');
                if (form) {
                    form.reset();
                    limpiarValidaciones(form);
                }

                $('#edit_id_evaluacion').val(ev.id_evaluacion);

                const fields = [
                    'titulo_evaluacion', 'descripcion_evaluacion',
                    'orden_evaluacion', 'tipo_evaluacion', 'estatus_evaluacion'
                ];

                fields.forEach(field => {
                    $(`#formEditarEvaluacion [name="${field}"]`).val(ev[field] || '');
                });

                cargarAsignacionesAcademicas('#edit_select_instructor_curso_eval', ev.id_instructor_curso);

                abrirModal('modalEditar');
            }
        );
    });

    // 7. Submit Formulario Editar
    $(document).on('submit', '#formEditarEvaluacion', async function (e) {
        e.preventDefault();

        const form = this;
        const formData = new FormData(form);

        showLoading('Actualizando...', 'Guardando cambios de la evaluación');

        const success = await executeAjax(
            update(API_URL, formData),
            'Evaluación actualizada exitosamente',
            () => {
                closeLoading();
                cerrarModal('modalEditar');
                form.reset();
                limpiarValidaciones(form);
                reloadDataTable(tblEvaluacion);
            }
        );

        if (!success) closeLoading();
    });

    // 8. Eliminar Evaluación con Confirmación SweetAlert2
    $(document).on('click', '.btn-table-eliminar', async function () {
        const id = this.value;
        if (!id) return;

        const confirmado = await showConfirm(
            '¿Eliminar evaluación?',
            'Esta acción cambiará el estatus de la evaluación a inactivo.',
            'Sí, eliminar',
            'Cancelar'
        );

        if (confirmado) {
            showLoading('Eliminando...', 'Actualizando estado del registro');
            const success = await executeAjax(
                remove(API_URL, id),
                'Evaluación eliminada correctamente',
                () => {
                    closeLoading();
                    reloadDataTable(tblEvaluacion);
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
