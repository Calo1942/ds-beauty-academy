<div id="modalCrear" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Registrar Recurso</h2>
            <span class="close-modal" onclick="cerrarModal('modalCrear')">&times;</span>
        </div>
        <form id="formCrearRecurso">
            <div class="modal-body">
                <div class="form-group mb-3">
                    <label for="nombre_recurso_crear" class="form-label">Nombre del Recurso</label>
                    <input type="text" name="nombre_recurso" id="nombre_recurso_crear" class="form-control" placeholder="Ingrese el nombre" maxlength="150" required>
                </div>
                <div class="form-group mb-3">
                    <label for="descripcion_recurso_crear" class="form-label">Descripción</label>
                    <textarea name="descripcion_recurso" id="descripcion_recurso_crear" class="form-control" placeholder="Descripción (opcional)"></textarea>
                </div>
                <div class="form-group mb-3">
                    <label for="url_archivo_recurso_crear" class="form-label">URL del Archivo</label>
                    <input type="text" name="url_archivo_recurso" id="url_archivo_recurso_crear" class="form-control" placeholder="Ingrese la URL del archivo" maxlength="512" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="cerrarModal('modalCrear')">Cancelar</button>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </form>
    </div>
</div>