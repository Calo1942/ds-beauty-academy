// usuarioDataTable.js - Lógica de tabla DataTables, llamadas AJAX y modales para Gestión de Usuarios
import { createDataTable, reloadDataTable, createActionsColumn } from '../helpers/dataTable.js';
import { getById, create, update, remove, executeAjax } from '../helpers/ajax.js';
import { showConfirm, showLoading, closeLoading, showToast } from '../helpers/sweetalert.js';
import { animateIn } from '../helpers/animations.js';
import { showSkeleton, hideSkeleton } from '../helpers/skeleton.js';
import { limpiarValidaciones } from '../helpers/validation.js';

const API_URL = '?url=usuario';

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

// Cargar select de roles
async function cargarRoles(selectSelector, valorSeleccionado = null) {
    const $select = $(selectSelector);
    if (!$select.length) return;

    try {
        const response = await $.ajax({
            url: API_URL,
            method: 'POST',
            data: { obtenerRoles: true },
            dataType: 'json'
        });

        if (response && response.status === 'success' && Array.isArray(response.data)) {
            let options = '<option value="">-- Seleccionar Rol --</option>';
            response.data.forEach(r => {
                const selected = (valorSeleccionado && valorSeleccionado == r.id_rol) ? 'selected' : '';
                options += `<option value="${r.id_rol}" data-nombre="${r.nombre_rol}" ${selected}>${r.nombre_rol}</option>`;
            });
            $select.html(options);
            if (valorSeleccionado) $select.val(valorSeleccionado);
        }
    } catch (err) {
        console.error('Error cargando roles:', err);
    }
}

// Cargar select de instructores
async function cargarInstructores(selectSelector, valorSeleccionado = null) {
    const $select = $(selectSelector);
    if (!$select.length) return;

    try {
        const response = await $.ajax({
            url: '?url=instructor',
            method: 'POST',
            data: { buscarTodos: true },
            dataType: 'json'
        });

        if (response && response.status === 'success' && Array.isArray(response.data)) {
            let options = '<option value="">-- Seleccionar Instructor (Opcional) --</option>';
            response.data.forEach(i => {
                const id = i.id_instructor;
                const nombre = `${i.primer_nombre_instructor || ''} ${i.primer_apellido_instructor || ''}`.trim();
                const cedula = i.cedula_instructor ? `(C.I: ${i.cedula_instructor})` : '';
                const selected = (valorSeleccionado && valorSeleccionado == id) ? 'selected' : '';
                options += `<option value="${id}" ${selected}>${nombre} ${cedula}</option>`;
            });
            $select.html(options);
            if (valorSeleccionado) $select.val(valorSeleccionado);
        }
    } catch (err) {
        console.error('Error cargando instructores:', err);
    }
}

// Cargar select de estudiantes
async function cargarEstudiantes(selectSelector, valorSeleccionado = null) {
    const $select = $(selectSelector);
    if (!$select.length) return;

    try {
        const response = await $.ajax({
            url: '?url=estudiante',
            method: 'POST',
            data: { buscarTodos: true },
            dataType: 'json'
        });

        if (response && response.status === 'success' && Array.isArray(response.data)) {
            let options = '<option value="">-- Seleccionar Estudiante (Opcional) --</option>';
            response.data.forEach(e => {
                const id = e.id_estudiante;
                const nombre = `${e.primer_nombre_estudiante || ''} ${e.primer_apellido_estudiante || ''}`.trim();
                const cedula = e.cedula_estudiante ? `(C.I: ${e.cedula_estudiante})` : '';
                const selected = (valorSeleccionado && valorSeleccionado == id) ? 'selected' : '';
                options += `<option value="${id}" ${selected}>${nombre} ${cedula}</option>`;
            });
            $select.html(options);
            if (valorSeleccionado) $select.val(valorSeleccionado);
        }
    } catch (err) {
        console.error('Error cargando estudiantes:', err);
    }
}

// Toggle dinámico de vinculación según el rol seleccionado
function toggleCamposRol(prefix, nombreRol) {
    const rol = (nombreRol || '').toLowerCase();
    const $groupInstructor = $(`#groupInstructor${prefix}`);
    const $groupEstudiante = $(`#groupEstudiante${prefix}`);

    if (rol.includes('instructor')) {
        $groupInstructor.removeClass('d-none');
        $groupEstudiante.addClass('d-none').find('select').val('');
    } else if (rol.includes('estudiante')) {
        $groupEstudiante.removeClass('d-none');
        $groupInstructor.addClass('d-none').find('select').val('');
    } else {
        $groupInstructor.addClass('d-none').find('select').val('');
        $groupEstudiante.addClass('d-none').find('select').val('');
    }
}

$(document).ready(function () {
    // 1. Mostrar skeleton mientras carga la tabla inicial
    showSkeleton('#usuarioSkeleton', '#usuarioTableContainer');

    // 2. Inicializar DataTable
    const tblUsuario = createDataTable('#usuarioTable', API_URL, [
        { 
            data: 'id_usuario',
            className: 'text-muted fw-bold'
        },
        { 
            data: 'nombre_persona',
            render: (data, type, row) => {
                const nombre = data || 'Administrador';
                let icon = 'fa-user-shield';
                if (row.id_instructor) icon = 'fa-chalkboard-teacher';
                else if (row.id_estudiante) icon = 'fa-user-graduate';

                return `
                    <div class="fw-semibold text-white">
                        <i class="fas ${icon} me-2 text-accent" style="color: var(--accent);"></i>${nombre}
                    </div>
                `;
            }
        },
        { 
            data: 'email_usuario',
            render: (data) => `<span class="text-white">${data || 'Sin correo'}</span>`
        },
        { 
            data: 'nombre_rol',
            render: (data) => {
                const rol = data || 'Usuario';
                let badgeClass = 'tipo-completo';
                if (rol.toLowerCase().includes('admin')) badgeClass = 'tipo-masterclass';
                else if (rol.toLowerCase().includes('estudiante')) badgeClass = 'tipo-completo';
                return `<span class="badge-tipo-curso ${badgeClass}">${rol}</span>`;
            }
        },
        { 
            data: 'estatus_usuario',
            render: (data) => {
                const activo = data == 1 || data === '1' || data === true;
                if (activo) {
                    return `<span class="badge-status-dot status-active">Activo</span>`;
                }
                return `<span class="badge-status-dot status-closed">Inactivo</span>`;
            }
        },
        createActionsColumn({
            idField: 'id_usuario',
            btnVer: true,
            btnEditar: true,
            btnEliminar: true
        })
    ], {
        initComplete: function () {
            setTimeout(() => {
                hideSkeleton('#usuarioSkeleton', '#usuarioTableContainer');
                animateIn('#usuarioTableContainer', 300);
            }, 300);
        }
    });

    // Eventos de cambio de rol para mostrar/ocultar vinculaciones
    $('#selectRolCrear').on('change', function () {
        const nombreRol = $(this).find('option:selected').data('nombre') || '';
        toggleCamposRol('Crear', nombreRol);
    });

    $('#selectRolEditar').on('change', function () {
        const nombreRol = $(this).find('option:selected').data('nombre') || '';
        toggleCamposRol('Editar', nombreRol);
    });

    // 3. Abrir modal Crear
    $(document).on('click', '.btn-registrar-usuario, [data-modal="modalCrear"]', async function () {
        const form = document.getElementById('formCrearUsuario');
        if (form) {
            form.reset();
            limpiarValidaciones(form);
        }
        await cargarRoles('#selectRolCrear');
        await cargarInstructores('#selectInstructorCrear');
        await cargarEstudiantes('#selectEstudianteCrear');
        toggleCamposRol('Crear', '');
        abrirModal('modalCrear');
    });

    // 4. Ver Detalle de Usuario
    $(document).on('click', '.btn-table-ver', async function () {
        const id = this.value;
        if (!id) return;

        showLoading('Cargando detalle...', 'Obteniendo información del usuario');

        await executeAjax(
            getById(API_URL, id),
            null,
            (response) => {
                closeLoading();
                const u = response.data;
                if (!u) return;

                $('#detail_id_usuario').text(u.id_usuario);
                $('#detail_nombre_persona').text(u.nombre_persona || 'Administrador');
                $('#detail_email_usuario').text(u.email_usuario || 'N/A');
                $('#detail_nombre_rol').text(u.nombre_rol || 'N/A');
                $('#detail_estatus_usuario').html(
                    u.estatus_usuario == 1 
                        ? '<span class="badge-status-dot status-active">Activo</span>' 
                        : '<span class="badge-status-dot status-closed">Inactivo</span>'
                );
                $('#detail_fecha_creacion').text(u.fecha_creacion || 'N/A');

                abrirModal('modalDetalle');
            }
        );
    });

    // 5. Submit Formulario Crear
    $(document).on('submit', '#formCrearUsuario', async function (e) {
        e.preventDefault();

        const form = this;
        const formData = new FormData(form);

        showLoading('Registrando...', 'Creando cuenta de usuario');

        const success = await executeAjax(
            create(API_URL, formData),
            'Usuario registrado exitosamente',
            () => {
                closeLoading();
                cerrarModal('modalCrear');
                form.reset();
                limpiarValidaciones(form);
                reloadDataTable(tblUsuario);
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
                const u = response.data;
                if (!u) return;

                const form = document.getElementById('formEditarUsuario');
                if (form) {
                    form.reset();
                    limpiarValidaciones(form);
                }

                $('#edit_id_usuario').val(u.id_usuario);
                $('#edit_email_usuario').val(u.email_usuario || '');
                $('#edit_password_usuario').val('');
                $('#edit_estatus_usuario').val(u.estatus_usuario);

                await cargarRoles('#selectRolEditar', u.id_rol);
                await cargarInstructores('#selectInstructorEditar', u.id_instructor);
                await cargarEstudiantes('#selectEstudianteEditar', u.id_estudiante);

                toggleCamposRol('Editar', u.nombre_rol);

                abrirModal('modalEditar');
            }
        );
    });

    // 7. Submit Formulario Editar
    $(document).on('submit', '#formEditarUsuario', async function (e) {
        e.preventDefault();

        const form = this;
        const formData = new FormData(form);

        showLoading('Actualizando...', 'Guardando cambios del usuario');

        const success = await executeAjax(
            update(API_URL, formData),
            'Usuario actualizado exitosamente',
            () => {
                closeLoading();
                cerrarModal('modalEditar');
                form.reset();
                limpiarValidaciones(form);
                reloadDataTable(tblUsuario);
            }
        );

        if (!success) closeLoading();
    });

    // 8. Desactivar Usuario con Confirmación SweetAlert2
    $(document).on('click', '.btn-table-eliminar', async function () {
        const id = this.value;
        if (!id) return;

        const confirmado = await showConfirm(
            '¿Desactivar usuario?',
            'Esta acción suspenderá el acceso del usuario al sistema.',
            'Sí, desactivar',
            'Cancelar'
        );

        if (confirmado) {
            showLoading('Desactivando...', 'Actualizando estado de la cuenta');
            const success = await executeAjax(
                remove(API_URL, id),
                'Usuario desactivado correctamente',
                () => {
                    closeLoading();
                    reloadDataTable(tblUsuario);
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

