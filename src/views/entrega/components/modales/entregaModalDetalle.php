<div id="modalDetalle" class="modal">
    <div class="modal-content modal-lg">
        <div class="modal-header">
            <h2 class="modal-title">Detalle de la Entrega</h2>
            <span class="close-modal" onclick="cerrarModal('modalDetalle')">&times;</span>
        </div>
        <div class="modal-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <h5 class="text-accent mb-3 border-bottom pb-2" style="color: var(--accent); font-size: 1rem;">
                        <i class="fas fa-user-graduate me-2"></i>Información Académica
                    </h5>
                    <div class="detail-item mb-2">
                        <span class="detail-label text-muted">ID Entrega:</span>
                        <span id="detail_id_entrega" class="detail-value text-white fw-bold"></span>
                    </div>
                    <div class="detail-item mb-2">
                        <span class="detail-label text-muted">Estudiante:</span>
                        <span id="detail_nombre_estudiante" class="detail-value text-white fw-bold"></span>
                    </div>
                    <div class="detail-item mb-2">
                        <span class="detail-label text-muted">Cédula:</span>
                        <span id="detail_cedula_estudiante" class="detail-value text-white"></span>
                    </div>
                    <div class="detail-item mb-2">
                        <span class="detail-label text-muted">Curso:</span>
                        <span id="detail_nombre_curso" class="detail-value text-white"></span>
                    </div>
                    <div class="detail-item mb-2">
                        <span class="detail-label text-muted">Evaluación:</span>
                        <span id="detail_titulo_evaluacion" class="detail-value text-white"></span>
                    </div>
                    <div class="detail-item mb-2">
                        <span class="detail-label text-muted">Fecha de Envío:</span>
                        <span id="detail_fecha_entrega" class="detail-value text-white"></span>
                    </div>
                </div>

                <div class="col-md-6">
                    <h5 class="text-accent mb-3 border-bottom pb-2" style="color: var(--accent); font-size: 1rem;">
                        <i class="fas fa-award me-2"></i>Calificación Pedagógica
                    </h5>
                    <div class="detail-item mb-2">
                        <span class="detail-label text-muted">Estatus de Revisión:</span>
                        <span id="detail_estatus_entrega" class="detail-value"></span>
                    </div>
                    <div class="detail-item mb-2">
                        <span class="detail-label text-muted">Nota Obtenida:</span>
                        <span id="detail_calificacion_entrega" class="detail-value fw-bold" style="color: var(--accent); font-size: 1.2rem;"></span>
                    </div>
                    <div class="detail-item mb-2">
                        <span class="detail-label text-muted">Instructora / Evaluador:</span>
                        <span id="detail_nombre_instructor" class="detail-value text-white"></span>
                    </div>
                    <div class="detail-item mb-2">
                        <span class="detail-label text-muted">Retroalimentación Docente:</span>
                        <div id="detail_feedback_instructora" class="p-2 rounded bg-black text-light small border border-secondary mt-1">
                            <em>Sin retroalimentación asignada</em>
                        </div>
                    </div>
                </div>

                <div class="col-12 mt-2">
                    <span class="text-muted small">Comentarios de la Alumna:</span>
                    <div id="detail_comentarios_estudiante" class="p-2 rounded bg-dark text-light small border border-secondary mt-1">
                        <em>Sin comentarios</em>
                    </div>
                </div>

                <div class="col-12 mt-3 pt-3 border-top">
                    <h5 class="text-accent mb-2" style="color: var(--accent); font-size: 1rem;">
                        <i class="fas fa-paperclip me-2"></i>Archivo de la Asignación
                    </h5>
                    <div id="detail_archivo_adjunto" class="p-3 text-center rounded bg-dark border border-secondary">
                        <!-- Vista previa del archivo o enlace de descarga -->
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer d-flex justify-content-between">
            <div id="detail_actions_container">
                <!-- Botón rápido para calificar se inyecta dinámicamente -->
            </div>
            <button type="button" class="btn btn-secondary" onclick="cerrarModal('modalDetalle')">Cerrar</button>
        </div>
    </div>
</div>
