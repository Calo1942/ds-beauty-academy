<div id="modalDetalle" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Detalle de la Clase</h2>
            <span class="close-modal" onclick="cerrarModal('modalDetalle')">&times;</span>
        </div>
        <div class="modal-body">
            <div class="detail-item">
                <span class="detail-label">ID:</span>
                <span id="detail_id_clase" class="detail-value"></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Título:</span>
                <span id="detail_titulo_clase" class="detail-value"></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Descripción:</span>
                <span id="detail_descripcion_clase" class="detail-value"></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">URL del Video:</span>
                <span id="detail_url_video_clase" class="detail-value"></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Orden:</span>
                <span id="detail_orden_clase" class="detail-value"></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">ID Instructor Curso:</span>
                <span id="detail_id_instructor_curso" class="detail-value"></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Estatus:</span>
                <span id="detail_estatus_clase" class="detail-value"></span>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" onclick="cerrarModal('modalDetalle')">Cerrar</button>
        </div>
    </div>
</div>