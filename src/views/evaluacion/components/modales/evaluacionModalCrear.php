<div id="modalCrear" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Registrar Evaluación</h2>
            <span class="close-modal" onclick="cerrarModal('modalCrear')">&times;</span>
        </div>
        <form id="formCrearEvaluacion">
            <div class="modal-body">
                <div class="form-group mb-3">
                    <label class="form-label">ID Instructor-Curso</label>
                    <input type="number" name="id_instructor_curso" class="form-control" placeholder="ID de asignación curso" required>
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">Título de la Evaluación</label>
                    <input type="text" name="titulo_evaluacion" class="form-control" placeholder="Ej. Práctica de Visajismo" required>
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">Descripción</label>
                    <textarea name="descripcion_evaluacion" class="form-control" placeholder="Indicaciones y criterios de evaluación"></textarea>
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">Orden Cronológico</label>
                    <input type="number" name="orden_evaluacion" class="form-control" placeholder="Ej. 1" required>
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
                <button type="button" class="btn btn-secondary" onclick="cerrarModal('modalCrear')">Cancelar</button>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </form>
    </div>
</div>
