// pagoDataTable.js - Gestión interactiva de Comprobantes de Pago con DataTables y SweetAlert2
import { createDataTable, reloadDataTable } from '../helpers/dataTable.js';
import { executeAjax } from '../helpers/ajax.js';
import { showConfirm, showLoading, closeLoading, showToast } from '../helpers/sweetalert.js';
import { animateIn } from '../helpers/animations.js';
import { showSkeleton, hideSkeleton } from '../helpers/skeleton.js';

const API_URL = window.pagoApiUrl || '?url=comprobantePago';

$(document).ready(function () {
    // 1. Mostrar skeleton inicial
    showSkeleton('#pagoSkeleton', '#pagoTableContainer');

    // 2. Inicialización de DataTables
    const tblPago = createDataTable('#pagoTable', API_URL, [
        {
            data: 'id_comprobante_pago',
            className: 'text-muted fw-bold'
        },
        {
            data: 'referencia_transaccion',
            render: (data) => `<span class="badge bg-dark border border-secondary text-light px-2 py-1 font-monospace"><i class="fas fa-receipt me-1 text-muted"></i>${data || 'N/A'}</span>`
        },
        {
            data: 'nombre_estudiante',
            render: (data, type, row) => `
                <div class="fw-semibold text-white">
                    <i class="fas fa-user-graduate me-1 text-muted"></i>${data || 'Sin nombre'}
                </div>
                <small class="text-muted">${row.cedula_estudiante ? 'C.I: ' + row.cedula_estudiante : ''}</small>
            `
        },
        {
            data: 'nombre_curso',
            render: (data) => `<span class="text-light">${data || 'N/A'}</span>`
        },
        {
            data: 'nombre_banco',
            render: (data) => `<span class="text-light"><i class="fas fa-university me-1 text-muted"></i>${data || 'N/A'}</span>`
        },
        {
            data: 'monto',
            render: (data) => `<span class="fw-bold" style="color: var(--accent);">$${parseFloat(data || 0).toFixed(2)}</span>`
        },
        {
            data: 'fecha_pago',
            render: (data) => `<span class="text-muted small">${data || 'N/A'}</span>`
        },
        {
            data: 'url_img_comprobante',
            orderable: false,
            searchable: false,
            render: (data) => {
                if (!data) return `<span class="text-muted small">Sin adjunto</span>`;
                return `
                    <button type="button" class="btn btn-sm btn-outline-info btn-ver-comprobante py-0 px-2" data-img="${data}" title="Ver comprobante de pago">
                        <i class="fas fa-image me-1"></i>Ver
                    </button>
                `;
            }
        },
        {
            data: 'estatus_pago',
            render: (data) => {
                let badgeClass = 'bg-secondary';
                if (data === 'Verificado') badgeClass = 'bg-success text-white';
                else if (data === 'Pendiente') badgeClass = 'bg-warning text-dark';
                else if (data === 'Rechazado') badgeClass = 'bg-danger text-white';
                return `<span class="badge ${badgeClass} px-2 py-1">${data || 'N/A'}</span>`;
            }
        },
        {
            data: null,
            orderable: false,
            searchable: false,
            className: 'text-end',
            render: (data, type, row) => {
                if (row.estatus_pago === 'Pendiente') {
                    return `
                        <div class="d-flex gap-1 justify-content-end">
                            <button type="button" value="${row.id_comprobante_pago}" data-estatus="Verificado" class="btn btn-sm btn-success btn-conciliar" title="Verificar pago">
                                <i class="fas fa-check"></i>
                            </button>
                            <button type="button" value="${row.id_comprobante_pago}" data-estatus="Rechazado" class="btn btn-sm btn-danger btn-conciliar" title="Rechazar pago">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    `;
                }
                return `<span class="text-muted small"><i class="fas fa-lock me-1"></i>Procesado</span>`;
            }
        }
    ], {
        initComplete: function () {
            setTimeout(() => {
                hideSkeleton('#pagoSkeleton', '#pagoTableContainer');
                animateIn('#pagoTableContainer', 300);
            }, 400);
        }
    });

    // 3. Modal de previsualización del comprobante con SweetAlert2
    $(document).on('click', '.btn-ver-comprobante', function () {
        const imgUrl = $(this).data('img');
        if (!imgUrl) return;

        Swal.fire({
            title: 'Comprobante de Pago',
            imageUrl: imgUrl,
            imageAlt: 'Imagen del comprobante',
            showCloseButton: true,
            showConfirmButton: false,
            background: 'var(--bg-card, #18181c)',
            color: '#f3f3f5',
            customClass: {
                popup: 'border-luxury shadow-lg'
            }
        });
    });

    // 4. Conciliar pago (Verificar o Rechazar) con SweetAlert2
    $(document).on('click', '.btn-conciliar', async function () {
        const id = this.value;
        const estatus = $(this).data('estatus');
        if (!id || !estatus) return;

        const esVerificar = (estatus === 'Verificado');
        const confirmado = await showConfirm(
            esVerificar ? '¿Verificar comprobante de pago?' : '¿Rechazar comprobante de pago?',
            esVerificar
                ? `¿Confirma que el comprobante #${id} es válido y los fondos están disponibles?`
                : `¿Desea marcar el comprobante #${id} como Rechazado? Esta acción se notificará al estudiante.`,
            esVerificar ? 'Sí, verificar pago' : 'Sí, rechazar comprobante',
            'Volver'
        );

        if (confirmado) {
            showLoading(
                esVerificar ? 'Verificando...' : 'Rechazando...',
                'Actualizando estado del pago y matrícula en el sistema...'
            );

            const formData = new FormData();
            formData.append('conciliar_pago', 'true');
            formData.append('id_comprobante_pago', id);
            formData.append('estatus_pago', estatus);

            const success = await executeAjax(
                $.ajax({
                    url: API_URL,
                    method: 'POST',
                    dataType: 'json',
                    data: formData,
                    processData: false,
                    contentType: false
                }),
                `Comprobante marcado como ${estatus} correctamente`,
                () => {
                    closeLoading();
                    reloadDataTable(tblPago);
                }
            );

            if (!success) closeLoading();
        }
    });
});
