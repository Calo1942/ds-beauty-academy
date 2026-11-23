<!-- Modal Editar Rol -->
<div id="modalEditar" class="modal">
    <div class="modal-content" style="max-width: 550px;">
        <div class="modal-header">
            <h2 class="modal-title">Editar Rol</h2>
            <span class="close-modal" onclick="cerrarModal('modalEditar')">&times;</span>
        </div>
        <form id="formEditarRol">
            <input type="hidden" name="id_rol" id="edit_id_rol">
            
            <div class="modal-body">
                <div class="form-group mb-3">
                    <label class="form-label">Nombre del Rol <span class="text-danger">*</span></label>
                    <input type="text" name="nombre_rol" id="edit_nombre_rol" class="form-control" required maxlength="100" autocomplete="off">
                </div>
                
                <div class="form-group mb-3">
                    <label class="form-label">Descripción del Rol</label>
                    <textarea name="descripcion_rol" id="edit_descripcion_rol" class="form-control" rows="3" maxlength="500"></textarea>
                </div>

                <div class="form-group mb-2">
                    <label class="form-label">Estado del Rol</label>
                    <select name="estatus_rol" id="edit_estatus_rol" class="form-control form-select">
                        <option value="1">Activo (Habilitado)</option>
                        <option value="0">Inactivo (Deshabilitado)</option>
                    </select>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="cerrarModal('modalEditar')">Cancelar</button>
                <button type="submit" class="btn btn-primary" id="btnActualizarRol">
                    <i class="fas fa-sync-alt me-1"></i> Actualizar Rol
                </button>
            </div>
        </form>
    </div>
</div>
