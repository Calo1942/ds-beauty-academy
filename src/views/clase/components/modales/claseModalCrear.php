<div id="modalCrear" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Registrar Clase</h2>
            <span class="close-modal" onclick="cerrarModal('modalCrear')">&times;</span>
        </div>
        <form id="formCrearClase">
            <div class="modal-body">
                <div class="form-group mb-3">
                    <label class="form-label">Título de la Clase</label>
                    <input type="text" name="titulo_clase" class="form-control" placeholder="Ingrese el título de la clase" required>
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">Descripción</label>
                    <textarea name="descripcion_clase" class="form-control" placeholder="Ingrese la descripción (opcional)"></textarea>
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">URL del Video</label>
                    <input type="text" name="url_video_clase" class="form-control" placeholder="Ingrese la URL del video" required>
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">Orden</label>
                    <input type="number" name="orden_clase" class="form-control" placeholder="Número de orden" required>
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">ID Instructor Curso</label>
                    <input type="number" name="id_instructor_curso" class="form-control" placeholder="ID del instructor-curso" required>
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
                <button type="button" class="btn btn-secondary" onclick="cerrarModal('modalCrear')">Cancelar</button>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </form>
    </div>
</div>