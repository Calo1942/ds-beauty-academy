<div id="modalCrear" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Registrar Nueva Matrícula</h2>
            <span class="close-modal" onclick="cerrarModal('modalCrear')">&times;</span>
        </div>
        <form id="formCrearMatricula">
            <div class="modal-body">
                <div class="form-group mb-3">
                    <label class="form-label">Estudiante <span class="text-danger">*</span></label>
                    <select name="id_estudiante" id="select_estudiante_matricula" class="form-control" required>
                        <option value="">-- Cargando estudiantes... --</option>
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label class="form-label">Curso e Instructor Asignado <span class="text-danger">*</span></label>
                    <select name="id_instructor_curso" id="select_instructor_curso_matricula" class="form-control" required>
                        <option value="">-- Cargando oferta académica... --</option>
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label class="form-label">Precio Acordado ($ USD) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" min="0" name="precio_acordado" id="precio_acordado_matricula" class="form-control" placeholder="0.00" required>
                    <small class="form-text text-muted">Se sugiere el precio referencial o de preventa, pero puede ajustarse para promociones especiales.</small>
                </div>

                <div class="form-group mb-3">
                    <label class="form-label">Estatus del Cupo <span class="text-danger">*</span></label>
                    <select name="estatus_cupo" id="estatus_cupo_matricula" class="form-control" required>
                        <option value="Reservado">Reservado (Pendiente de pago)</option>
                        <option value="Confirmado">Confirmado (Cupo asegurado)</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="cerrarModal('modalCrear')">Cancelar</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i>Registrar Matrícula
                </button>
            </div>
        </form>
    </div>
</div>
