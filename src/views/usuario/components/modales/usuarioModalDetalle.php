<!-- Modal Detalle Usuario -->
<div id="modalDetalle" class="modal">
    <div class="modal-content" style="max-width: 550px;">
        <div class="modal-header">
            <h2 class="modal-title">Detalles del Usuario</h2>
            <span class="close-modal" onclick="cerrarModal('modalDetalle')">&times;</span>
        </div>
        <div class="modal-body">
            <div class="detail-item">
                <span class="detail-label">ID de Usuario:</span>
                <span class="detail-value" id="detail_id_usuario">-</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Persona Vinculada:</span>
                <span class="detail-value fw-semibold" id="detail_nombre_persona">-</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Correo Electrónico:</span>
                <span class="detail-value text-accent" id="detail_email_usuario">-</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Rol Asignado:</span>
                <span class="detail-value" id="detail_nombre_rol">-</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Estado de Cuenta:</span>
                <span class="detail-value" id="detail_estatus_usuario">-</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Fecha de Creación:</span>
                <span class="detail-value" id="detail_fecha_creacion">-</span>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="cerrarModal('modalDetalle')">Cerrar</button>
        </div>
    </div>
</div>

