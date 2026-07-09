<div id="modalDetalle" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Detalle del Banco</h2>
            <span class="close-modal" onclick="cerrarModal('modalDetalle')">&times;</span>
        </div>
        <div class="modal-body">
            <div class="detail-item">
                <span class="detail-label">ID:</span>
                <span id="detail_id_banco" class="detail-value"></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Nombre del Banco:</span>
                <span id="detail_nombre_banco" class="detail-value"></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Estatus:</span>
                <span id="detail_estatus_banco" class="detail-value"></span>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" onclick="cerrarModal('modalDetalle')">Cerrar</button>
        </div>
    </div>
</div>