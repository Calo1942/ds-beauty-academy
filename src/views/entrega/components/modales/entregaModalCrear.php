<div id="modalCrear" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Subir Entrega de Evaluación</h2>
            <span class="close-modal" onclick="cerrarModal('modalCrear')">&times;</span>
        </div>
        <form id="formCrearEntrega" enctype="multipart/form-data">
            <div class="modal-body">
                <div class="form-group mb-3">
                    <label class="form-label">Matrícula de la Estudiante <span class="text-danger">*</span></label>
                    <select name="id_matricula" id="select_matricula_entrega" class="form-control" required>
                        <option value="">-- Cargando matrículas... --</option>
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label class="form-label">Evaluación a Entregar <span class="text-danger">*</span></label>
                    <select name="id_evaluacion" id="select_evaluacion_entrega" class="form-control" required>
                        <option value="">-- Cargando evaluaciones... --</option>
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label class="form-label">Archivo Adjunto (Foto de Práctica / Documento) <span class="text-danger">*</span></label>
                    <input type="file" name="archivo_entrega" id="archivo_entrega" class="form-control" accept="image/jpeg,image/png,image/webp,application/pdf,application/zip" required>
                    <small class="form-text text-muted">Formatos admitidos: JPG, PNG, WEBP, PDF, ZIP (Máx. 10 MB).</small>
                </div>

                <div class="form-group mb-3">
                    <label class="form-label">Comentarios o Dudas sobre la Asignación</label>
                    <textarea name="comentarios_estudiante" id="comentarios_estudiante" rows="3" class="form-control" placeholder="Describa el trabajo realizado, materiales empleados o dudas para la docente..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="cerrarModal('modalCrear')">Cancelar</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-upload me-1"></i>Enviar Asignación
                </button>
            </div>
        </form>
    </div>
</div>
