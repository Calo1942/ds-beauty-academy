<div id="modalDetalle" class="modal">
    <div class="modal-content" style="max-width: 600px;">
        <div class="modal-header">
            <h2 class="modal-title">Detalle del Diploma</h2>
            <span class="close-modal" onclick="cerrarModal('modalDetalle')">&times;</span>
        </div>
        <div class="modal-body">
            <div class="detail-item">
                <span class="detail-label">ID:</span>
                <span id="detail_id_certificado_instructor" class="detail-value"></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Título:</span>
                <span id="detail_titulo" class="detail-value"></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Descripción:</span>
                <span id="detail_descripcion" class="detail-value"></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Categoría:</span>
                <span id="detail_categoria" class="detail-value"></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Instructor:</span>
                <span id="detail_instructor" class="detail-value"></span>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" onclick="cerrarModal('modalDetalle')">Cerrar</button>
        </div>
    </div>
</div>