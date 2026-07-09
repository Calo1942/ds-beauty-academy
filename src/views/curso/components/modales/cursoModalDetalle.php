<div id="modalDetalle" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Detalle del Curso</h2>
            <span class="close-modal" onclick="cerrarModal('modalDetalle')">&times;</span>
        </div>
        <div class="modal-body">
            <div class="detail-item">
                <span class="detail-label">ID:</span>
                <span id="detail_id_curso" class="detail-value"></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Nombre del Curso:</span>
                <span id="detail_nombre_curso" class="detail-value"></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Descripción:</span>
                <span id="detail_descripcion_curso" class="detail-value"></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Tipo:</span>
                <span id="detail_tipo_curso" class="detail-value"></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Precio Preventa:</span>
                <span id="detail_precio_preventa" class="detail-value"></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Precio Normal:</span>
                <span id="detail_precio_normal" class="detail-value"></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Fecha Fin Preventa:</span>
                <span id="detail_fecha_fin_preventa" class="detail-value"></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Fecha Inicio:</span>
                <span id="detail_fecha_inicio_curso" class="detail-value"></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Fecha Fin:</span>
                <span id="detail_fecha_fin_curso" class="detail-value"></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Límite de Cupos:</span>
                <span id="detail_limite_cupos" class="detail-value"></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Estatus:</span>
                <span id="detail_estatus_curso" class="detail-value"></span>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" onclick="cerrarModal('modalDetalle')">Cerrar</button>
        </div>
    </div>
</div>