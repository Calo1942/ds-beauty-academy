<div id="modalCalificar" class="modal">
    <div class="modal-content modal-lg">
        <div class="modal-header">
            <h2 class="modal-title">Calificar Entrega Pedagógica</h2>
            <span class="close-modal" onclick="cerrarModal('modalCalificar')">&times;</span>
        </div>
        <form id="formCalificarEntrega">
            <input type="hidden" name="id_entrega_evaluacion" id="calificar_id_entrega">
            <div class="modal-body">
                <!-- Resumen de la entrega de la alumna -->
                <div class="p-3 mb-3 rounded bg-dark border border-secondary">
                    <div class="row g-2">
                        <div class="col-md-6">
                            <span class="text-muted small">Alumna:</span>
                            <div id="calificar_nombre_estudiante" class="fw-bold text-white"></div>
                        </div>
                        <div class="col-md-6">
                            <span class="text-muted small">Curso:</span>
                            <div id="calificar_nombre_curso" class="text-white"></div>
                        </div>
                        <div class="col-md-6">
                            <span class="text-muted small">Evaluación:</span>
                            <div id="calificar_titulo_evaluacion" class="text-light fw-semibold"></div>
                        </div>
                        <div class="col-md-6">
                            <span class="text-muted small">Fecha de Entrega:</span>
                            <div id="calificar_fecha_entrega" class="text-muted"></div>
                        </div>
                        <div class="col-12 mt-2">
                            <span class="text-muted small">Comentarios de la Estudiante:</span>
                            <div id="calificar_comentarios_estudiante" class="p-2 rounded bg-black text-light font-monospace small border border-secondary mt-1">
                                <em>Sin comentarios</em>
                            </div>
                        </div>
                        <div class="col-12 mt-2">
                            <span class="text-muted small">Material Entregado:</span>
                            <div id="calificar_adjunto_preview" class="mt-1">
                                <!-- Enlace o vista previa del archivo adjunto -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Formulario de Evaluación -->
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label">Calificación (Escala 0 a 100) <span class="text-danger">*</span></label>
                            <input type="number" step="0.5" min="0" max="100" name="calificacion_entrega" id="calificar_nota" class="form-control" placeholder="Ej. 95.00" required>
                            <small class="form-text text-muted">Aprobación estándar a partir de 75 puntos.</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label">Estatus de la Entrega <span class="text-danger">*</span></label>
                            <select name="estatus_entrega" id="calificar_estatus" class="form-control" required>
                                <option value="Aprobada">Aprobada (Acreditada)</option>
                                <option value="Repetir">Repetir (Requiere correcciones)</option>
                                <option value="En Revisión">En Revisión (Pendiente de corrección)</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group mb-3">
                            <label class="form-label">Feedback y Recomendaciones de la Instructora</label>
                            <textarea name="feedback_instructora_entrega" id="calificar_feedback" rows="4" class="form-control" placeholder="Escriba comentarios pedagógicos, técnicas a mejorar o felicitaciones para la estudiante..."></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="cerrarModal('modalCalificar')">Cancelar</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-check-circle me-1"></i>Guardar Calificación
                </button>
            </div>
        </form>
    </div>
</div>
