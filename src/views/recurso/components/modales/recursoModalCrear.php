<div id="modalCrear" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Registrar Recurso</h2>
            <span class="close-modal" onclick="cerrarModal('modalCrear')">&times;</span>
        </div>
        <form id="formCrearRecurso">
            <div class="modal-body">
                <div class="form-group mb-3">
                    <label class="form-label">Título del Recurso</label>
                    <input type="text" name="titulo_recurso" class="form-control" placeholder="Ingrese el título" required>
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">Descripción</label>
                    <textarea name="descripcion_recurso" class="form-control" placeholder="Descripción (opcional)"></textarea>
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">URL del Archivo</label>
                    <input type="text" name="url_archivo_recurso" class="form-control" placeholder="Ingrese la URL del archivo" required>
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">ID Etiqueta</label>
                    <input type="number" name="id_etiqueta" class="form-control" placeholder="ID de la etiqueta" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="cerrarModal('modalCrear')">Cancelar</button>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </form>
    </div>
</div>