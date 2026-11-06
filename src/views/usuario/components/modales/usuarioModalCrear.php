<!-- Modal Crear Usuario -->
<div id="modalCrear" class="modal">
    <div class="modal-content" style="max-width: 600px;">
        <div class="modal-header">
            <h2 class="modal-title">Registrar Usuario</h2>
            <span class="close-modal" onclick="cerrarModal('modalCrear')">&times;</span>
        </div>
        <form id="formCrearUsuario">
            <div class="modal-body">
                <div class="form-group mb-3">
                    <label class="form-label">Correo Electrónico <span class="text-danger">*</span></label>
                    <input type="email" name="email_usuario" class="form-control" placeholder="ejemplo@beautyacademy.com" required maxlength="150" autocomplete="off">
                </div>
                
                <div class="form-group mb-3">
                    <label class="form-label">Contraseña <span class="text-danger">*</span></label>
                    <input type="password" name="password_usuario" class="form-control" placeholder="Mínimo 6 caracteres" required minlength="6" maxlength="72" autocomplete="new-password">
                </div>

                <div class="form-group mb-3">
                    <label class="form-label">Rol del Usuario <span class="text-danger">*</span></label>
                    <select name="id_rol" id="selectRolCrear" class="form-control form-select" required>
                        <option value="">-- Seleccionar Rol --</option>
                    </select>
                </div>

                <!-- Vinculación opcional según el rol -->
                <div class="form-group mb-3 d-none" id="groupInstructorCrear">
                    <label class="form-label">Vincular con Instructor</label>
                    <select name="id_instructor" id="selectInstructorCrear" class="form-control form-select">
                        <option value="">-- Seleccionar Instructor (Opcional) --</option>
                    </select>
                </div>

                <div class="form-group mb-3 d-none" id="groupEstudianteCrear">
                    <label class="form-label">Vincular con Estudiante</label>
                    <select name="id_estudiante" id="selectEstudianteCrear" class="form-control form-select">
                        <option value="">-- Seleccionar Estudiante (Opcional) --</option>
                    </select>
                </div>

                <div class="form-group mb-2">
                    <label class="form-label">Estado de la Cuenta</label>
                    <select name="estatus_usuario" class="form-control form-select">
                        <option value="1" selected>Activo (Acceso habilitado)</option>
                        <option value="0">Inactivo (Acceso suspendido)</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="cerrarModal('modalCrear')">Cancelar</button>
                <button type="submit" class="btn btn-primary">Registrar Usuario</button>
            </div>
        </form>
    </div>
</div>

