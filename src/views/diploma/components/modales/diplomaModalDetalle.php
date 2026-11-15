<div id="modalDetalle" class="modal">
    <div class="modal-content" style="max-width: 600px;">
        <div class="modal-header">
            <h2 class="modal-title">Detalle del Diploma</h2>
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
                    <span class="detail-label">ID:</span>
                    <span id="detail_id_diploma" class="detail-value"></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Título:</span>
                    <span id="detail_nombre_diploma" class="detail-value fw-bold"></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Descripción:</span>
                    <span id="detail_descripcion_diploma" class="detail-value"></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Categoría:</span>
                    <span id="detail_categoria_diploma" class="detail-value"></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Instructor Responsable:</span>
                    <span id="detail_instructor" class="detail-value"></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Documento Adjunto:</span>
                    <span id="detail_url_pdf_diploma" class="detail-value"></span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="cerrarModal('modalDetalle')">Cerrar</button>
            </div>
        </div>
    </div>