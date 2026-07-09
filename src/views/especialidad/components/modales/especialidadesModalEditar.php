<div id="modalEditar" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Editar Especialidad</h2>
            <span class="close-modal" onclick="cerrarModal('modalEditar')">&times;</span>
        </div>
        <form id="formEditarEspecialidad">
            <input type="hidden" name="id_especialidad" id="edit_id_especialidad">
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Nombre de la Especialidad</label>
                    <input type="text" name="nombre_especialidad" class="form-control" required onfocus="this.select()">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="cerrarModal('modalEditar')">Cancelar</button>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </form>
    </div>
</div>