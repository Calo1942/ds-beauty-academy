<div id="modalEditar" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Editar Evaluación</h2>
            <span class="close-modal" onclick="cerrarModal('modalEditar')">&times;</span>
        </div>
        <form id="formEditarEvaluacion">
            <input type="hidden" id="edit_id_evaluacion" name="id_evaluacion">
            <div class="modal-body">
                <div class="form-group mb-3">
                    <label class="form-label">ID Instructor-Curso</label>
                    <input type="number" name="id_instructor_curso" class="form-control" required>
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">Título de la Evaluación</label>
                    <input type="text" name="titulo_evaluacion" class="form-control" required>
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">Descripción</label>
                    <textarea name="descripcion_evaluacion" class="form-control"></textarea>
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">Orden Cronológico</label>
                    <input type="number" name="orden_evaluacion" class="form-control" required>
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">Tipo de Evaluación</label>
                    <select name="tipo_evaluacion" class="form-control" required>
                        <option value="practica">Práctica</option>
                        <option value="teorica">Teórica</option>
                    </select>
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">Estatus</label>
                    <select name="estatus_evaluacion" class="form-control" required>
                        <option value="1">Activa</option>
                        <option value="0">Inactiva</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="cerrarModal('modalEditar')">Cancelar</button>
                <button type="submit" class="btn btn-primary">Actualizar</button>
            </div>
        </form>
    </div>
</div>
