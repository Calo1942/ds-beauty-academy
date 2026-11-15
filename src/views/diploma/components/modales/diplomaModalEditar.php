<div id="modalEditar" class="modal">
    <div class="modal-content" style="max-width: 600px;">
        <div class="modal-header">
            <h2 class="modal-title">Editar Diploma</h2>
            <span class="close-modal" onclick="cerrarModal('modalEditar')">&times;</span>
        </div>
        <form id="formEditarDiploma" enctype="multipart/form-data">
            <input type="hidden" name="id_diploma" id="edit_id_diploma">
            <div class="modal-body">
                <div class="form-group mb-3">
                    <label for="nombre_diploma_editar" class="form-label">Título del Diploma <span class="text-danger">*</span></label>
                    <input type="text" name="nombre_diploma" id="nombre_diploma_editar" class="form-control" maxlength="255" required onfocus="this.select()">
                </div>
                <div class="form-group mb-3">
                    <label for="descripcion_diploma_editar" class="form-label">Descripción</label>
                    <textarea name="descripcion_diploma" id="descripcion_diploma_editar" class="form-control" rows="3" onfocus="this.select()"></textarea>
                </div>
                <div class="form-group mb-3">
                    <label for="categoria_diploma_editar" class="form-label">Categoría <span class="text-danger">*</span></label>
                    <input type="text" name="categoria_diploma" id="categoria_diploma_editar" class="form-control" maxlength="100" required onfocus="this.select()">
                </div>
                <div class="form-group mb-3" id="grupo_instructor_editar">
                    <label for="id_instructor_editar" class="form-label">Instructor Responsable <span class="text-danger">*</span></label>
                    <select name="id_instructor" id="id_instructor_editar" class="form-control" required>
                        <option value="">-- Seleccione un instructor --</option>
                    </select>
                    <input type="text" id="instructor_nombre_fijo_editar" class="form-control bg-dark border-secondary text-white" readonly style="display: none;">
                </div>
                <div class="form-group mb-3">
                    <label for="archivo_diploma_editar" class="form-label">Actualizar Documento Digital (PDF / Imagen)</label>
                    <input type="file" name="archivo_diploma" id="archivo_diploma_editar" class="form-control" accept="application/pdf,image/jpeg,image/png,image/webp">
                    <small class="form-text text-muted">Formatos admitidos: PDF, JPG, PNG, WEBP (Máx. 5 MB). Dejar vacío para conservar el actual.</small>
                </div>
                <div class="form-group mb-3">
                    <label for="url_pdf_diploma_editar" class="form-label">Ruta o Enlace Web Alternativo (Opcional)</label>
                    <input type="text" name="url_pdf_diploma" id="url_pdf_diploma_editar" class="form-control" placeholder="Ej: assets/uploads/diplomas/... o https://..." maxlength="512" onfocus="this.select()">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="cerrarModal('modalEditar')">Cancelar</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i>Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</div>
