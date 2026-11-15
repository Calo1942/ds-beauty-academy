<div id="modalEditar" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Editar Banco</h2>
            <span class="close-modal" onclick="cerrarModal('modalEditar')">&times;</span>
        </div>
        <form id="formEditarBanco">
            <input type="hidden" name="id_banco" id="edit_id_banco">
            
        <div class="modal-body">
            <!-- Skeleton Loader tipo tabla para el modal -->
            <div class="skeleton-modal-table skeleton-table" style="display: none;">
                <div class="skeleton-header">
                    <div class="skeleton-header-cell" style="flex: 1;"></div>
                    <div class="skeleton-header-cell" style="flex: 2;"></div>
                </div>
                <div class="skeleton-row">
                    <div class="skeleton-cell" style="flex: 1;"></div>
                    <div class="skeleton-cell" style="flex: 2;"></div>
                </div>
                <div class="skeleton-row">
                    <div class="skeleton-cell" style="flex: 1;"></div>
                    <div class="skeleton-cell" style="flex: 2;"></div>
                </div>
                <div class="skeleton-row">
                    <div class="skeleton-cell" style="flex: 1;"></div>
                    <div class="skeleton-cell" style="flex: 2;"></div>
                </div>
                <div class="skeleton-row">
                    <div class="skeleton-cell" style="flex: 1;"></div>
                    <div class="skeleton-cell" style="flex: 2;"></div>
                </div>
            </div>
            <div class="modal-body-content">
                <div class="form-group">
                    <label for="nombre_banco_editar" class="form-label">Nombre del Banco</label>
                    <input type="text" name="nombre_banco" id="nombre_banco_editar" class="form-control" placeholder="Ingrese el nombre del banco" maxlength="150" required onfocus="this.select()">
                </div>
            </div>
        </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="cerrarModal('modalEditar')">Cancelar</button>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </form>
    </div>
</div>