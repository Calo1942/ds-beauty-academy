<div id="modalDetalle" class="modal">
    <div class="modal-content" style="max-width: 600px;">
        <div class="modal-header">
            <h2 class="modal-title">Detalle del Estudiante</h2>
            <span class="close-modal" onclick="cerrarModal('modalDetalle')">&times;</span>
        </div>
        <div class="modal-body">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="detail-avatar-container">
                    <div class="detail-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="detail-info-header">
                        <div class="detail-id-badge">ID: <span id="detail_id_estudiante_badge"></span></div>
                        <div id="detail_nombre_completo_header" class="detail-name-header"></div>
                    </div>
                </div>
                <div class="detail-item" style="grid-column: span 2; display: none;">
                    <span class="detail-label">ID:</span>
                    <span id="detail_id_estudiante" class="detail-value"></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Cédula:</span>
                    <span id="detail_cedula" class="detail-value"></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Correo:</span>
                    <span id="detail_correo" class="detail-value"></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Teléfono:</span>
                    <span id="detail_telefono" class="detail-value"></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Instagram:</span>
                    <span id="detail_instagram" class="detail-value"></span>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" onclick="cerrarModal('modalDetalle')">Cerrar</button>
        </div>
    </div>
</div>