<div id="modalDetalle" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Detalle del Recurso</h2>
            <span class="close-modal" onclick="cerrarModal('modalDetalle')">&times;</span>
        </div>
        
        <div class="modal-body">
            <!-- Skeleton Loader tipo tabla para el modal -->
            <div class="skeleton-modal-table skeleton-table" style="display: none;">
                <div class="skeleton-header">
                    <div class="skeleton-header-cell" style="flex: 1;"></div>
                    <div class="skeleton-header-cell" style="flex: 2;"></div>
                </div>
                <div class="skeleton-row">
                    <div class="skeleton-cell" style="flex: 1;"></div>
                    <div class="skeleton-cell" style="flex: 2;"></div>
                </div>
                <div class="skeleton-row">
                    <div class="skeleton-cell" style="flex: 1;"></div>
                    <div class="skeleton-cell" style="flex: 2;"></div>
                </div>
                <div class="skeleton-row">
                    <div class="skeleton-cell" style="flex: 1;"></div>
                    <div class="skeleton-cell" style="flex: 2;"></div>
                </div>
                <div class="skeleton-row">
                    <div class="skeleton-cell" style="flex: 1;"></div>
                    <div class="skeleton-cell" style="flex: 2;"></div>
                </div>
            </div>
            <div class="modal-body-content">
                <div class="detail-item">
                <span class="detail-label">ID del Recurso</span>
                <span class="detail-value" id="detail_id_recurso">-</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Nombre del Recurso</span>
                <span class="detail-value" id="detail_nombre_recurso">-</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Descripción</span>
                <span class="detail-value" id="detail_descripcion_recurso">-</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">URL del Archivo</span>
                <span class="detail-value" id="detail_url_archivo_recurso">-</span>
            </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="cerrarModal('modalDetalle')">Cerrar</button>
        </div>
    </div>
</div>
