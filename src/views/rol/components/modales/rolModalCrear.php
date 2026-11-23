<!-- Modal Crear Rol -->
<div id="modalCrear" class="modal">
    <div class="modal-content" style="max-width: 550px;">
        <div class="modal-header">
            <h2 class="modal-title">Registrar Rol</h2>
            <span class="close-modal" onclick="cerrarModal('modalCrear')">&times;</span>
        </div>
        <form id="formCrearRol">
            <div class="modal-body">
                <div class="form-group mb-3">
                    <label class="form-label">Nombre del Rol <span class="text-danger">*</span></label>
                    <input type="text" name="nombre_rol" class="form-control" placeholder="Ej: Coordinador, Asistente" required maxlength="100" autocomplete="off">
                </div>
                
                <div class="form-group mb-3">
                    <label class="form-label">Descripción del Rol</label>
                    <textarea name="descripcion_rol" class="form-control" rows="3" placeholder="Describe los permisos y funciones principales de este rol..." maxlength="500"></textarea>
                </div>

                <div class="form-group mb-2">
                    <label class="form-label">Estado del Rol</label>
                    <select name="estatus_rol" class="form-control form-select">
                        <option value="1" selected>Activo (Habilitado)</option>
                        <option value="0">Inactivo (Deshabilitado)</option>
                    </select>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="cerrarModal('modalCrear')">Cancelar</button>
                <button type="submit" class="btn btn-primary" id="btnGuardarRol">
                    <i class="fas fa-save me-1"></i> Guardar Rol
                </button>
            </div>
        </form>
    </div>
</div>
