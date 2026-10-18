// matriculaDataTable.js - Lógica de tabla DataTables y acciones para Matrículas
import { createDataTable, reloadDataTable } from '../helpers/dataTable.js';
import { executeAjax } from '../helpers/ajax.js';
import { showConfirm, showLoading, closeLoading, showToast } from '../helpers/sweetalert.js';
import { animateIn } from '../helpers/animations.js';
import { showSkeleton, hideSkeleton } from '../helpers/skeleton.js';

const API_URL = window.matriculaApiUrl || '?url=matricula';

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

    // 3. Cancelar Matrícula con Confirmación SweetAlert2
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
