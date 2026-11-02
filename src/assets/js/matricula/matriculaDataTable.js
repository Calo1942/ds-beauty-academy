// matriculaDataTable.js - Lógica de tabla DataTables y acciones para Matrículas
import { createDataTable, reloadDataTable } from '../helpers/dataTable.js';
import { create, executeAjax } from '../helpers/ajax.js';
import { showConfirm, showLoading, closeLoading, showToast } from '../helpers/sweetalert.js';
import { animateIn } from '../helpers/animations.js';
import { showSkeleton, hideSkeleton } from '../helpers/skeleton.js';
import { limpiarValidaciones } from '../helpers/validation.js';

const API_URL = window.matriculaApiUrl || '?url=matricula';

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

// Cargar lista de estudiantes activos para el select
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
            let options = '<option value="">-- Seleccione una estudiante --</option>';
            response.data
                .filter(est => est.estatus_estudiante == 1 || est.estatus_estudiante === 'Activo')
                .forEach(est => {
                    const id = est.id_estudiante;
                    const nombre = `${est.primer_nombre_estudiante} ${est.primer_apellido_estudiante}`;
                    const cedula = est.cedula_estudiante ? `(C.I: ${est.cedula_estudiante})` : '';
                    const selected = (valorSeleccionado && valorSeleccionado == id) ? 'selected' : '';
                    options += `<option value="${id}" ${selected}>${nombre} ${cedula}</option>`;
                });
            $select.html(options);
            if (valorSeleccionado) $select.val(valorSeleccionado);
        }
    } catch (err) {
        console.error('Error cargando lista de estudiantes:', err);
    }
}

// Cargar oferta académica (instructor_curso) para el select
async function cargarOfertaAcademica(selectSelector, valorSeleccionado = null) {
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
            let options = '<option value="">-- Seleccione una oferta académica --</option>';
            response.data.forEach(item => {
                const id = item.id_instructor_curso;
                const curso = item.nombre_curso || 'Curso sin nombre';
                const instructor = item.nombre_instructor || 'Sin instructor';
                const normal = parseFloat(item.precio_normal || 0);
                const preventa = item.precio_preventa ? parseFloat(item.precio_preventa) : null;
                const precioTxt = preventa ? `$${preventa.toFixed(2)} (Preventa) / $${normal.toFixed(2)}` : `$${normal.toFixed(2)}`;
                const selected = (valorSeleccionado && valorSeleccionado == id) ? 'selected' : '';

                options += `<option value="${id}" 
                                data-precio-normal="${normal}" 
                                data-precio-preventa="${preventa || ''}" 
                                ${selected}>
                                ${curso} - Prof. ${instructor} [${precioTxt}]
                            </option>`;
            });
            $select.html(options);
            if (valorSeleccionado) $select.val(valorSeleccionado);
        }
    } catch (err) {
        console.error('Error cargando oferta académica:', err);
    }
}

$(document).ready(function () {
    // 1. Mostrar skeleton mientras carga la tabla inicial
    showSkeleton('#matriculaSkeleton', '#matriculaTableContainer');

    // 2. Inicializar DataTable
    const tblMatricula = createDataTable('#matriculaTable', API_URL, [
        { 
            data: 'id_matricula',
            className: 'text-muted fw-bold'
        },
        { 
            data: 'nombre_estudiante',
            render: (data) => `<span class="fw-semibold text-white"><i class="fas fa-user-graduate me-2 text-muted"></i>${data || 'Sin nombre'}</span>`
        },
        { 
            data: 'cedula_estudiante',
            render: (data) => `<span class="badge bg-dark border border-secondary text-light px-2 py-1">${data || 'N/A'}</span>`
        },
        { 
            data: 'nombre_curso',
            render: (data) => `<span class="text-light">${data || 'N/A'}</span>`
        },
        { 
            data: 'nombre_instructor',
            render: (data) => `<span class="text-light">${data || 'No asignado'}</span>`
        },
        { 
            data: 'precio_acordado',
            render: (data) => `<span class="text-accent fw-bold" style="color: var(--accent);">$${parseFloat(data || 0).toFixed(2)}</span>`
        },
        { 
            data: 'estatus_pago',
            render: (data) => {
                const isSolvente = (data === 'Solvente');
                return `<span class="badge ${isSolvente ? 'bg-success text-white' : 'bg-warning text-dark'} px-2 py-1">${data || 'Pendiente'}</span>`;
            }
        },
        { 
            data: 'estatus_cupo',
            render: (data) => {
                let badgeClass = 'bg-secondary';
                if (data === 'Confirmado') badgeClass = 'bg-success text-white';
                else if (data === 'Reservado') badgeClass = 'bg-info text-white';
                else if (data === 'Cancelado') badgeClass = 'bg-danger text-white';
                return `<span class="badge ${badgeClass} px-2 py-1">${data || 'N/A'}</span>`;
            }
        },
        {
            data: null,
            orderable: false,
            searchable: false,
            className: 'text-end',
            render: (data, type, row) => {
                if (row.estatus_cupo === 'Cancelado') {
                    return `<span class="text-muted small">Cancelada</span>`;
                }
                return `
                    <button value="${row.id_matricula}" class="btn-action btn-delete btn-cancelar-matricula" title="Cancelar Matrícula y liberar cupo" type="button">
                        <i class="fas fa-times"></i>
                    </button>
                `;
            }
        }
    ], {
        initComplete: function () {
            setTimeout(() => {
                hideSkeleton('#matriculaSkeleton', '#matriculaTableContainer');
                animateIn('#matriculaTableContainer', 300);
            }, 400);
        }
    });

    // 3. Abrir modal Crear Matrícula
    $(document).on('click', '.btn-registrar-matricula, [data-modal="modalCrear"]', function () {
        const form = document.getElementById('formCrearMatricula');
        if (form) {
            form.reset();
            limpiarValidaciones(form);
        }
        cargarEstudiantes('#select_estudiante_matricula');
        cargarOfertaAcademica('#select_instructor_curso_matricula');
        abrirModal('modalCrear');
    });

    // 4. Auto-sugerir precio al seleccionar curso e instructor
    $(document).on('change', '#select_instructor_curso_matricula', function () {
        const $option = $(this).find('option:selected');
        const precioPreventa = $option.data('precio-preventa');
        const precioNormal = $option.data('precio-normal');

        const precioSugerido = (precioPreventa && parseFloat(precioPreventa) > 0)
            ? parseFloat(precioPreventa).toFixed(2)
            : (precioNormal ? parseFloat(precioNormal).toFixed(2) : '');

        if (precioSugerido !== '') {
            $('#precio_acordado_matricula').val(precioSugerido);
        }
    });

    // 5. Submit Formulario Crear Matrícula (Manejo de triggers de aforo y duplicidad)
    $(document).on('submit', '#formCrearMatricula', async function (e) {
        e.preventDefault();

        const form = this;
        const formData = new FormData(form);
        formData.append('guardar', 'true');

        showLoading('Registrando...', 'Validando cupos y registrando matrícula');

        const success = await executeAjax(
            create(API_URL, formData),
            'Matrícula registrada exitosamente',
            () => {
                closeLoading();
                cerrarModal('modalCrear');
                form.reset();
                limpiarValidaciones(form);
                reloadDataTable(tblMatricula);
            }
        );

        if (!success) closeLoading();
    });

    // 6. Cancelar Matrícula con Confirmación SweetAlert2
    $(document).on('click', '.btn-cancelar-matricula', async function () {
        const id = this.value;
        if (!id) return;

        const confirmado = await showConfirm(
            '¿Cancelar matrícula?',
            'Esta acción cancelará la matrícula del estudiante y liberará el cupo en el curso.',
            'Sí, cancelar matrícula',
            'Volver'
        );

        if (confirmado) {
            showLoading('Cancelando...', 'Actualizando estado de la matrícula');
            
            const formData = new FormData();
            formData.append('eliminar', 'true');
            formData.append('id_matricula', id);

            const success = await executeAjax(
                $.ajax({
                    url: API_URL,
                    method: 'POST',
                    dataType: 'json',
                    data: formData,
                    processData: false,
                    contentType: false
                }),
                'Matrícula cancelada y cupo liberado',
                () => {
                    closeLoading();
                    reloadDataTable(tblMatricula);
                }
            );

            if (!success) closeLoading();
        }
    });
});
