// certificadoDataTable.js - Gestión interactiva de Certificados Emitidos con DataTables y SweetAlert2
import { createDataTable, reloadDataTable } from '../helpers/dataTable.js';
import { executeAjax } from '../helpers/ajax.js';
import { showConfirm, showLoading, closeLoading, showToast } from '../helpers/sweetalert.js';
import { animateIn } from '../helpers/animations.js';
import { showSkeleton, hideSkeleton } from '../helpers/skeleton.js';

const API_URL = window.certificadoApiUrl || '?url=certificado';

$(document).ready(function () {
    // 1. Mostrar skeleton mientras carga la información
    showSkeleton('#certificadoSkeleton', '#certificadoTableContainer');

    // 2. Inicializar DataTable
    const tblCertificado = createDataTable('#certificadoTable', API_URL, [
        {
            data: 'id_certificado',
            className: 'text-muted fw-bold'
        },
        {
            data: 'titulo_certificado',
            render: (data, type, row) => `
                <div class="fw-semibold text-white">
                    <i class="fas fa-award me-1" style="color: var(--accent);"></i>${data || 'Sin título'}
                </div>
                ${row.descripcion_certificado ? `<small class="text-muted text-truncate d-inline-block" style="max-width: 260px;">${row.descripcion_certificado}</small>` : ''}
            `
        },
        {
            data: 'nombre_estudiante',
            render: (data) => `<span class="text-white fw-medium"><i class="fas fa-user-graduate me-1 text-muted"></i>${data || 'N/A'}</span>`
        },
        {
            data: 'nombre_curso',
            render: (data) => `<span class="text-light">${data || 'N/A'}</span>`
        },
        {
            data: 'id_matricula',
            render: (data) => `<span class="badge bg-dark border border-secondary text-light px-2 py-1 font-monospace">Matrícula #${data || 'N/A'}</span>`
        },
        {
            data: 'categoria_certificado',
            render: (data) => `<span class="badge bg-secondary px-2 py-1">${data || 'General'}</span>`
        },
        {
            data: 'estatus_certificado',
            render: (data) => {
                let badgeClass = 'bg-secondary';
                if (data === 'Emitido') badgeClass = 'bg-success text-white';
                else if (data === 'Pendiente de Emisión') badgeClass = 'bg-warning text-dark';
                else if (data === 'Anulado') badgeClass = 'bg-danger text-white';
                return `<span class="badge ${badgeClass} px-2 py-1">${data || 'N/A'}</span>`;
            }
        },
        {
            data: null,
            orderable: false,
            searchable: false,
            className: 'text-end',
            render: (data, type, row) => {
                const pdfBtn = row.url_pdf_certificado ? `
                    <a href="${row.url_pdf_certificado}" target="_blank" class="btn btn-sm btn-outline-info py-1 px-2" title="Descargar o visualizar PDF">
                        <i class="fas fa-file-pdf me-1 text-danger"></i>PDF
                    </a>
                ` : `<span class="text-muted small">Sin PDF</span>`;

                const anularBtn = row.estatus_certificado !== 'Anulado' ? `
                    <button type="button" value="${row.id_certificado}" class="btn-action btn-delete btn-anular-certificado" title="Anular certificado">
                        <i class="fas fa-ban"></i>
                    </button>
                ` : '';

                return `
                    <div class="d-flex align-items-center justify-content-end gap-2">
                        ${pdfBtn}
                        ${anularBtn}
                    </div>
                `;
            }
        }
    ], {
        initComplete: function () {
            setTimeout(() => {
                hideSkeleton('#certificadoSkeleton', '#certificadoTableContainer');
                animateIn('#certificadoTableContainer', 300);
            }, 400);
        }
    });

    // 3. Anular Certificado con SweetAlert2
    $(document).on('click', '.btn-anular-certificado', async function () {
        const id = this.value;
        if (!id) return;

        const confirmado = await showConfirm(
            '¿Anular certificado?',
            'Esta acción marcará el certificado como Anulado y se ocultará del listado activo.',
            'Sí, anular certificado',
            'Volver'
        );

        if (confirmado) {
            showLoading('Anulando...', 'Actualizando el estado del certificado en el sistema');

            const formData = new FormData();
            formData.append('eliminar', id);

            const success = await executeAjax(
                $.ajax({
                    url: API_URL,
                    method: 'POST',
                    dataType: 'json',
                    data: formData,
                    processData: false,
                    contentType: false
                }),
                'Certificado anulado correctamente',
                () => {
                    closeLoading();
                    reloadDataTable(tblCertificado);
                }
            );

            if (!success) closeLoading();
        }
    });
});
