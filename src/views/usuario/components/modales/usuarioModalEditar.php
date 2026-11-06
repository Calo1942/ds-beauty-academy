<!-- Modal Editar Usuario -->
<div id="modalEditar" class="modal">
    <div class="modal-content" style="max-width: 600px;">
        <div class="modal-header">
            <h2 class="modal-title">Editar Usuario</h2>
            <span class="close-modal" onclick="cerrarModal('modalEditar')">&times;</span>
        </div>
        <form id="formEditarUsuario">
            <input type="hidden" name="id_usuario" id="edit_id_usuario">
            <div class="modal-body">
                <div class="form-group mb-3">
                    <label class="form-label">Correo Electrónico <span class="text-danger">*</span></label>
                    <input type="email" name="email_usuario" id="edit_email_usuario" class="form-control" required maxlength="150" autocomplete="off">
                </div>
                
                <div class="form-group mb-3">
                    <label class="form-label">Nueva Contraseña <small class="text-muted">(Dejar en blanco para mantener la actual)</small></label>
                    <input type="password" name="password_usuario" id="edit_password_usuario" class="form-control" placeholder="Opcional: cambiar contraseña" minlength="6" maxlength="72" autocomplete="new-password">
                </div>

                <div class="form-group mb-3">
                    <label class="form-label">Rol del Usuario <span class="text-danger">*</span></label>
                    <select name="id_rol" id="selectRolEditar" class="form-control form-select" required>
                        <option value="">-- Seleccionar Rol --</option>
                    </select>
                </div>

                <!-- Vinculación opcional según el rol -->
                <div class="form-group mb-3 d-none" id="groupInstructorEditar">
                    <label class="form-label">Vincular con Instructor</label>
                    <select name="id_instructor" id="selectInstructorEditar" class="form-control form-select">
                        <option value="">-- Sin Vinculación --</option>
                    </select>
                </div>

                <div class="form-group mb-3 d-none" id="groupEstudianteEditar">
                    <label class="form-label">Vincular con Estudiante</label>
                    <select name="id_estudiante" id="selectEstudianteEditar" class="form-control form-select">
                        <option value="">-- Sin Vinculación --</option>
                    </select>
                </div>

                <div class="form-group mb-2">
                    <label class="form-label">Estado de la Cuenta</label>
                    <select name="estatus_usuario" id="edit_estatus_usuario" class="form-control form-select">
                        <option value="1">Activo (Acceso habilitado)</option>
                        <option value="0">Inactivo (Acceso suspendido)</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="cerrarModal('modalEditar')">Cancelar</button>
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            </div>
        </form>
    </div>
</div>

