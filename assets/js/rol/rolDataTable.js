// rolDataTable.js - Lógica de tabla DataTables, llamadas AJAX y modales para Gestión de Roles
import { createDataTable, reloadDataTable, createActionsColumn, abrirModal, cerrarModal } from '../helpers/dataTable.js';
import { getById, create, update, remove, executeAjax } from '../helpers/ajax.js';
import { showConfirm, showLoading, closeLoading, showToast, showDeleteConfirm } from '../helpers/sweetalert.js';
import { animateIn } from '../helpers/animations.js';
import { showSkeleton, hideSkeleton, showModalSkeleton, hideModalSkeleton } from '../helpers/skeleton.js';
import { limpiarValidaciones } from '../helpers/validation.js';

const API_URL = '?url=rol';

$(document).ready(function () {
    // 1. Mostrar skeleton antes de inicializar DataTable
    showSkeleton('#rolSkeleton', '#rolTableContainer');

    // 2. Inicializar DataTable
    const tblRol = createDataTable('#rolTable', API_URL, [
        { 
            data: 'id_rol',
            className: 'text-muted fw-bold'
        },
        { 
            data: 'nombre_rol',
            render: (data, type, row) => {
                const nombre = data || 'Sin nombre';
                let icon = 'fa-user-tag';
                let badgeClass = 'tipo-completo';

                if (nombre.toLowerCase().includes('admin')) {
                    icon = 'fa-user-shield';
                    badgeClass = 'tipo-masterclass';
                } else if (nombre.toLowerCase().includes('instructor')) {
                    icon = 'fa-chalkboard-teacher';
                    badgeClass = 'tipo-completo';
                } else if (nombre.toLowerCase().includes('estudiante')) {
                    icon = 'fa-user-graduate';
                    badgeClass = 'tipo-completo';
                }

                return `
                    <div class="fw-semibold text-white d-flex align-items-center gap-2">
                        <i class="fas ${icon}" style="color: var(--accent);"></i>
                        <span>${nombre}</span>
                    </div>
                `;
            }
        },
        { 
            data: 'descripcion_rol',
            render: (data) => `<span class="text-muted" style="font-size: 13px;">${data || 'Sin descripción'}</span>`
        },
        { 
            data: 'total_usuarios',
            render: (data) => {
                const total = parseInt(data) || 0;
                return `<span class="badge-cedula" style="font-size: 12px;"><i class="fas fa-users me-1"></i>${total} usuario(s)</span>`;
            }
        },
        { 
            data: 'estatus_rol',
            render: (data) => {
                const activo = data == 1 || data === '1' || data === true;
                if (activo) {
                    return `<span class="badge-status-dot status-active">Activo</span>`;
                }
                return `<span class="badge-status-dot status-closed">Inactivo</span>`;
            }
        },
        createActionsColumn({
            idField: 'id_rol',
            btnVer: true,
            btnEditar: true,
            btnEliminar: true
        })
    ], {
        statusOptions: ['Activo', 'Inactivo'],
        initComplete: function () {
            setTimeout(() => {
                hideSkeleton('#rolSkeleton', '#rolTableContainer');
                animateIn('#rolTableContainer', 300);
            }, 300);
        }
    });

    // 3. Abrir Modal de Registro
    $(document).on('click', '.btn-registrar-rol', function () {
        const form = document.getElementById('formCrearRol');
        if (form) {
            form.reset();
            limpiarValidaciones(form);
        }
        abrirModal('modalCrear');
    });

    // 4. Ver Detalle del Rol
    $(document).on('click', '.btn-table-ver', async function () {
        const id = this.value;
        if (!id) return;

        showModalSkeleton('#modalDetalle');

        await executeAjax(
            getById(API_URL, id),
            null,
            (response) => {
                closeLoading();
                const r = response.data;
                if (!r) return;

                $('#detail_id_rol').text(r.id_rol || '-');
                $('#detail_nombre_rol').text(r.nombre_rol || '-');
                $('#detail_descripcion_rol').text(r.descripcion_rol || 'Sin descripción');
                $('#detail_total_usuarios').html(`<span class="badge-cedula"><i class="fas fa-users me-1"></i>${r.total_usuarios || 0} usuario(s)</span>`);
                
                const activo = r.estatus_rol == 1 || r.estatus_rol === '1' || r.estatus_rol === true;
                const statusHtml = activo 
                    ? '<span class="badge-status-dot status-active">Activo</span>' 
                    : '<span class="badge-status-dot status-closed">Inactivo</span>';
                $('#detail_estatus_rol').html(statusHtml);

                $('#detail_fecha_creacion').text(r.fecha_creacion || 'No registrada');

                hideModalSkeleton('#modalDetalle');
            }
        );
    });

    // 5. Submit Formulario Crear
    $(document).on('submit', '#formCrearRol', async function (e) {
        e.preventDefault();

        const form = this;
        const formData = new FormData(form);

        showLoading('Registrando...', 'Creando nuevo rol en el sistema');

        const success = await executeAjax(
            create(API_URL, formData),
            'Rol registrado exitosamente',
            () => {
                closeLoading();
                cerrarModal('modalCrear');
                form.reset();
                limpiarValidaciones(form);
                reloadDataTable(tblRol);
            }
        );

        if (!success) closeLoading();
    });

    // 6. Cargar y abrir Modal Editar
    $(document).on('click', '.btn-table-editar', async function () {
        const id = this.value;
        if (!id) return;

        showModalSkeleton('#modalEditar');

        await executeAjax(
            getById(API_URL, id),
            null,
            (response) => {
                closeLoading();
                const r = response.data;
                if (!r) return;

                const form = document.getElementById('formEditarRol');
                if (form) {
                    form.reset();
                    limpiarValidaciones(form);
                }

                $('#edit_id_rol').val(r.id_rol);
                $('#edit_nombre_rol').val(r.nombre_rol || '');
                $('#edit_descripcion_rol').val(r.descripcion_rol || '');
                $('#edit_estatus_rol').val(r.estatus_rol);

                // Si es rol 1 (Administrador principal), deshabilitar desactivación
                if (parseInt(r.id_rol) === 1) {
                    $('#edit_estatus_rol').prop('disabled', true);
                } else {
                    $('#edit_estatus_rol').prop('disabled', false);
                }

                hideModalSkeleton('#modalEditar');
            }
        );
    });

    // 7. Submit Formulario Editar
    $(document).on('submit', '#formEditarRol', async function (e) {
        e.preventDefault();

        const form = this;
        const formData = new FormData(form);

        // Si el select estaba deshabilitado, forzar envío de estatus 1
        if ($('#edit_estatus_rol').is(':disabled')) {
            formData.set('estatus_rol', '1');
        }

        showLoading('Actualizando...', 'Guardando cambios del rol');

        const success = await executeAjax(
            update(API_URL, formData),
            'Rol actualizado exitosamente',
            () => {
                closeLoading();
                cerrarModal('modalEditar');
                form.reset();
                limpiarValidaciones(form);
                reloadDataTable(tblRol);
            }
        );

        if (!success) closeLoading();
    });

    // 8. Eliminar Rol con Confirmación SweetAlert2
    $(document).on('click', '.btn-table-eliminar', async function () {
        const id = this.value;
        if (!id) return;

        const idNum = parseInt(id);
        if ([1, 2, 3].includes(idNum)) {
            showToast('Los roles base del sistema (Administrador, Instructor, Estudiante) están protegidos y no pueden eliminarse.', 'warning');
            return;
        }

        const confirmado = await showDeleteConfirm(
            '¿Eliminar rol?',
            'Esta acción eliminará el rol si no tiene usuarios vinculados.',
            'Sí, eliminar',
            'Cancelar'
        );

        if (confirmado) {
            showLoading('Eliminando...', 'Verificando y eliminando rol');
            const success = await executeAjax(
                remove(API_URL, id),
                'Rol eliminado correctamente',
                () => {
                    closeLoading();
                    reloadDataTable(tblRol);
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
