<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clases | DS Beauty Academy</title>
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
            <h1 class="page-title">Gestión de Clases</h1>
            <div class="header-actions">
                <button class="btn btn-primary" onclick="abrirModal('modalCrear')">
                    <i class="fas fa-plus"></i> Registrar Clase
                </button>
            </div>
        </div>

        <?php include __DIR__ . '/components/claseDataTable.php'; ?>
    </main>
    </div>

    <!-- Modales -->
    <?php include __DIR__ . '/components/modales/claseModalCrear.php'; ?>
    <?php include __DIR__ . '/components/modales/claseModalEditar.php'; ?>
    <?php include __DIR__ . '/components/modales/claseModalDetalle.php'; ?>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            buscarTodos();
            document.getElementById('formCrearClase').addEventListener('submit', (e) => {
                e.preventDefault();
                guardarClase();
            });
            document.getElementById('formEditarClase').addEventListener('submit', (e) => {
                e.preventDefault();
                actualizarClase();
            });
            document.getElementById('searchClase').addEventListener('input', function() {
                const filter = this.value.toLowerCase();
                document.querySelectorAll('#claseTable tbody tr').forEach(row => {
                    row.style.display = row.innerText.toLowerCase().includes(filter) ? '' : 'none';
                });
            });
        });

        function abrirModal(id) {
            document.getElementById(id).classList.add('show');
        }

        function cerrarModal(id) {
            document.getElementById(id).classList.remove('show');
            if (id === 'modalCrear') document.getElementById('formCrearClase').reset();
            if (id === 'modalEditar') document.getElementById('formEditarClase').reset();
        }

        async function apiRequest(data) {
            try {
                const response = await fetch('?url=clase', {
                    method: 'POST',
                    body: data
                });
                return await response.json();
            } catch (error) {
                return {
                    status: 500,
                    message: 'Error de conexión con el servidor'
                };
            }
        }

        async function buscarTodos() {
            const formData = new FormData();
            formData.append('buscarTodos', 'true');
            const result = await apiRequest(formData);
            if (result.code === 200) renderTable(result.data);
        }

        function renderTable(data) {
            const tbody = document.querySelector('#claseTable tbody');
            tbody.innerHTML = '';
            data.forEach(clase => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${clase.id_clase}</td>
                    <td>${clase.titulo_clase}</td>
                    <td>${clase.orden_clase}</td>
                    <td>${clase.id_instructor_curso}</td>
                    <td>${clase.estatus_clase == 1 ? 'Activo' : 'Inactivo'}</td>
                    <td class="action-btns">
                        <button class="btn-action btn-view" onclick="verDetalle(${clase.id_clase})" title="Ver Detalle"><i class="fas fa-eye"></i></button>
                        <button class="btn-action btn-edit" onclick="editarClase(${clase.id_clase})" title="Editar"><i class="fas fa-pen"></i></button>
                        <button class="btn-action btn-delete" onclick="eliminarClase(${clase.id_clase})" title="Eliminar"><i class="fas fa-trash"></i></button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }

        async function guardarClase() {
            const formData = new FormData(document.getElementById('formCrearClase'));
            formData.append('guardar', 'true');
            const result = await apiRequest(formData);
            if (result.code === 201) {
                cerrarModal('modalCrear');
                buscarTodos();
                alert(result.message);
            } else alert('Error: ' + result.message);
        }

        async function editarClase(id) {
            const formData = new FormData();
            formData.append('buscar', id);
            const result = await apiRequest(formData);
            if (result.code === 200) {
                const c = result.data;
                document.getElementById('edit_id_clase').value = c.id_clase;
                ['titulo_clase', 'descripcion_clase', 'url_video_clase', 'orden_clase', 'id_instructor_curso', 'estatus_clase'].forEach(f => {
                    const el = document.querySelector(`#formEditarClase [name="${f}"]`);
                    if (el) el.value = c[f] || '';
                });
                abrirModal('modalEditar');
            }
        }

        async function actualizarClase() {
            const formData = new FormData(document.getElementById('formEditarClase'));
            formData.append('actualizar', 'true');
            const result = await apiRequest(formData);
            if (result.code === 200) {
                cerrarModal('modalEditar');
                buscarTodos();
                alert(result.message);
            } else alert('Error: ' + result.message);
        }

        async function eliminarClase(id) {
            if (!confirm('¿Está seguro de que desea eliminar esta clase?')) return;
            const formData = new FormData();
            formData.append('eliminar', id);
            const result = await apiRequest(formData);
            if (result.code === 200) {
                buscarTodos();
                alert(result.message);
            } else alert('Error: ' + result.message);
        }

        async function verDetalle(id) {
            const formData = new FormData();
            formData.append('buscar', id);
            const result = await apiRequest(formData);
            if (result.code === 200) {
                const c = result.data;
                document.getElementById('detail_id_clase').textContent = c.id_clase;
                document.getElementById('detail_titulo_clase').textContent = c.titulo_clase;
                document.getElementById('detail_descripcion_clase').textContent = c.descripcion_clase || 'N/A';
                document.getElementById('detail_url_video_clase').textContent = c.url_video_clase;
                document.getElementById('detail_orden_clase').textContent = c.orden_clase;
                document.getElementById('detail_id_instructor_curso').textContent = c.id_instructor_curso;
                document.getElementById('detail_estatus_clase').textContent = c.estatus_clase == 1 ? 'Activo' : 'Inactivo';
                abrirModal('modalDetalle');
            }
        }
    </script>
    <script src="src/assets/bootstrap-5.3.8/js/bootstrap.bundle.min.js"></script>
</body>

</html>