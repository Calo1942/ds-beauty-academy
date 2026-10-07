<div id="modalDetalle" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Detalle de la Evaluación</h2>
            <span class="close-modal" onclick="cerrarModal('modalDetalle')">&times;</span>
        </div>
        <div class="modal-body">
            <div class="detail-item">
                <span class="detail-label">ID Evaluación</span>
                <span class="detail-value" id="detail_id_evaluacion">-</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Título</span>
                <span class="detail-value" id="detail_titulo_evaluacion">-</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Tipo</span>
                <span class="detail-value" id="detail_tipo_evaluacion">-</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Orden Cronológico</span>
                <span class="detail-value" id="detail_orden_evaluacion">-</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Descripción / Criterios</span>
                <span class="detail-value" id="detail_descripcion_evaluacion">-</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">ID Asignación Docente</span>
                <span class="detail-value" id="detail_id_instructor_curso">-</span>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="cerrarModal('modalDetalle')">Cerrar</button>
        </div>
    </div>
</div>
