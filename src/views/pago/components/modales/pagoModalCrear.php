<div id="modalCrear" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Registrar Pago Manual</h2>
            <span class="close-modal" onclick="cerrarModal('modalCrear')">&times;</span>
        </div>
        <form id="formCrearPago" enctype="multipart/form-data">
            <div class="modal-body">
                <div class="form-group mb-3">
                    <label class="form-label">Matrícula del Estudiante <span class="text-danger">*</span></label>
                    <select name="id_matricula" id="select_matricula_pago" class="form-control" required>
                        <option value="">-- Cargando matrículas... --</option>
                    </select>
                    <small class="form-text text-muted">Seleccione la matrícula asociada a la alumna y curso.</small>
                </div>

                <div class="form-group mb-3">
                    <label class="form-label">Banco Receptor <span class="text-danger">*</span></label>
                    <select name="id_banco" id="select_banco_pago" class="form-control" required>
                        <option value="">-- Cargando bancos... --</option>
                    </select>
                </div>

                <div class="row">
                    <div class="col-md-6 form-group mb-3">
                        <label class="form-label">Referencia Bancaria <span class="text-danger">*</span></label>
                        <input type="text" name="referencia_transaccion" id="referencia_pago" class="form-control" placeholder="Ej. REF-987654" maxlength="150" required>
                    </div>
                    <div class="col-md-6 form-group mb-3">
                        <label class="form-label">Monto Cancelado ($ USD) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" min="0.01" name="monto" id="monto_pago" class="form-control" placeholder="0.00" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 form-group mb-3">
                        <label class="form-label">Fecha de la Operación <span class="text-danger">*</span></label>
                        <input type="date" name="fecha_pago" id="fecha_pago" class="form-control" required>
                    </div>
                    <div class="col-md-6 form-group mb-3">
                        <label class="form-label">Estatus del Pago <span class="text-danger">*</span></label>
                        <select name="estatus_pago" id="estatus_pago_crear" class="form-control" required>
                            <option value="Pendiente">Pendiente (Por conciliar)</option>
                            <option value="Verificado">Verificado (Pago confirmado)</option>
                        </select>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label class="form-label">Comprobante Digital (Captura o PDF)</label>
                    <input type="file" name="comprobante_archivo" id="comprobante_archivo" class="form-control" accept="image/jpeg,image/png,image/webp,application/pdf">
                    <small class="form-text text-muted">Formatos admitidos: JPG, PNG, WEBP, PDF (Máx. 5 MB).</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="cerrarModal('modalCrear')">Cancelar</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i>Registrar Pago
                </button>
            </div>
        </form>
    </div>
</div>
