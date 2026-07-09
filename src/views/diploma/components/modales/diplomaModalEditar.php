<div id="modalEditar" class="modal">
    <div class="modal-content" style="max-width: 600px;">
        <div class="modal-header">
            <h2 class="modal-title">Editar Diploma</h2>
            <span class="close-modal" onclick="cerrarModal('modalEditar')">&times;</span>
        </div>
        <form id="formEditarDiploma">
            <input type="hidden" name="id_certificado_instructor" id="edit_id_certificado_instructor">
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Título del Diploma</label>
                    <input type="text" name="titulo_certificado_instructor" class="form-control" required onfocus="this.select()">
                </div>
                <div class="form-group">
                    <label class="form-label">Descripción</label>
                    <textarea name="descripcion_certificado_instructor" class="form-control" rows="3" onfocus="this.select()"></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Categoría</label>
                    <input type="text" name="categoria_certificado_instructor" class="form-control" required onfocus="this.select()">
                </div>
                <div class="form-group">
                    <label class="form-label">URL PDF (Opcional)</label>
                    <input type="url" name="url_pdf_certificado_instructor" class="form-control" onfocus="this.select()">
                </div>
                <div class="form-group">
                    <label class="form-label">Instructor</label>
                    <select name="id_instructor" id="id_instructor_editar" class="form-control" required>
                        <!-- Se cargará dinámicamente -->
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="cerrarModal('modalEditar')">Cancelar</button>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </form>
    </div>
</div>