// bancoDataTable.js - Lógica de tabla DataTables, llamadas AJAX y modales para Bancos
import { createDataTable, reloadDataTable, createActionsColumn } from '../helpers/dataTable.js';
import { getById, create, update, remove, executeAjax } from '../helpers/ajax.js';
import { showConfirm, showLoading, closeLoading } from '../helpers/sweetalert.js';
import { animateIn } from '../helpers/animations.js';
import { showSkeleton, hideSkeleton } from '../helpers/skeleton.js';
import { limpiarValidaciones } from '../helpers/validation.js';

const API_URL = window.bancoApiUrl || '?url=banco';

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
    showSkeleton('#bancoSkeleton', '#bancoTableContainer');

    // 2. Inicializar DataTable
    const tblBanco = createDataTable('#bancoTable', API_URL, [
        { 
            data: 'id_banco',
            className: 'text-muted fw-bold'
        },
        { 
            data: 'nombre_banco',
            render: (data) => `<span class="fw-semibold text-white"><i class="fas fa-university me-2 text-muted"></i>${data || 'Sin nombre'}</span>`
        },
        createActionsColumn({
            idField: 'id_banco',
            btnVer: true,
            btnEditar: true,
            btnEliminar: true
        })
    ], {
        initComplete: function () {
            setTimeout(() => {
                hideSkeleton('#bancoSkeleton', '#bancoTableContainer');
                animateIn('#bancoTableContainer', 300);
            }, 400);
        }
    });

    // 3. Abrir modal Crear
    $(document).on('click', '.btn-registrar-banco, [data-modal="modalCrear"]', function () {
        const form = document.getElementById('formCrearBanco');
        if (form) {
            form.reset();
            limpiarValidaciones(form);
        }
        abrirModal('modalCrear');
    });

    // 4. Ver Detalle de Banco
    $(document).on('click', '.btn-table-ver', async function () {
        const id = this.value;
        if (!id) return;

        showLoading('Cargando detalle...', 'Obteniendo información del banco');

        await executeAjax(
            getById(API_URL, id),
            null,
            (response) => {
                closeLoading();
                const b = response.data;
                if (!b) return;

                $('#detail_id_banco').text(b.id_banco);
                $('#detail_nombre_banco').text(b.nombre_banco || 'N/A');
                $('#detail_estatus_banco').text(b.estatus_banco == 1 ? 'Activo' : 'Inactivo');

                abrirModal('modalDetalle');
            }
        );
    });

    // 5. Submit Formulario Crear
    $(document).on('submit', '#formCrearBanco', async function (e) {
        e.preventDefault();

        const form = this;
        const formData = new FormData(form);

        showLoading('Registrando...', 'Guardando banco en el sistema');

        const success = await executeAjax(
            create(API_URL, formData),
            'Banco registrado exitosamente',
            () => {
                closeLoading();
                cerrarModal('modalCrear');
                form.reset();
                limpiarValidaciones(form);
                reloadDataTable(tblBanco);
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
                const b = response.data;
                if (!b) return;

                const form = document.getElementById('formEditarBanco');
                if (form) {
                    form.reset();
                    limpiarValidaciones(form);
                }

                $('#edit_id_banco').val(b.id_banco);
                $('#formEditarBanco [name="nombre_banco"]').val(b.nombre_banco || '');

                abrirModal('modalEditar');
            }
        );
    });

    // 7. Submit Formulario Editar
    $(document).on('submit', '#formEditarBanco', async function (e) {
        e.preventDefault();

        const form = this;
        const formData = new FormData(form);

        showLoading('Actualizando...', 'Guardando cambios del banco');

        const success = await executeAjax(
            update(API_URL, formData),
            'Banco actualizado exitosamente',
            () => {
                closeLoading();
                cerrarModal('modalEditar');
                form.reset();
                limpiarValidaciones(form);
                reloadDataTable(tblBanco);
            }
        );

        if (!success) closeLoading();
    });

    // 8. Eliminar Banco con Confirmación SweetAlert2
    $(document).on('click', '.btn-table-eliminar', async function () {
        const id = this.value;
        if (!id) return;

        const confirmado = await showConfirm(
            '¿Eliminar banco?',
            'Esta acción cambiará el estatus del banco a inactivo.',
            'Sí, eliminar',
            'Cancelar'
        );

        if (confirmado) {
            showLoading('Eliminando...', 'Actualizando estado del registro');
            const success = await executeAjax(
                remove(API_URL, id),
                'Banco eliminado correctamente',
                () => {
                    closeLoading();
                    reloadDataTable(tblBanco);
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
