<div id="modalCrear" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Registrar Especialidad</h2>
            <span class="close-modal" onclick="cerrarModal('modalCrear')">&times;</span>
        </div>
        <form id="formCrearEspecialidad">
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Nombre de la Especialidad</label>
                    <input type="text" name="nombre_especialidad" class="form-control" placeholder="Ej: Colorimetría" required onfocus="this.select()">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="cerrarModal('modalCrear')">Cancelar</button>
                <button type="submit" class="btn btn-primary">Registrar</button>
            </div>
        </form>
    </div>
</div>