<div id="modalEditar" class="modal">
    <div class="modal-content" style="max-width: 700px;">
        <div class="modal-header">
            <h2 class="modal-title">Editar Instructor</h2>
            <span class="close-modal" onclick="cerrarModal('modalEditar')">&times;</span>
        </div>
        <form id="formEditarInstructor">
            <input type="hidden" name="id_instructor" id="edit_id_instructor">
            <div class="modal-body">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label class="form-label">Primer Nombre</label>
                        <input type="text" name="primer_nombre_instructor" class="form-control" required onfocus="this.select()">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Segundo Nombre</label>
                        <input type="text" name="segundo_nombre_instructor" class="form-control" onfocus="this.select()">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Primer Apellido</label>
                        <input type="text" name="primer_apellido_instructor" class="form-control" required onfocus="this.select()">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Segundo Apellido</label>
                        <input type="text" name="segundo_apellido_instructor" class="form-control" onfocus="this.select()">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Cédula</label>
                        <input type="text" name="cedula_instructor" class="form-control" required onfocus="this.select()">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Correo Electrónico</label>
                        <input type="email" name="correo_instructor" class="form-control" required onfocus="this.select()">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Teléfono Principal</label>
                        <input type="text" name="telefono_principal_instructor" class="form-control" required onfocus="this.select()">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Teléfono Alternativo</label>
                        <input type="text" name="telefono_alternativo_instructor" class="form-control" onfocus="this.select()">
                    </div>
                    <div class="form-group" style="grid-column: span 2;">
                        <label class="form-label">Usuario Instagram</label>
                        <input type="text" name="usuario_instagram_instructor" class="form-control" onfocus="this.select()">
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