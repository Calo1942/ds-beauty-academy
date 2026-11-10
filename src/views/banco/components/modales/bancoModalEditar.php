<div id="modalEditar" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Editar Banco</h2>
            <span class="close-modal" onclick="cerrarModal('modalEditar')">&times;</span>
        </div>
        <form id="formEditarBanco">
            <input type="hidden" name="id_banco" id="edit_id_banco">
            <div class="modal-body">
                <div class="form-group">
                    <label for="nombre_banco_editar" class="form-label">Nombre del Banco</label>
                    <input type="text" name="nombre_banco" id="nombre_banco_editar" class="form-control" placeholder="Ingrese el nombre del banco" maxlength="150" required onfocus="this.select()">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="cerrarModal('modalEditar')">Cancelar</button>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </form>
    </div>
</div>