<div id="modalEditar" class="modal">
    <div class="modal-content" style="max-width: 600px;">
        <div class="modal-header">
            <h2 class="modal-title">Editar Diploma</h2>
            <span class="close-modal" onclick="cerrarModal('modalEditar')">&times;</span>
        </div>
        <form id="formEditarDiploma" enctype="multipart/form-data">
            <input type="hidden" name="id_diploma" id="edit_id_diploma">

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
                    <div class="form-group">
                        <label class="form-label">Título del Diploma</label>
                        <input type="text" name="nombre_diploma" class="form-control" maxlength="255" required onfocus="this.select()">
                    </div>
                    <div class="form-group mb-3">
                        <label for="descripcion_diploma_editar" class="form-label">Descripción</label>
                        <textarea name="descripcion_diploma" id="descripcion_diploma_editar" class="form-control" rows="3" onfocus="this.select()"></textarea>
                    </div>
                    <div class="form-group mb-3">
                        <label for="categoria_diploma_editar" class="form-label">Categoría <span class="text-danger">*</span></label>
                        <input type="text" name="categoria_diploma" id="categoria_diploma_editar" class="form-control" maxlength="100" required onfocus="this.select()">
                    </div>
                    <div class="form-group mb-3">
                        <label for="id_instructor_editar" class="form-label">Instructor Responsable <span class="text-danger">*</span></label>
                        <select name="id_instructor" id="id_instructor_editar" class="form-control" required>
                            <option value="">-- Seleccione un instructor --</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="cerrarModal('modalEditar')">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Guardar Cambios
                    </button>
                </div>
        </form>
    </div>
</div>