// pagoDataTable.js - Gestión interactiva de Comprobantes de Pago con DataTables y SweetAlert2
import { createDataTable, reloadDataTable } from '../helpers/dataTable.js';
import { getById, create, executeAjax } from '../helpers/ajax.js';
import { showConfirm, showLoading, closeLoading, showToast } from '../helpers/sweetalert.js';
import { animateIn } from '../helpers/animations.js';
import { showSkeleton, hideSkeleton } from '../helpers/skeleton.js';
import { limpiarValidaciones } from '../helpers/validation.js';

const API_URL = window.pagoApiUrl || '?url=pago';

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

// Cargar matrículas disponibles para el registro de pago
async function cargarMatriculas(selectSelector, valorSeleccionado = null) {
    const $select = $(selectSelector);
    if (!$select.length) return;

    try {
        const response = await $.ajax({
            url: '?url=matricula',
            method: 'POST',
            data: { buscarTodos: true },
            dataType: 'json'
        });

        if (response && response.status === 'success' && Array.isArray(response.data)) {
            let options = '<option value="">-- Seleccione una matrícula --</option>';
            response.data
                .filter(m => m.estatus_cupo !== 'Cancelado')
                .forEach(m => {
                    const id = m.id_matricula;
                    const nombre = m.nombre_estudiante || 'Sin nombre';
                    const cedula = m.cedula_estudiante ? `(C.I: ${m.cedula_estudiante})` : '';
                    const curso = m.nombre_curso || 'Curso';
                    const precio = parseFloat(m.precio_acordado || 0).toFixed(2);
                    const estatus = m.estatus_pago || 'Pendiente';
                    const selected = (valorSeleccionado && valorSeleccionado == id) ? 'selected' : '';

                    options += `<option value="${id}" data-precio="${precio}" ${selected}>
                        #${id} - ${nombre} ${cedula} | ${curso} [$${precio} - ${estatus}]
                    </option>`;
                });
            $select.html(options);
            if (valorSeleccionado) $select.val(valorSeleccionado);
        }
    } catch (err) {
        console.error('Error cargando lista de matrículas:', err);
    }
}

// Cargar bancos receptores activos
async function cargarBancos(selectSelector, valorSeleccionado = null) {
    const $select = $(selectSelector);
    if (!$select.length) return;

    try {
        const response = await $.ajax({
            url: '?url=banco',
            method: 'POST',
            data: { buscarTodos: true },
            dataType: 'json'
        });

        if (response && response.status === 'success' && Array.isArray(response.data)) {
            let options = '<option value="">-- Seleccione un banco --</option>';
            response.data
                .filter(b => b.estatus_banco == 1 || b.estatus_banco === '1' || b.estatus_banco === true)
                .forEach(b => {
                    const id = b.id_banco;
                    const nombre = b.nombre_banco;
                    const selected = (valorSeleccionado && valorSeleccionado == id) ? 'selected' : '';
                    options += `<option value="${id}" ${selected}>${nombre}</option>`;
                });
            $select.html(options);
            if (valorSeleccionado) $select.val(valorSeleccionado);
        }
    } catch (err) {
        console.error('Error cargando lista de bancos:', err);
    }
}

// Helper para formatear badge de estatus de pago
function renderBadgeEstatusPago(estatus) {
    let badgeClass = 'bg-secondary';
    if (estatus === 'Verificado') badgeClass = 'bg-success text-white';
    else if (estatus === 'Pendiente') badgeClass = 'bg-warning text-dark';
    else if (estatus === 'Rechazado') badgeClass = 'bg-danger text-white';
    return `<span class="badge ${badgeClass} px-2 py-1">${estatus || 'N/A'}</span>`;
}

// Función centralizada para conciliar pago (Verificar o Rechazar)
async function procesarConciliacionPago(id, estatus, tblPago) {
    if (!id || !estatus) return;

    const esVerificar = (estatus === 'Verificado');
    const mensajeConfirm = esVerificar
        ? `¿Confirma que el comprobante #${id} es legítimo y los fondos están disponibles en cuenta? Esta acción confirmará el cupo y marcará la matrícula como Solvente.`
        : `¿Desea marcar el comprobante #${id} como Rechazado? Si no existen otros pagos verificados, la matrícula pasará a estado Deudora.`;

    const confirmado = await showConfirm(
        esVerificar ? '¿Verificar comprobante de pago?' : '¿Rechazar comprobante de pago?',
        mensajeConfirm,
        esVerificar ? 'Sí, verificar y aprobar' : 'Sí, rechazar pago',
        'Volver'
    );

    if (confirmado) {
        showLoading(
            esVerificar ? 'Verificando pago...' : 'Rechazando pago...',
            'Actualizando estado del pago y sincronizando la matrícula en el sistema...'
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
            `Comprobante #${id} marcado como ${estatus} correctamente`,
            () => {
                closeLoading();
                cerrarModal('modalDetalle');
                reloadDataTable(tblPago);
            }
        );

        if (!success) closeLoading();
    }
}

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
            render: (data) => `<span class="text-white fw-medium small">${data || 'N/A'}</span>`
        },
        {
            data: 'url_img_comprobante',
            orderable: false,
            searchable: false,
            render: (data) => {
                if (!data) return `<span class="text-muted small">Sin adjunto</span>`;
                const isPdf = data.toLowerCase().endsWith('.pdf');
                return `
                    <button type="button" class="btn btn-sm btn-outline-info btn-ver-comprobante py-0 px-2" data-img="${data}" title="Ver comprobante de pago">
                        <i class="fas ${isPdf ? 'fa-file-pdf' : 'fa-image'} me-1"></i>Ver
                    </button>
                `;
            }
        },
        {
            data: 'estatus_pago',
            render: (data) => renderBadgeEstatusPago(data)
        },
        {
            data: null,
            orderable: false,
            searchable: false,
            className: 'text-end',
            render: (data, type, row) => {
                const id = row.id_comprobante_pago;
                let buttons = `
                    <button type="button" value="${id}" class="btn-action btn-view btn-table-ver" title="Ver detalle del pago">
                        <i class="fas fa-eye"></i>
                    </button>
                `;

                if (row.estatus_pago === 'Pendiente') {
                    buttons += `
                        <button type="button" value="${id}" data-estatus="Verificado" class="btn-action btn-success btn-conciliar" title="Verificar pago">
                            <i class="fas fa-check"></i>
                        </button>
                        <button type="button" value="${id}" data-estatus="Rechazado" class="btn-action btn-delete btn-conciliar" title="Rechazar pago">
                            <i class="fas fa-times"></i>
                        </button>
                    `;
                } else if (row.estatus_pago === 'Rechazado') {
                    buttons += `
                        <button type="button" value="${id}" data-estatus="Verificado" class="btn-action btn-success btn-conciliar" title="Reconsiderar y verificar pago">
                            <i class="fas fa-check"></i>
                        </button>
                    `;
                } else if (row.estatus_pago === 'Verificado') {
                    buttons += `
                        <button type="button" value="${id}" data-estatus="Rechazado" class="btn-action btn-delete btn-conciliar" title="Anular o rechazar pago">
                            <i class="fas fa-undo"></i>
                        </button>
                    `;
                }

                return `<div class="d-flex gap-1 justify-content-end align-items-center">${buttons}</div>`;
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

    // 3. Abrir Modal Crear Pago Manual
    $(document).on('click', '.btn-registrar-pago, [data-modal="modalCrear"]', function () {
        const form = document.getElementById('formCrearPago');
        if (form) {
            form.reset();
            limpiarValidaciones(form);
        }
        // Asignar fecha de hoy
        const hoy = new Date().toISOString().split('T')[0];
        $('#fecha_pago').val(hoy);

        cargarMatriculas('#select_matricula_pago');
        cargarBancos('#select_banco_pago');
        abrirModal('modalCrear');
    });

    // 4. Auto-completar monto acordado al elegir la matrícula
    $(document).on('change', '#select_matricula_pago', function () {
        const precio = $(this).find('option:selected').data('precio');
        if (precio !== undefined && precio !== '') {
            $('#monto_pago').val(precio);
        }
    });

    // 5. Submit Formulario Crear Pago Manual
    $(document).on('submit', '#formCrearPago', async function (e) {
        e.preventDefault();

        const form = this;
        const formData = new FormData(form);
        formData.append('guardar', 'true');

        showLoading('Registrando pago...', 'Guardando comprobante en el sistema');

        const success = await executeAjax(
            $.ajax({
                url: API_URL,
                method: 'POST',
                data: formData,
                dataType: 'json',
                processData: false,
                contentType: false
            }),
            'Pago registrado exitosamente',
            () => {
                closeLoading();
                cerrarModal('modalCrear');
                form.reset();
                limpiarValidaciones(form);
                reloadDataTable(tblPago);
            }
        );

        if (!success) closeLoading();
    });

    // 6. Modal de Detalle de Pago
    $(document).on('click', '.btn-table-ver', async function () {
        const id = this.value;
        if (!id) return;

        showLoading('Cargando detalle...', 'Obteniendo información del comprobante');

        await executeAjax(
            getById(API_URL, id),
            null,
            (response) => {
                closeLoading();
                const pago = response.data;
                if (!pago) return;

                $('#detail_id_comprobante').text(`#${pago.id_comprobante_pago}`);
                $('#detail_referencia').text(pago.referencia_transaccion || 'N/A');
                $('#detail_banco').text(pago.nombre_banco || 'N/A');
                $('#detail_monto').text(`$${parseFloat(pago.monto || 0).toFixed(2)} USD`);
                $('#detail_fecha_pago').text(pago.fecha_pago || 'N/A');
                $('#detail_estatus_pago').html(renderBadgeEstatusPago(pago.estatus_pago));

                $('#detail_nombre_estudiante').text(pago.nombre_estudiante || 'Sin nombre');
                $('#detail_cedula_estudiante').text(pago.cedula_estudiante || 'N/A');
                $('#detail_nombre_curso').text(pago.nombre_curso || 'N/A');
                $('#detail_precio_acordado').text(`$${parseFloat(pago.precio_acordado || 0).toFixed(2)} USD`);

                const isSolvente = (pago.estatus_matricula === 'Solvente');
                $('#detail_estatus_matricula').html(`
                    <span class="badge ${isSolvente ? 'bg-success text-white' : 'bg-warning text-dark'} px-2 py-1">
                        ${pago.estatus_matricula || 'Deudora'}
                    </span>
                `);

                const cupo = pago.estatus_cupo_matricula || 'N/A';
                let cupoBadge = 'bg-secondary';
                if (cupo === 'Confirmado') cupoBadge = 'bg-success text-white';
                else if (cupo === 'Reservado') cupoBadge = 'bg-info text-white';
                else if (cupo === 'Cancelado') cupoBadge = 'bg-danger text-white';
                $('#detail_estatus_cupo').html(`<span class="badge ${cupoBadge} px-2 py-1">${cupo}</span>`);

                // Vista previa del comprobante adjunto
                const adjuntoContainer = $('#detail_comprobante_adjunto');
                if (pago.url_img_comprobante) {
                    const url = pago.url_img_comprobante;
                    const isPdf = url.toLowerCase().endsWith('.pdf');

                    if (isPdf) {
                        adjuntoContainer.html(`
                            <div class="py-2">
                                <i class="fas fa-file-pdf text-danger fa-3x mb-2"></i>
                                <div class="text-white small mb-2 font-monospace">${url}</div>
                                <a href="${url}" target="_blank" class="btn btn-sm btn-primary">
                                    <i class="fas fa-external-link-alt me-1"></i>Abrir Documento PDF
                                </a>
                            </div>
                        `);
                    } else {
                        adjuntoContainer.html(`
                            <div class="py-2">
                                <img src="${url}" alt="Comprobante" class="img-fluid rounded border border-secondary mb-2" style="max-height: 220px; object-fit: contain; cursor: pointer;" onclick="window.open('${url}', '_blank')">
                                <div>
                                    <a href="${url}" target="_blank" class="btn btn-sm btn-outline-info">
                                        <i class="fas fa-expand me-1"></i>Ver en tamaño completo
                                    </a>
                                </div>
                            </div>
                        `);
                    }
                } else {
                    adjuntoContainer.html(`<span class="text-muted small"><i class="fas fa-info-circle me-1"></i>No se adjuntó comprobante digital para esta operación.</span>`);
                }

                // Botones rápidos de conciliación en el footer del detalle
                const actionContainer = $('#detail_action_buttons');
                actionContainer.empty();

                if (pago.estatus_pago === 'Pendiente') {
                    actionContainer.html(`
                        <button type="button" value="${pago.id_comprobante_pago}" data-estatus="Verificado" class="btn btn-success btn-sm btn-conciliar">
                            <i class="fas fa-check me-1"></i>Aprobar Pago
                        </button>
                        <button type="button" value="${pago.id_comprobante_pago}" data-estatus="Rechazado" class="btn btn-danger btn-sm btn-conciliar">
                            <i class="fas fa-times me-1"></i>Rechazar Pago
                        </button>
                    `);
                } else if (pago.estatus_pago === 'Rechazado') {
                    actionContainer.html(`
                        <button type="button" value="${pago.id_comprobante_pago}" data-estatus="Verificado" class="btn btn-success btn-sm btn-conciliar">
                            <i class="fas fa-check me-1"></i>Reconsiderar y Aprobar
                        </button>
                    `);
                } else if (pago.estatus_pago === 'Verificado') {
                    actionContainer.html(`
                        <button type="button" value="${pago.id_comprobante_pago}" data-estatus="Rechazado" class="btn btn-outline-danger btn-sm btn-conciliar">
                            <i class="fas fa-undo me-1"></i>Anular / Rechazar Pago
                        </button>
                    `);
                }

                abrirModal('modalDetalle');
            }
        );
    });

    // 7. Modal de previsualización rápida del comprobante con SweetAlert2
    $(document).on('click', '.btn-ver-comprobante', function () {
        const imgUrl = $(this).data('img');
        if (!imgUrl) return;

        if (imgUrl.toLowerCase().endsWith('.pdf')) {
            Swal.fire({
                title: 'Comprobante en PDF',
                html: `
                    <div class="mb-3 text-muted">El comprobante es un documento PDF.</div>
                    <a href="${imgUrl}" target="_blank" class="btn btn-primary">
                        <i class="fas fa-external-link-alt me-2"></i>Abrir Documento PDF
                    </a>
                `,
                showCloseButton: true,
                showConfirmButton: false,
                background: 'var(--bg-card, #18181c)',
                color: '#f3f3f5'
            });
            return;
        }

        Swal.fire({
            title: 'Comprobante de Pago',
            imageUrl: imgUrl,
            imageAlt: 'Imagen del comprobante',
            html: `
                <div class="mt-2">
                    <a href="${imgUrl}" target="_blank" class="btn btn-sm btn-outline-light">
                        <i class="fas fa-expand me-1"></i>Ver en tamaño completo
                    </a>
                </div>
            `,
            showCloseButton: true,
            showConfirmButton: false,
            background: 'var(--bg-card, #18181c)',
            color: '#f3f3f5',
            customClass: {
                popup: 'border-luxury shadow-lg'
            }
        });
    });

    // 8. Conciliar pago (Verificar o Rechazar) con SweetAlert2
    $(document).on('click', '.btn-conciliar', async function () {
        const id = this.value;
        const estatus = $(this).data('estatus');
        await procesarConciliacionPago(id, estatus, tblPago);
    });
});
