<!-- Modal Detalle Rol -->
<div id="modalDetalle" class="modal">
    <div class="modal-content" style="max-width: 520px;">
        <div class="modal-header">
            <h2 class="modal-title">Detalles del Rol</h2>
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
            </div>
            
            <div class="modal-body-content">
                <div class="detail-item">
                    <span class="detail-label">ID de Rol:</span>
                    <span class="detail-value fw-bold" id="detail_id_rol">-</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Nombre del Rol:</span>
                    <span class="detail-value fw-semibold" id="detail_nombre_rol">-</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Descripción:</span>
                    <span class="detail-value" id="detail_descripcion_rol">-</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Usuarios con este Rol:</span>
                    <span class="detail-value" id="detail_total_usuarios">-</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Estado:</span>
                    <span class="detail-value" id="detail_estatus_rol">-</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Fecha de Creación:</span>
                    <span class="detail-value text-muted" id="detail_fecha_creacion">-</span>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="cerrarModal('modalDetalle')">Cerrar</button>
        </div>
    </div>
</div>
