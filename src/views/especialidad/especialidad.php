<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Especialidades | DS Beauty Academy</title>
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
            <h1 class="page-title">Gestión de Especialidades</h1>
            <div class="header-actions">
                <button class="btn btn-primary" onclick="abrirModal('modalCrear')">
                    <i class="fas fa-plus"></i> Registrar Especialidad
                </button>
            </div>
        </div>

        <?php include __DIR__ . '/components/especialidadesDataTable.php'; ?>
    </main>
    </div>

    <!-- Modales -->
    <?php include __DIR__ . '/components/modales/especialidadesModalCrear.php'; ?>
    <?php include __DIR__ . '/components/modales/especialidadesModalEditar.php'; ?>
    <?php include __DIR__ . '/components/modales/especialidadesModalDetalle.php'; ?>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            buscarTodos();

            document.getElementById('formCrearEspecialidad').addEventListener('submit', (e) => {
                e.preventDefault();
                guardarEspecialidad();
            });

            document.getElementById('formEditarEspecialidad').addEventListener('submit', (e) => {
                e.preventDefault();
                actualizarEspecialidad();
            });

            document.getElementById('searchEspecialidad').addEventListener('input', function() {
                const filter = this.value.toLowerCase();
                const rows = document.querySelectorAll('#especialidadTable tbody tr');
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
            if (id === 'modalCrear') document.getElementById('formCrearEspecialidad').reset();
            if (id === 'modalEditar') document.getElementById('formEditarEspecialidad').reset();
        }

        async function apiRequest(data) {
            try {
                const response = await fetch('?url=especialidad', {
                    method: 'POST',
                    body: data
                });
                return await response.json();
            } catch (error) {
                console.error('API Error:', error);
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
            if (result.code === 200) {
                renderTable(result.data);
            }
        }

        function renderTable(data) {
            const tbody = document.querySelector('#especialidadTable tbody');
            tbody.innerHTML = '';

            data.forEach(especialidad => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${especialidad.id_especialidad}</td>
                    <td>${especialidad.nombre_especialidad}</td>
                    <td class="action-btns">
                        <button class="btn-action btn-view" onclick="verDetalle(${especialidad.id_especialidad})" title="Ver Detalle">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn-action btn-edit" onclick="editarEspecialidad(${especialidad.id_especialidad})" title="Editar">
                            <i class="fas fa-pen"></i>
                        </button>
                        <button class="btn-action btn-delete" onclick="eliminarEspecialidad(${especialidad.id_especialidad})" title="Eliminar">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }

        async function guardarEspecialidad() {
            const form = document.getElementById('formCrearEspecialidad');
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

        async function editarEspecialidad(id) {
            const formData = new FormData();
            formData.append('buscar', id);

            const result = await apiRequest(formData);
            if (result.code === 200) {
                const especialidad = result.data;
                document.getElementById('edit_id_especialidad').value = especialidad.id_especialidad;
                document.querySelector('#formEditarEspecialidad [name="nombre_especialidad"]').value = especialidad.nombre_especialidad;
                abrirModal('modalEditar');
            }
        }

        async function actualizarEspecialidad() {
            const form = document.getElementById('formEditarEspecialidad');
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

        async function eliminarEspecialidad(id) {
            if (!confirm('¿Está seguro de que desea eliminar esta especialidad?')) return;

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
                const especialidad = result.data;
                document.getElementById('detail_id_especialidad').textContent = especialidad.id_especialidad;
                document.getElementById('detail_nombre_especialidad').textContent = especialidad.nombre_especialidad;
                abrirModal('modalDetalle');
            }
        }
    </script>
    <script src="src/assets/bootstrap-5.3.8/js/bootstrap.bundle.min.js"></script>
</body>

</html>