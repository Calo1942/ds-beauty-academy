<div id="modalCrear" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Registro de Nuevo Banco</h2>
            <span class="close-modal" onclick="cerrarModal('modalCrear')">&times;</span>
        </div>
        <form id="formCrearBanco">
            <div class="modal-body">
                <div class="form-group">
                    <label for="nombre_banco" class="form-label">Nombre del Banco</label>
                    <input type="text" name="nombre_banco" id="nombre_banco" class="form-control" placeholder="Ingrese el nombre del banco" required onfocus="this.select()">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="cerrarModal('modalCrear')">Cancelar</button>
                <button type="submit" class="btn btn-primary">Registrar</button>
            </div>
        </form>
    </div>
</div>