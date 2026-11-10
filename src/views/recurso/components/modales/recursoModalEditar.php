<div id="modalEditar" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Editar Recurso</h2>
            <span class="close-modal" onclick="cerrarModal('modalEditar')">&times;</span>
        </div>
        <form id="formEditarRecurso">
            <input type="hidden" id="edit_id_recurso" name="id_recurso">
            <div class="modal-body">
                <div class="form-group mb-3">
                    <label for="nombre_recurso_editar" class="form-label">Nombre del Recurso</label>
                    <input type="text" name="nombre_recurso" id="nombre_recurso_editar" class="form-control" placeholder="Ingrese el nombre" maxlength="150" required>
                </div>
                <div class="form-group mb-3">
                    <label for="descripcion_recurso_editar" class="form-label">Descripción</label>
                    <textarea name="descripcion_recurso" id="descripcion_recurso_editar" class="form-control" placeholder="Descripción (opcional)"></textarea>
                </div>
                <div class="form-group mb-3">
                    <label for="url_archivo_recurso_editar" class="form-label">URL del Archivo</label>
                    <input type="text" name="url_archivo_recurso" id="url_archivo_recurso_editar" class="form-control" placeholder="Ingrese la URL del archivo" maxlength="512" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="cerrarModal('modalEditar')">Cancelar</button>
                <button type="submit" class="btn btn-primary">Actualizar</button>
            </div>
        </form>
    </div>
</div>
