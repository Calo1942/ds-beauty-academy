// entregaDataTable.js - Gestión de Entregas y Calificaciones Pedagógicas
import { createDataTable, reloadDataTable } from '../helpers/dataTable.js';
import { getById, executeAjax } from '../helpers/ajax.js';
import { showConfirm, showLoading, closeLoading, showToast } from '../helpers/sweetalert.js';
import { animateIn } from '../helpers/animations.js';
import { showSkeleton, hideSkeleton } from '../helpers/skeleton.js';
import { limpiarValidaciones } from '../helpers/validation.js';

const API_URL = window.entregaApiUrl || '?url=entrega';

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

// Cargar matrículas de estudiantes para el modal de nueva entrega
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
                    const selected = (valorSeleccionado && valorSeleccionado == id) ? 'selected' : '';
                    options += `<option value="${id}" ${selected}>#${id} - ${nombre} ${cedula} | ${curso}</option>`;
                });
            $select.html(options);
            if (valorSeleccionado) $select.val(valorSeleccionado);
        }
    } catch (err) {
        console.error('Error cargando matrículas:', err);
    }
}

// Cargar evaluaciones activas para el modal de nueva entrega
async function cargarEvaluaciones(selectSelector, valorSeleccionado = null) {
    const $select = $(selectSelector);
    if (!$select.length) return;

    try {
        const response = await $.ajax({
            url: '?url=evaluacion',
            method: 'POST',
            data: { buscarTodos: true },
            dataType: 'json'
        });

        if (response && response.status === 'success' && Array.isArray(response.data)) {
            let options = '<option value="">-- Seleccione una evaluación --</option>';
            response.data
                .filter(ev => ev.estatus_evaluacion == 1 || ev.estatus_evaluacion === '1' || ev.estatus_evaluacion === true)
                .forEach(ev => {
                    const id = ev.id_evaluacion;
                    const titulo = ev.titulo_evaluacion || 'Evaluación';
                    const tipo = ev.tipo_evaluacion ? `[${ev.tipo_evaluacion.toUpperCase()}]` : '';
                    const selected = (valorSeleccionado && valorSeleccionado == id) ? 'selected' : '';
                    options += `<option value="${id}" ${selected}>#${id} - ${titulo} ${tipo}</option>`;
                });
            $select.html(options);
            if (valorSeleccionado) $select.val(valorSeleccionado);
        }
    } catch (err) {
        console.error('Error cargando evaluaciones:', err);
    }
}

// Helper para badges de estado de entrega
function renderBadgeEstadoEntrega(estatus) {
    let badgeClass = 'bg-secondary';
    if (estatus === 'Aprobada') badgeClass = 'bg-success text-white';
    else if (estatus === 'Repetir') badgeClass = 'bg-danger text-white';
    else if (estatus === 'En Revisión') badgeClass = 'bg-info text-white';
    else if (estatus === 'Pendiente') badgeClass = 'bg-warning text-dark';
    return `<span class="badge ${badgeClass} px-2 py-1">${estatus || 'Pendiente'}</span>`;
}

$(document).ready(function () {
    // 1. Mostrar skeleton mientras carga la tabla inicial
    showSkeleton('#entregaSkeleton', '#entregaTableContainer');

    // 2. Inicializar DataTable
    const tblEntrega = createDataTable('#entregaTable', API_URL, [
        {
            data: 'id_entrega_evaluacion',
            className: 'text-muted fw-bold'
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
            data: 'titulo_evaluacion',
            render: (data, type, row) => `
                <div class="fw-semibold text-white">
                    <i class="fas fa-clipboard-check me-1 text-accent" style="color: var(--accent);"></i>${data || 'N/A'}
                </div>
                <small class="badge bg-dark border border-secondary text-muted text-capitalize">${row.tipo_evaluacion || 'General'}</small>
            `
        },
        {
            data: 'fecha_entrega',
            render: (data) => `<span class="text-white fw-medium small">${data ? data.substring(0, 16) : 'N/A'}</span>`
        },
        {
            data: 'url_archivo_entrega',
            orderable: false,
            searchable: false,
            render: (data) => {
                if (!data) return `<span class="text-muted small">Sin adjunto</span>`;
                const isPdf = data.toLowerCase().endsWith('.pdf');
                const isZip = data.toLowerCase().endsWith('.zip') || data.toLowerCase().endsWith('.rar');
                let icon = 'fa-image';
                if (isPdf) icon = 'fa-file-pdf text-danger';
                else if (isZip) icon = 'fa-file-archive text-warning';

                return `
                    <button type="button" class="btn btn-sm btn-outline-info btn-ver-adjunto py-0 px-2" data-url="${data}" title="Ver archivo entregado">
                        <i class="fas ${icon} me-1"></i>Ver
                    </button>
                `;
            }
        },
        {
            data: 'calificacion_entrega',
            render: (data) => {
                if (data === null || data === undefined || data === '') {
                    return `<span class="badge bg-secondary text-muted">Sin nota</span>`;
                }
                const nota = parseFloat(data);
                const color = (nota >= 75) ? 'text-success' : 'text-danger';
                return `<span class="fw-bold ${color}">${nota.toFixed(1)} / 100</span>`;
            }
        },
        {
            data: 'estatus_entrega',
            render: (data) => renderBadgeEstadoEntrega(data)
        },
        {
            data: null,
            orderable: false,
            searchable: false,
            className: 'text-end',
            render: (data, type, row) => {
                const id = row.id_entrega_evaluacion;
                return `
                    <div class="d-flex gap-1 justify-content-end align-items-center">
                        <button type="button" value="${id}" class="btn-action btn-view btn-table-ver" title="Ver detalle de la entrega">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button type="button" value="${id}" class="btn-action btn-success btn-table-calificar" title="Calificar esta entrega">
                            <i class="fas fa-star"></i>
                        </button>
                        <button type="button" value="${id}" class="btn-action btn-delete btn-table-eliminar" title="Eliminar entrega">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                `;
            }
        }
    ], {
        initComplete: function () {
            setTimeout(() => {
                hideSkeleton('#entregaSkeleton', '#entregaTableContainer');
                animateIn('#entregaTableContainer', 300);
            }, 400);
        }
    });

    // 3. Abrir Modal Crear Entrega (Estudiante / Admin)
    $(document).on('click', '.btn-registrar-entrega, [data-modal="modalCrear"]', function () {
        const form = document.getElementById('formCrearEntrega');
        if (form) {
            form.reset();
            limpiarValidaciones(form);
        }
        cargarMatriculas('#select_matricula_entrega');
        cargarEvaluaciones('#select_evaluacion_entrega');
        abrirModal('modalCrear');
    });

    // 4. Submit Formulario Crear Entrega
    $(document).on('submit', '#formCrearEntrega', async function (e) {
        e.preventDefault();

        const form = this;
        const formData = new FormData(form);
        formData.append('guardar', 'true');

        showLoading('Subiendo...', 'Enviando asignación pedagógica al sistema');

        const success = await executeAjax(
            $.ajax({
                url: API_URL,
                method: 'POST',
                data: formData,
                dataType: 'json',
                processData: false,
                contentType: false
            }),
            'Asignación enviada exitosamente',
            () => {
                closeLoading();
                cerrarModal('modalCrear');
                form.reset();
                limpiarValidaciones(form);
                reloadDataTable(tblEntrega);
            }
        );

        if (!success) closeLoading();
    });

    // 5. Abrir Modal Calificar
    $(document).on('click', '.btn-table-calificar', async function () {
        const id = this.value;
        if (!id) return;

        showLoading('Cargando entrega...', 'Obteniendo datos de la asignación');

        await executeAjax(
            getById(API_URL, id),
            null,
            (response) => {
                closeLoading();
                const entrega = response.data;
                if (!entrega) return;

                const form = document.getElementById('formCalificarEntrega');
                if (form) {
                    form.reset();
                    limpiarValidaciones(form);
                }

                $('#calificar_id_entrega').val(entrega.id_entrega_evaluacion);
                $('#calificar_nombre_estudiante').text(entrega.nombre_estudiante || 'Sin nombre');
                $('#calificar_nombre_curso').text(entrega.nombre_curso || 'N/A');
                $('#calificar_titulo_evaluacion').text(entrega.titulo_evaluacion || 'N/A');
                $('#calificar_fecha_entrega').text(entrega.fecha_entrega || 'N/A');

                const comentarios = entrega.comentarios_estudiante;
                $('#calificar_comentarios_estudiante').html(
                    comentarios ? `"${comentarios}"` : '<em>La estudiante no incluyó comentarios adicionales.</em>'
                );

                // Vista previa de material
                const adjuntoDiv = $('#calificar_adjunto_preview');
                if (entrega.url_archivo_entrega) {
                    const url = entrega.url_archivo_entrega;
                    const isPdf = url.toLowerCase().endsWith('.pdf');
                    const isImg = url.toLowerCase().match(/\.(jpg|jpeg|png|webp)$/);

                    if (isImg) {
                        adjuntoDiv.html(`
                            <div class="d-flex align-items-center gap-3">
                                <img src="${url}" alt="Trabajo" class="rounded border border-secondary" style="height: 70px; width: 70px; object-fit: cover;">
                                <div>
                                    <div class="text-white small fw-bold mb-1">Archivo de imagen adjunto</div>
                                    <a href="${url}" target="_blank" class="btn btn-sm btn-outline-info">
                                        <i class="fas fa-expand me-1"></i>Abrir Imagen Completa
                                    </a>
                                </div>
                            </div>
                        `);
                    } else if (isPdf) {
                        adjuntoDiv.html(`
                            <a href="${url}" target="_blank" class="btn btn-sm btn-outline-danger">
                                <i class="fas fa-file-pdf me-1"></i>Abrir Documento PDF de la Alumna
                            </a>
                        `);
                    } else {
                        adjuntoDiv.html(`
                            <a href="${url}" target="_blank" class="btn btn-sm btn-outline-light">
                                <i class="fas fa-download me-1"></i>Descargar Archivo Adjunto
                            </a>
                        `);
                    }
                } else {
                    adjuntoDiv.html(`<span class="text-muted small">Sin archivo adjunto</span>`);
                }

                // Cargar valores existentes si ya fue calificada
                if (entrega.calificacion_entrega !== null && entrega.calificacion_entrega !== undefined) {
                    $('#calificar_nota').val(parseFloat(entrega.calificacion_entrega));
                }
                if (entrega.estatus_entrega) {
                    $('#calificar_estatus').val(entrega.estatus_entrega);
                }
                $('#calificar_feedback').val(entrega.feedback_instructora_entrega || '');

                abrirModal('modalCalificar');
            }
        );
    });

    // 6. Submit Formulario Calificar Entrega
    $(document).on('submit', '#formCalificarEntrega', async function (e) {
        e.preventDefault();

        const form = this;
        const formData = new FormData(form);
        formData.append('calificar', 'true');

        const nota = parseFloat($('#calificar_nota').val());
        const estatus = $('#calificar_estatus').val();

        if (estatus === 'Aprobada' && nota < 75) {
            const continuar = await showConfirm(
                'Nota inferior al estándar de aprobación',
                `Ha asignado ${nota} puntos y estatus "Aprobada" (el umbral habitual es 75 pts). ¿Desea proceder y validar la aprobación?`,
                'Sí, aprobar de todas formas',
                'Corregir nota'
            );
            if (!continuar) return;
        }

        showLoading('Guardando evaluación...', 'Registrando nota y generando certificado si corresponde');

        const success = await executeAjax(
            $.ajax({
                url: API_URL,
                method: 'POST',
                data: formData,
                dataType: 'json',
                processData: false,
                contentType: false
            }),
            `Entrega evaluada exitosamente como "${estatus}"`,
            () => {
                closeLoading();
                cerrarModal('modalCalificar');
                form.reset();
                limpiarValidaciones(form);
                reloadDataTable(tblEntrega);
            }
        );

        if (!success) closeLoading();
    });

    // 7. Modal Ver Detalle de Entrega
    $(document).on('click', '.btn-table-ver', async function () {
        const id = this.value;
        if (!id) return;

        showLoading('Cargando detalle...', 'Obteniendo información de la entrega');

        await executeAjax(
            getById(API_URL, id),
            null,
            (response) => {
                closeLoading();
                const entrega = response.data;
                if (!entrega) return;

                $('#detail_id_entrega').text(`#${entrega.id_entrega_evaluacion}`);
                $('#detail_nombre_estudiante').text(entrega.nombre_estudiante || 'Sin nombre');
                $('#detail_cedula_estudiante').text(entrega.cedula_estudiante || 'N/A');
                $('#detail_nombre_curso').text(entrega.nombre_curso || 'N/A');
                $('#detail_titulo_evaluacion').text(entrega.titulo_evaluacion || 'N/A');
                $('#detail_fecha_entrega').text(entrega.fecha_entrega || 'N/A');
                $('#detail_nombre_instructor').text(entrega.nombre_instructor || 'Sin docente asignado');

                $('#detail_estatus_entrega').html(renderBadgeEstadoEntrega(entrega.estatus_entrega));

                const nota = entrega.calificacion_entrega;
                if (nota !== null && nota !== undefined && nota !== '') {
                    $('#detail_calificacion_entrega').text(`${parseFloat(nota).toFixed(1)} / 100`);
                } else {
                    $('#detail_calificacion_entrega').text('Pendiente de evaluación');
                }

                $('#detail_feedback_instructora').html(
                    entrega.feedback_instructora_entrega
                        ? `<p class="mb-0 text-white">${entrega.feedback_instructora_entrega}</p>`
                        : '<em>Sin observaciones aún por parte de la docente.</em>'
                );

                $('#detail_comentarios_estudiante').html(
                    entrega.comentarios_estudiante
                        ? `<p class="mb-0 text-white">${entrega.comentarios_estudiante}</p>`
                        : '<em>La estudiante no incluyó comentarios en esta entrega.</em>'
                );

                // Archivo adjunto
                const adjuntoContainer = $('#detail_archivo_adjunto');
                if (entrega.url_archivo_entrega) {
                    const url = entrega.url_archivo_entrega;
                    const isPdf = url.toLowerCase().endsWith('.pdf');
                    const isImg = url.toLowerCase().match(/\.(jpg|jpeg|png|webp)$/);

                    if (isImg) {
                        adjuntoContainer.html(`
                            <div class="py-2">
                                <img src="${url}" alt="Trabajo entregado" class="img-fluid rounded border border-secondary mb-2" style="max-height: 250px; object-fit: contain; cursor: pointer;" onclick="window.open('${url}', '_blank')">
                                <div>
                                    <a href="${url}" target="_blank" class="btn btn-sm btn-outline-info">
                                        <i class="fas fa-expand me-1"></i>Ver en tamaño completo
                                    </a>
                                </div>
                            </div>
                        `);
                    } else if (isPdf) {
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
                                <i class="fas fa-file-archive text-warning fa-3x mb-2"></i>
                                <div class="text-white small mb-2 font-monospace">${url}</div>
                                <a href="${url}" target="_blank" class="btn btn-sm btn-outline-light">
                                    <i class="fas fa-download me-1"></i>Descargar Archivo Adjunto
                                </a>
                            </div>
                        `);
                    }
                } else {
                    adjuntoContainer.html(`<span class="text-muted small">No se adjuntó ningún archivo para esta entrega.</span>`);
                }

                // Inyectar botón rápido de calificar
                $('#detail_actions_container').html(`
                    <button type="button" value="${entrega.id_entrega_evaluacion}" class="btn btn-success btn-sm btn-table-calificar" onclick="cerrarModal('modalDetalle')">
                        <i class="fas fa-star me-1"></i>Calificar esta Entrega
                    </button>
                `);

                abrirModal('modalDetalle');
            }
        );
    });

    // 8. Previsualización rápida de archivo adjunto
    $(document).on('click', '.btn-ver-adjunto', function () {
        const fileUrl = $(this).data-url || $(this).attr('data-url');
        if (!fileUrl) return;

        const isImg = fileUrl.toLowerCase().match(/\.(jpg|jpeg|png|webp)$/);
        const isPdf = fileUrl.toLowerCase().endsWith('.pdf');

        if (isPdf) {
            Swal.fire({
                title: 'Documento PDF de Entrega',
                html: `
                    <div class="mb-3 text-muted">El archivo entregado es un documento en formato PDF.</div>
                    <a href="${fileUrl}" target="_blank" class="btn btn-primary">
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

        if (isImg) {
            Swal.fire({
                title: 'Trabajo Práctico Entregado',
                imageUrl: fileUrl,
                imageAlt: 'Imagen de entrega',
                html: `
                    <div class="mt-2">
                        <a href="${fileUrl}" target="_blank" class="btn btn-sm btn-outline-light">
                            <i class="fas fa-expand me-1"></i>Ver en tamaño original
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
            return;
        }

        // Si es ZIP u otro formato, abrir directamente para descarga
        window.open(fileUrl, '_blank');
    });

    // 9. Eliminar Entrega
    $(document).on('click', '.btn-table-eliminar', async function () {
        const id = this.value;
        if (!id) return;

        const confirmado = await showConfirm(
            '¿Eliminar entrega de evaluación?',
            'Esta acción eliminará el registro de la entrega y cualquier calificación asociada. La estudiante podrá volver a enviar su asignación.',
            'Sí, eliminar entrega',
            'Cancelar'
        );

        if (confirmado) {
            showLoading('Eliminando...', 'Borrando asignación del sistema');

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
                'Entrega eliminada exitosamente',
                () => {
                    closeLoading();
                    reloadDataTable(tblEntrega);
                }
            );

            if (!success) closeLoading();
        }
    });
});
