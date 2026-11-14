// diplomaDataTable.js - Lógica de tabla DataTables, llamadas AJAX y modales para Diplomas
import { createDataTable, reloadDataTable, createActionsColumn } from '../helpers/dataTable.js';
import { getAll, getById, create, update, remove, executeAjax } from '../helpers/ajax.js';
import { showConfirm, showLoading, closeLoading, showToast } from '../helpers/sweetalert.js';
import { animateIn } from '../helpers/animations.js';
import { showSkeleton, hideSkeleton } from '../helpers/skeleton.js';
import { limpiarValidaciones } from '../helpers/validation.js';

const API_URL = window.diplomaApiUrl || '?url=diploma';
const INSTRUCTOR_API_URL = '?url=instructor';

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

async function cargarInstructores(selectSelector) {
    const $select = $(selectSelector);
    $select.html('<option value="">Cargando instructores...</option>');

    try {
        const res = await getAll(INSTRUCTOR_API_URL);
        $select.html('<option value="">Seleccione un instructor</option>');
        if (res && res.data && Array.isArray(res.data)) {
            res.data.forEach(inst => {
                const nombre = `${inst.primer_nombre_instructor || ''} ${inst.primer_apellido_instructor || ''}`.trim();
                $select.append(`<option value="${inst.id_instructor}">${nombre} (ID: ${inst.id_instructor})</option>`);
            });
        }
    } catch (e) {
        $select.html('<option value="">Error al cargar instructores</option>');
    }
}

$(document).ready(function () {
    // 1. Mostrar skeleton mientras carga la tabla inicial
    showSkeleton('#diplomaSkeleton', '#diplomaTableContainer');

    // 2. Inicializar DataTable
    const tblDiploma = createDataTable('#diplomaTable', API_URL, [
        { 
            data: 'id_diploma',
            className: 'text-muted fw-bold'
        },
        { 
            data: 'nombre_diploma',
            render: (data) => `<span class="fw-semibold text-white"><i class="fas fa-graduation-cap me-2 text-accent" style="color: var(--accent);"></i>${data || 'Sin título'}</span>`
        },
        { 
            data: 'categoria_diploma',
            render: (data) => `<span class="badge bg-secondary text-light px-2 py-1">${data || 'General'}</span>`
        },
        { 
            data: 'nombre_instructor',
            render: (data) => `<span class="text-light">${data || 'No asignado'}</span>`
        },
        createActionsColumn({
            idField: 'id_diploma',
            btnVer: true,
            btnEditar: true,
            btnEliminar: true
        })
    ], {
        initComplete: function () {
            setTimeout(() => {
                hideSkeleton('#diplomaSkeleton', '#diplomaTableContainer');
                animateIn('#diplomaTableContainer', 300);
            }, 400);
        }
    });

    // 3. Abrir modal Crear
    $(document).on('click', '.btn-registrar-diploma, [data-modal="modalCrear"]', async function () {
        const form = document.getElementById('formCrearDiploma');
        if (form) {
            form.reset();
            limpiarValidaciones(form);
        }
        await cargarInstructores('#id_instructor_crear');
        abrirModal('modalCrear');
    });

    // 4. Ver Detalle de Diploma
    $(document).on('click', '.btn-table-ver', async function () {
        const id = this.value;
        if (!id) return;

        showLoading('Cargando detalle...', 'Obteniendo información del diploma');

        await executeAjax(
            getById(API_URL, id),
            null,
            (response) => {
                closeLoading();
                const d = response.data;
                if (!d) return;

                $('#detail_id_diploma').text(d.id_diploma);
                $('#detail_nombre_diploma').text(d.nombre_diploma || 'N/A');
                $('#detail_descripcion_diploma').text(d.descripcion_diploma || 'Sin descripción');
                $('#detail_categoria_diploma').text(d.categoria_diploma || 'N/A');
                $('#detail_instructor').text(d.nombre_instructor || 'N/A');
                
                if (d.url_pdf_diploma) {
                    $('#detail_url_pdf_diploma').html(`<a href="${d.url_pdf_diploma}" target="_blank" class="text-accent" style="color: var(--accent);"><i class="fas fa-file-pdf me-1"></i>Ver Documento</a>`);
                } else {
                    $('#detail_url_pdf_diploma').text('No adjunto');
                }

                abrirModal('modalDetalle');
            }
        );
    });

    // 5. Submit Formulario Crear
    $(document).on('submit', '#formCrearDiploma', async function (e) {
        e.preventDefault();

        const form = this;
        const formData = new FormData(form);

        showLoading('Registrando...', 'Guardando diploma en el sistema');

        const success = await executeAjax(
            create(API_URL, formData),
            'Diploma registrado exitosamente',
            () => {
                closeLoading();
                cerrarModal('modalCrear');
                form.reset();
                limpiarValidaciones(form);
                reloadDataTable(tblDiploma);
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
            async (response) => {
                closeLoading();
                const d = response.data;
                if (!d) return;

                const form = document.getElementById('formEditarDiploma');
                if (form) {
                    form.reset();
                    limpiarValidaciones(form);
                }

                await cargarInstructores('#id_instructor_editar');

                $('#edit_id_diploma').val(d.id_diploma);

                const fields = [
                    'nombre_diploma', 'descripcion_diploma', 'categoria_diploma',
                    'url_pdf_diploma', 'id_instructor'
                ];

                fields.forEach(field => {
                    $(`#formEditarDiploma [name="${field}"]`).val(d[field] || '');
                });

                abrirModal('modalEditar');
            }
        );
    });

    // 7. Submit Formulario Editar
    $(document).on('submit', '#formEditarDiploma', async function (e) {
        e.preventDefault();

        const form = this;
        const formData = new FormData(form);

        showLoading('Actualizando...', 'Guardando cambios del diploma');

        const success = await executeAjax(
            update(API_URL, formData),
            'Diploma actualizado exitosamente',
            () => {
                closeLoading();
                cerrarModal('modalEditar');
                form.reset();
                limpiarValidaciones(form);
                reloadDataTable(tblDiploma);
            }
        );

        if (!success) closeLoading();
    });

    // 8. Eliminar Diploma con Confirmación SweetAlert2
    $(document).on('click', '.btn-table-eliminar', async function () {
        const id = this.value;
        if (!id) return;

        const confirmado = await showConfirm(
            '¿Eliminar diploma?',
            'Esta acción cambiará el estatus del diploma a inactivo.',
            'Sí, eliminar',
            'Cancelar'
        );

        if (confirmado) {
            showLoading('Eliminando...', 'Actualizando estado del registro');
            const success = await executeAjax(
                remove(API_URL, id),
                'Diploma eliminado correctamente',
                () => {
                    closeLoading();
                    reloadDataTable(tblDiploma);
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
