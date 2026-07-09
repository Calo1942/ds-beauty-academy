<div id="modalCrear" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Registrar Curso</h2>
            <span class="close-modal" onclick="cerrarModal('modalCrear')">&times;</span>
        </div>
        <form id="formCrearCurso">
            <div class="modal-body">
                <div class="form-group mb-3">
                    <label for="nombre_curso" class="form-label">Nombre del Curso</label>
                    <input type="text" name="nombre_curso" class="form-control" placeholder="Ingrese el nombre del curso" required>
                </div>
                <div class="form-group mb-3">
                    <label for="descripcion_curso" class="form-label">Descripción</label>
                    <textarea name="descripcion_curso" class="form-control" placeholder="Ingrese la descripción del curso"></textarea>
                </div>
                <div class="form-group mb-3">
                    <label for="tipo_curso" class="form-label">Tipo de Curso</label>
                    <select name="tipo_curso" class="form-control" required>
                        <option value="Masterclass">Masterclass</option>
                        <option value="Curso completo">Curso completo</option>
                    </select>
                </div>
                <div class="form-group mb-3">
                    <label for="precio_preventa" class="form-label">Precio Preventa</label>
                    <input type="number" step="0.01" name="precio_preventa" class="form-control" placeholder="Ingrese el precio de preventa (opcional)">
                </div>
                <div class="form-group mb-3">
                    <label for="precio_normal" class="form-label">Precio Normal</label>
                    <input type="number" step="0.01" name="precio_normal" class="form-control" placeholder="Ingrese el precio normal" required>
                </div>
                <div class="form-group mb-3">
                    <label for="fecha_fin_preventa" class="form-label">Fecha Fin Preventa</label>
                    <input type="datetime-local" name="fecha_fin_preventa" class="form-control">
                </div>
                <div class="form-group mb-3">
                    <label for="fecha_inicio_curso" class="form-label">Fecha Inicio Curso</label>
                    <input type="datetime-local" name="fecha_inicio_curso" class="form-control" required>
                </div>
                <div class="form-group mb-3">
                    <label for="fecha_fin_curso" class="form-label">Fecha Fin Curso</label>
                    <input type="datetime-local" name="fecha_fin_curso" class="form-control">
                </div>
                <div class="form-group mb-3">
                    <label for="limite_cupos" class="form-label">Límite de Cupos</label>
                    <input type="number" name="limite_cupos" class="form-control" placeholder="Ingrese el límite de cupos" required>
                </div>
                <div class="form-group mb-3">
                    <label for="estatus_curso" class="form-label">Estatus</label>
                    <select name="estatus_curso" class="form-control" required>
                        <option value="Borrador">Borrador</option>
                        <option value="Preventa">Preventa</option>
                        <option value="Venta Normal">Venta Normal</option>
                        <option value="Cerrado">Cerrado</option>
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