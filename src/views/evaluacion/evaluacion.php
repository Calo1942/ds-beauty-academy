<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evaluaciones | DS Beauty Academy</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="src/assets/bootstrap-5.3.8/css/bootstrap.min.css">
    <link rel="stylesheet" href="src/views/styles.css">
</head>

<body>
    <?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>
    <?php require_once __DIR__ . '/../layouts/navbar.php'; ?>

    <main class="content">
        <div class="page-header">
            <h1 class="page-title">Gestión de Evaluaciones</h1>
            <div class="header-actions">
                <button class="btn btn-primary" onclick="abrirModal('modalCrear')">
                    <i class="fas fa-plus"></i> Registrar Evaluación
                </button>
            </div>
        </div>

        <?php include __DIR__ . '/components/evaluacionDataTable.php'; ?>
    </main>
    </div>

    <!-- Modales -->
    <?php include __DIR__ . '/components/modales/evaluacionModalCrear.php'; ?>
    <?php include __DIR__ . '/components/modales/evaluacionModalEditar.php'; ?>
    <?php include __DIR__ . '/components/modales/evaluacionModalDetalle.php'; ?>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            buscarTodos();

            document.getElementById('formCrearEvaluacion').addEventListener('submit', (e) => {
                e.preventDefault();
                guardarEvaluacion();
            });

            document.getElementById('formEditarEvaluacion').addEventListener('submit', (e) => {
                e.preventDefault();
                actualizarEvaluacion();
            });

            document.getElementById('searchEvaluacion').addEventListener('input', function() {
                const filter = this.value.toLowerCase();
                const rows = document.querySelectorAll('#evaluacionTable tbody tr');
                rows.forEach(row => {
                    const text = row.innerText.toLowerCase();
                    row.style.display = text.includes(filter) ? '' : 'none';
                });
            });
        });

        function abrirModal(id) {
            document.getElementById(id).classList.add('show');
        }

        function cerrarModal(id) {
            document.getElementById(id).classList.remove('show');
            if (id === 'modalCrear') document.getElementById('formCrearEvaluacion').reset();
            if (id === 'modalEditar') document.getElementById('formEditarEvaluacion').reset();
        }

        async function apiRequest(data) {
            try {
                const response = await fetch('?url=evaluacion', {
                    method: 'POST',
                    body: data
                });
                return await response.json();
            } catch (error) {
                console.error('API Error:', error);
                return { status: 500, message: 'Error de conexión con el servidor' };
            }
        }

        async function buscarTodos() {
            const formData = new FormData();
            formData.append('buscarTodos', 'true');

            const result = await apiRequest(formData);
            if (result.code === 200) {
                renderTable(result.data);
            }
        }

        function renderTable(data) {
            const tbody = document.querySelector('#evaluacionTable tbody');
            tbody.innerHTML = '';

            data.forEach(item => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${item.id_evaluacion}</td>
                    <td><strong>${item.titulo_evaluacion}</strong></td>
                    <td><span class="badge ${item.tipo_evaluacion === 'practica' ? 'bg-info' : 'bg-secondary'}">${item.tipo_evaluacion}</span></td>
                    <td>Orden #${item.orden_evaluacion}</td>
                    <td>Instancia ID: ${item.id_instructor_curso}</td>
                    <td><span class="badge ${item.estatus_evaluacion == 1 ? 'bg-success' : 'bg-danger'}">${item.estatus_evaluacion == 1 ? 'Activa' : 'Inactiva'}</span></td>
                    <td class="action-btns">
                        <button class="btn-action btn-view" onclick="verDetalle(${item.id_evaluacion})" title="Ver Detalle">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn-action btn-edit" onclick="editarEvaluacion(${item.id_evaluacion})" title="Editar">
                            <i class="fas fa-pen"></i>
                        </button>
                        <button class="btn-action btn-delete" onclick="eliminarEvaluacion(${item.id_evaluacion})" title="Eliminar">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }

        async function guardarEvaluacion() {
            const form = document.getElementById('formCrearEvaluacion');
            const formData = new FormData(form);
            formData.append('guardar', 'true');

            const result = await apiRequest(formData);
            if (result.code === 201) {
                cerrarModal('modalCrear');
                buscarTodos();
                alert(result.message);
            } else {
                alert('Error: ' + result.message);
            }
        }

        async function editarEvaluacion(id) {
            const formData = new FormData();
            formData.append('buscar', id);

            const result = await apiRequest(formData);
            if (result.code === 200) {
                const item = result.data;
                document.getElementById('edit_id_evaluacion').value = item.id_evaluacion;

                const fields = ['id_instructor_curso', 'titulo_evaluacion', 'descripcion_evaluacion', 'orden_evaluacion', 'tipo_evaluacion', 'estatus_evaluacion'];
                fields.forEach(field => {
                    const input = document.querySelector(`#formEditarEvaluacion [name="${field}"]`);
                    if (input) input.value = item[field] || '';
                });

                abrirModal('modalEditar');
            }
        }

        async function actualizarEvaluacion() {
            const form = document.getElementById('formEditarEvaluacion');
            const formData = new FormData(form);
            formData.append('actualizar', 'true');

            const result = await apiRequest(formData);
            if (result.code === 200) {
                cerrarModal('modalEditar');
                buscarTodos();
                alert(result.message);
            } else {
                alert('Error: ' + result.message);
            }
        }

        async function eliminarEvaluacion(id) {
            if (!confirm('¿Está seguro de que desea eliminar esta evaluación?')) return;

            const formData = new FormData();
            formData.append('eliminar', id);

            const result = await apiRequest(formData);
            if (result.code === 200) {
                buscarTodos();
                alert(result.message);
            } else {
                alert('Error: ' + result.message);
            }
        }

        async function verDetalle(id) {
            const formData = new FormData();
            formData.append('buscar', id);

            const result = await apiRequest(formData);
            if (result.code === 200) {
                const item = result.data;
                document.getElementById('detail_id_evaluacion').textContent = item.id_evaluacion;
                document.getElementById('detail_titulo_evaluacion').textContent = item.titulo_evaluacion;
                document.getElementById('detail_tipo_evaluacion').textContent = item.tipo_evaluacion;
                document.getElementById('detail_orden_evaluacion').textContent = '#' + item.orden_evaluacion;
                document.getElementById('detail_descripcion_evaluacion').textContent = item.descripcion_evaluacion || 'Sin descripción';
                document.getElementById('detail_id_instructor_curso').textContent = item.id_instructor_curso;

                abrirModal('modalDetalle');
            }
        }
    </script>
    <script src="src/assets/bootstrap-5.3.8/js/bootstrap.bundle.min.js"></script>
</body>
</html>
