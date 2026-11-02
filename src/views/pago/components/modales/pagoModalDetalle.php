<div id="modalDetalle" class="modal">
    <div class="modal-content modal-lg">
        <div class="modal-header">
            <h2 class="modal-title">Detalle del Comprobante de Pago</h2>
            <span class="close-modal" onclick="cerrarModal('modalDetalle')">&times;</span>
        </div>
        <div class="modal-body">
            <div class="row g-3">
                <!-- Información Financiera -->
                <div class="col-md-6">
                    <h5 class="text-accent mb-3 border-bottom pb-2" style="color: var(--accent); font-size: 1rem;">
                        <i class="fas fa-receipt me-2"></i>Datos de la Transacción
                    </h5>
                    <div class="detail-item mb-2">
                        <span class="detail-label text-muted">ID Comprobante:</span>
                        <span id="detail_id_comprobante" class="detail-value fw-bold text-white"></span>
                    </div>
                    <div class="detail-item mb-2">
                        <span class="detail-label text-muted">Referencia Bancaria:</span>
                        <span id="detail_referencia" class="detail-value font-monospace fw-bold text-light"></span>
                    </div>
                    <div class="detail-item mb-2">
                        <span class="detail-label text-muted">Banco Receptor:</span>
                        <span id="detail_banco" class="detail-value text-white"></span>
                    </div>
                    <div class="detail-item mb-2">
                        <span class="detail-label text-muted">Monto Reportado:</span>
                        <span id="detail_monto" class="detail-value fw-bold" style="color: var(--accent); font-size: 1.1rem;"></span>
                    </div>
                    <div class="detail-item mb-2">
                        <span class="detail-label text-muted">Fecha del Pago:</span>
                        <span id="detail_fecha_pago" class="detail-value text-white"></span>
                    </div>
                    <div class="detail-item mb-2">
                        <span class="detail-label text-muted">Estado del Pago:</span>
                        <span id="detail_estatus_pago" class="detail-value"></span>
                    </div>
                </div>

                <!-- Información Académica -->
                <div class="col-md-6">
                    <h5 class="text-accent mb-3 border-bottom pb-2" style="color: var(--accent); font-size: 1rem;">
                        <i class="fas fa-user-graduate me-2"></i>Matrícula y Estudiante
                    </h5>
                    <div class="detail-item mb-2">
                        <span class="detail-label text-muted">Estudiante:</span>
                        <span id="detail_nombre_estudiante" class="detail-value text-white fw-bold"></span>
                    </div>
                    <div class="detail-item mb-2">
                        <span class="detail-label text-muted">Cédula:</span>
                        <span id="detail_cedula_estudiante" class="detail-value text-white"></span>
                    </div>
                    <div class="detail-item mb-2">
                        <span class="detail-label text-muted">Curso Asociado:</span>
                        <span id="detail_nombre_curso" class="detail-value text-white"></span>
                    </div>
                    <div class="detail-item mb-2">
                        <span class="detail-label text-muted">Precio Acordado:</span>
                        <span id="detail_precio_acordado" class="detail-value text-white"></span>
                    </div>
                    <div class="detail-item mb-2">
                        <span class="detail-label text-muted">Estatus Matrícula:</span>
                        <span id="detail_estatus_matricula" class="detail-value"></span>
                    </div>
                    <div class="detail-item mb-2">
                        <span class="detail-label text-muted">Cupo Matrícula:</span>
                        <span id="detail_estatus_cupo" class="detail-value"></span>
                    </div>
                </div>

                <!-- Comprobante Adjunto -->
                <div class="col-12 mt-3 pt-3 border-top">
                    <h5 class="text-accent mb-2" style="color: var(--accent); font-size: 1rem;">
                        <i class="fas fa-paperclip me-2"></i>Comprobante Digital Adjunto
                    </h5>
                    <div id="detail_comprobante_adjunto" class="p-2 text-center rounded bg-dark border border-secondary">
                        <!-- Generado dinámicamente: vista previa o enlace de descarga -->
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer d-flex justify-content-between">
            <div id="detail_action_buttons" class="d-flex gap-2">
                <!-- Botones rápidos de conciliación se insertan aquí -->
            </div>
            <button type="button" class="btn btn-secondary" onclick="cerrarModal('modalDetalle')">Cerrar</button>
        </div>
    </div>
</div>
