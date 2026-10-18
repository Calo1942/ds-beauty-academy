// estudianteDataTable.js - Lógica de tabla DataTables, llamadas AJAX y modales para Estudiantes
import { createDataTable, reloadDataTable, createActionsColumn } from '../helpers/dataTable.js';
import { getById, create, update, remove, executeAjax } from '../helpers/ajax.js';
import { showConfirm, showLoading, closeLoading, showToast } from '../helpers/sweetalert.js';
import { animateIn } from '../helpers/animations.js';
import { showSkeleton, hideSkeleton } from '../helpers/skeleton.js';
import { limpiarValidaciones } from '../helpers/validation.js';

const API_URL = window.estudianteApiUrl || '?url=estudiante';

// Helpers para abrir y cerrar modales del sistema
export function abrirModal(id) {
    const modal = document.getElementById(id);
    if (modal) modal.classList.add('show');
}

export function cerrarModal(id) {
    const modal = document.getElementById(id);
    if (modal) modal.classList.remove('show');
}

// Hacer funciones de modal globales por compatibilidad con atributos HTML onclick
window.abrirModal = abrirModal;
window.cerrarModal = cerrarModal;

$(document).ready(function () {
    // 1. Mostrar skeleton mientras carga la tabla inicial
    showSkeleton('#estudianteSkeleton', '#estudianteTableContainer');

    // 2. Inicializar DataTable
    const tblEstudiante = createDataTable('#estudianteTable', API_URL, [
        { 
            data: 'id_estudiante',
            className: 'text-muted fw-bold'
        },
        { 
            data: null,
            render: (data) => {
                const pNombre = data.primer_nombre_estudiante || '';
                const sNombre = data.segundo_nombre_estudiante ? ` ${data.segundo_nombre_estudiante}` : '';
                const pApellido = data.primer_apellido_estudiante ? ` ${data.primer_apellido_estudiante}` : '';
                const sApellido = data.segundo_apellido_estudiante ? ` ${data.segundo_apellido_estudiante}` : '';
                const nombreCompleto = `${pNombre}${sNombre}${pApellido}${sApellido}`.trim();
                
                return `<div class="fw-semibold text-white text-capitalize">${nombreCompleto || 'Sin nombre'}</div>`;
            }
        },
        { 
            data: 'cedula_estudiante',
            render: (data) => `<span class="badge bg-dark border border-secondary text-light px-2 py-1">${data || 'N/A'}</span>`
        },
        { 
            data: 'correo_estudiante',
            render: (data) => `<span class="text-light">${data || 'N/A'}</span>`
        },
        { 
            data: 'usuario_instagram_estudiante',
            render: (data) => data ? `<span class="text-accent" style="color: var(--accent);"><i class="fab fa-instagram me-1"></i>${data}</span>` : '<span class="text-muted">N/A</span>'
        },
        createActionsColumn({
            idField: 'id_estudiante',
            btnVer: true,
            btnEditar: true,
            btnEliminar: true
        })
    ], {
        initComplete: function () {
            setTimeout(() => {
                hideSkeleton('#estudianteSkeleton', '#estudianteTableContainer');
                animateIn('#estudianteTableContainer', 300);
            }, 400);
        }
    });

    // 3. Abrir modal Crear
    $(document).on('click', '.btn-registrar-estudiante, [data-modal="modalCrear"]', function () {
        const form = document.getElementById('formCrearEstudiante');
        if (form) {
            form.reset();
            limpiarValidaciones(form);
        }
        abrirModal('modalCrear');
    });

    // 4. Ver Detalle de Estudiante
    $(document).on('click', '.btn-table-ver', async function () {
        const id = this.value;
        if (!id) return;

        showLoading('Cargando detalle...', 'Obteniendo información del estudiante');

        await executeAjax(
            getById(API_URL, id),
            null,
            (response) => {
                closeLoading();
                const est = response.data;
                if (!est) return;

                $('#detail_id_estudiante').text(est.id_estudiante);
                $('#detail_id_estudiante_badge').text(est.id_estudiante);
                
                const pNombre = est.primer_nombre_estudiante || '';
                const sNombre = est.segundo_nombre_estudiante ? ` ${est.segundo_nombre_estudiante}` : '';
                const pApellido = est.primer_apellido_estudiante ? ` ${est.primer_apellido_estudiante}` : '';
                const sApellido = est.segundo_apellido_estudiante ? ` ${est.segundo_apellido_estudiante}` : '';
                const nombreCompleto = `${pNombre}${sNombre}${pApellido}${sApellido}`.trim();
                
                $('#detail_nombre_completo_header').text(nombreCompleto.toLowerCase());
                $('#detail_cedula').text(est.cedula_estudiante || 'N/A');
                $('#detail_correo').text(est.correo_estudiante || 'N/A');
                $('#detail_telefono').text(est.telefono_principal_estudiante || 'N/A');
                $('#detail_telefono_alternativo').text(est.telefono_alternativo_estudiante || 'N/A');
                $('#detail_instagram').text(est.usuario_instagram_estudiante || 'N/A');

                abrirModal('modalDetalle');
            }
        );
    });

    // 5. Submit Formulario Crear
    $(document).on('submit', '#formCrearEstudiante', async function (e) {
        e.preventDefault();

        const form = this;
        const formData = new FormData(form);

        showLoading('Registrando...', 'Guardando estudiante en el sistema');

        const success = await executeAjax(
            create(API_URL, formData),
            'Estudiante registrado exitosamente',
            () => {
                closeLoading();
                cerrarModal('modalCrear');
                form.reset();
                limpiarValidaciones(form);
                reloadDataTable(tblEstudiante);
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
                const est = response.data;
                if (!est) return;

                const form = document.getElementById('formEditarEstudiante');
                if (form) {
                    form.reset();
                    limpiarValidaciones(form);
                }

                $('#edit_id_estudiante').val(est.id_estudiante);

                const fields = [
                    'primer_nombre_estudiante', 'segundo_nombre_estudiante',
                    'primer_apellido_estudiante', 'segundo_apellido_estudiante',
                    'cedula_estudiante', 'correo_estudiante',
                    'telefono_principal_estudiante', 'telefono_alternativo_estudiante',
                    'usuario_instagram_estudiante'
                ];

                fields.forEach(field => {
                    $(`#formEditarEstudiante [name="${field}"]`).val(est[field] || '');
                });

                abrirModal('modalEditar');
            }
        );
    });

    // 7. Submit Formulario Editar
    $(document).on('submit', '#formEditarEstudiante', async function (e) {
        e.preventDefault();

        const form = this;
        const formData = new FormData(form);

        showLoading('Actualizando...', 'Guardando cambios del estudiante');

        const success = await executeAjax(
            update(API_URL, formData),
            'Estudiante actualizado exitosamente',
            () => {
                closeLoading();
                cerrarModal('modalEditar');
                form.reset();
                limpiarValidaciones(form);
                reloadDataTable(tblEstudiante);
            }
        );

        if (!success) closeLoading();
    });

    // 8. Eliminar Estudiante con Confirmación SweetAlert2
    $(document).on('click', '.btn-table-eliminar', async function () {
        const id = this.value;
        if (!id) return;

        const confirmado = await showConfirm(
            '¿Eliminar estudiante?',
            'Esta acción cambiará el estatus del estudiante a inactivo.',
            'Sí, eliminar',
            'Cancelar'
        );

        if (confirmado) {
            showLoading('Eliminando...', 'Actualizando estado del registro');
            const success = await executeAjax(
                remove(API_URL, id),
                'Estudiante eliminado correctamente',
                () => {
                    closeLoading();
                    reloadDataTable(tblEstudiante);
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
