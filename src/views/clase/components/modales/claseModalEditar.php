<div id="modalEditar" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Editar Clase</h2>
            <span class="close-modal" onclick="cerrarModal('modalEditar')">&times;</span>
        </div>
        <form id="formEditarClase">
            <input type="hidden" name="id_clase" id="edit_id_clase">
            <div class="modal-body">
                <div class="form-group mb-3">
                    <label class="form-label">Título de la Clase</label>
                    <input type="text" name="titulo_clase" class="form-control" required>
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">Descripción</label>
                    <textarea name="descripcion_clase" class="form-control"></textarea>
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">URL del Video</label>
                    <input type="text" name="url_video_clase" class="form-control" required>
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">Orden</label>
                    <input type="number" name="orden_clase" class="form-control" required>
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">ID Instructor Curso</label>
                    <input type="number" name="id_instructor_curso" class="form-control" required>
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">Estatus</label>
                    <select name="estatus_clase" class="form-control" required>
                        <option value="1">Activo</option>
                        <option value="0">Inactivo</option>
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