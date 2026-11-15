<div id="modalCrear" class="modal">
    <div class="modal-content" style="max-width: 600px;">
        <div class="modal-header">
            <h2 class="modal-title">Registrar Diploma</h2>
            <span class="close-modal" onclick="cerrarModal('modalCrear')">&times;</span>
        </div>
        <form id="formCrearDiploma" enctype="multipart/form-data">
            <div class="modal-body">
                <div class="form-group mb-3">
                    <label for="nombre_diploma_crear" class="form-label">Título del Diploma <span class="text-danger">*</span></label>
                    <input type="text" name="nombre_diploma" id="nombre_diploma_crear" class="form-control" placeholder="Ej: Especialista en Cejas" maxlength="255" required onfocus="this.select()">
                </div>
                <div class="form-group mb-3">
                    <label for="descripcion_diploma_crear" class="form-label">Descripción</label>
                    <textarea name="descripcion_diploma" id="descripcion_diploma_crear" class="form-control" placeholder="Breve descripción del diploma" rows="3" onfocus="this.select()"></textarea>
                </div>
                <div class="form-group mb-3">
                    <label for="categoria_diploma_crear" class="form-label">Categoría <span class="text-danger">*</span></label>
                    <input type="text" name="categoria_diploma" id="categoria_diploma_crear" class="form-control" placeholder="Ej: Estética" maxlength="100" required onfocus="this.select()">
                </div>
                <div class="form-group mb-3">
                    <label for="id_instructor_crear" class="form-label">Instructor Responsable <span class="text-danger">*</span></label>
                    <select name="id_instructor" id="id_instructor_crear" class="form-control" required>
                        <option value="">-- Seleccione un instructor --</option>
                    </select>
                </div>
                <div class="form-group mb-3">
                    <label for="archivo_diploma_crear" class="form-label">Documento Digital del Diploma (PDF / Imagen)</label>
                    <input type="file" name="archivo_diploma" id="archivo_diploma_crear" class="form-control" accept="application/pdf,image/jpeg,image/png,image/webp">
                    <small class="form-text text-muted">Formatos admitidos: PDF, JPG, PNG, WEBP (Máx. 5 MB).</small>
                </div>
                <div class="form-group mb-3">
                    <label for="url_pdf_diploma_crear" class="form-label">Ruta o Enlace Web Alternativo (Opcional)</label>
                    <input type="text" name="url_pdf_diploma" id="url_pdf_diploma_crear" class="form-control" placeholder="Ej: assets/uploads/diplomas/... o https://..." maxlength="512" onfocus="this.select()">
                    <small class="form-text text-muted">Puede ingresar una ruta interna o enlace externo si no adjunta archivo físico.</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="cerrarModal('modalCrear')">Cancelar</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i>Registrar Diploma
                </button>
            </div>
        </form>
    </div>
</div>
