<div id="modalCrear" class="modal">
    <div class="modal-content" style="max-width: 700px;">
        <div class="modal-header">
            <h2 class="modal-title">Registrar Instructor</h2>
            <span class="close-modal" onclick="cerrarModal('modalCrear')">&times;</span>
        </div>
        <form id="formCrearInstructor">
            <div class="modal-body">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label class="form-label">Primer Nombre</label>
                        <input type="text" name="primer_nombre_instructor" class="form-control" placeholder="Ej: María" required onfocus="this.select()">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Segundo Nombre</label>
                        <input type="text" name="segundo_nombre_instructor" class="form-control" placeholder="Ej: Elena" onfocus="this.select()">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Primer Apellido</label>
                        <input type="text" name="primer_apellido_instructor" class="form-control" placeholder="Ej: Pérez" required onfocus="this.select()">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Segundo Apellido</label>
                        <input type="text" name="segundo_apellido_instructor" class="form-control" placeholder="Ej: García" onfocus="this.select()">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Cédula</label>
                        <input type="text" name="cedula_instructor" class="form-control" placeholder="Ej: 12345678" required onfocus="this.select()">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Correo Electrónico</label>
                        <input type="email" name="correo_instructor" class="form-control" placeholder="Ej: maria@gmail.com" required onfocus="this.select()">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Teléfono Principal</label>
                        <input type="text" name="telefono_principal_instructor" class="form-control" placeholder="Ej: 04121234567" required onfocus="this.select()">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Teléfono Alternativo</label>
                        <input type="text" name="telefono_alternativo_instructor" class="form-control" placeholder="Ej: 04141234567" onfocus="this.select()">
                    </div>
                    <div class="form-group" style="grid-column: span 2;">
                        <label class="form-label">Usuario Instagram</label>
                        <input type="text" name="usuario_instagram_instructor" class="form-control" placeholder="Ej: @maria_perez" onfocus="this.select()">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="cerrarModal('modalCrear')">Cancelar</button>
                <button type="submit" class="btn btn-primary">Registrar</button>
            </div>
        </form>
    </div>
</div>