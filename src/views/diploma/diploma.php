<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diplomas | DS Beauty Academy</title>
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
            <h1 class="page-title">Gestión de Diplomas</h1>
            <div class="header-actions">
                <button class="btn btn-primary" onclick="abrirModalCrear()">
                    <i class="fas fa-plus"></i> Registrar Diploma
                </button>
            </div>
        </div>

        <?php include __DIR__ . '/components/diplomaDataTable.php'; ?>
    </main>

    <!-- Modales -->
    <?php include __DIR__ . '/components/modales/diplomaModalCrear.php'; ?>
    <?php include __DIR__ . '/components/modales/diplomaModalEditar.php'; ?>
    <?php include __DIR__ . '/components/modales/diplomaModalDetalle.php'; ?>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            buscarTodos();

            document.getElementById('formCrearDiploma').addEventListener('submit', (e) => {
                e.preventDefault();
                guardarDiploma();
            });

            document.getElementById('formEditarDiploma').addEventListener('submit', (e) => {
                e.preventDefault();
                actualizarDiploma();
            });

            document.getElementById('searchDiploma').addEventListener('input', function() {
                const filter = this.value.toLowerCase();
                const rows = document.querySelectorAll('#diplomaTable tbody tr');
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
            if (id === 'modalCrear') document.getElementById('formCrearDiploma').reset();
            if (id === 'modalEditar') document.getElementById('formEditarDiploma').reset();
        }

        async function apiRequest(url, data) {
            try {
                const response = await fetch(url, {
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

            const result = await apiRequest('?url=diploma', formData);
            if (result.code === 200) {
                renderTable(result.data);
            }
        }

        function renderTable(data) {
            const tbody = document.querySelector('#diplomaTable tbody');
            tbody.innerHTML = '';

            data.forEach(diploma => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${diploma.id_diploma}</td>
                    <td>${diploma.nombre_diploma}</td>
                    <td>${diploma.categoria_diploma}</td>
                    <td>${diploma.nombre_instructor}</td>
                    <td class="action-btns">
                        <button class="btn-action btn-view" onclick="verDetalle(${diploma.id_diploma})" title="Ver Detalle">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn-action btn-edit" onclick="editarDiploma(${diploma.id_diploma})" title="Editar">
                            <i class="fas fa-pen"></i>
                        </button>
                        <button class="btn-action btn-delete" onclick="eliminarDiploma(${diploma.id_diploma})" title="Eliminar">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }

        async function cargarInstructores(selectId) {
            const formData = new FormData();
            formData.append('buscarTodos', 'true');
            const result = await apiRequest('?url=instructor', formData);

            if (result.code === 200) {
                const select = document.getElementById(selectId);
                select.innerHTML = '<option value="">Seleccione un instructor</option>';
                result.data.forEach(instructor => {
                    const option = document.createElement('option');
                    option.value = instructor.id_instructor;
                    option.textContent = `${instructor.primer_nombre_instructor} ${instructor.primer_apellido_instructor}`;
                    select.appendChild(option);
                });
            }
        }

        async function abrirModalCrear() {
            await cargarInstructores('id_instructor_crear');
            abrirModal('modalCrear');
        }

        async function guardarDiploma() {
            const form = document.getElementById('formCrearDiploma');
            const formData = new FormData(form);
            formData.append('guardar', 'true');

            const result = await apiRequest('?url=diploma', formData);
            if (result.code === 201) {
                cerrarModal('modalCrear');
                buscarTodos();
                alert(result.message);
            } else {
                alert('Error: ' + result.message);
            }
        }

        async function editarDiploma(id) {
            const formData = new FormData();
            formData.append('buscar', id);

            const result = await apiRequest('?url=diploma', formData);
            if (result.code === 200) {
                const diploma = result.data;
                await cargarInstructores('id_instructor_editar');

                document.getElementById('edit_id_diploma').value = diploma.id_diploma;
                document.querySelector('#formEditarDiploma [name="nombre_diploma"]').value = diploma.nombre_diploma;
                document.querySelector('#formEditarDiploma [name="descripcion_diploma"]').value = diploma.descripcion_diploma;
                document.querySelector('#formEditarDiploma [name="categoria_diploma"]').value = diploma.categoria_diploma;
                document.querySelector('#formEditarDiploma [name="url_pdf_diploma"]').value = diploma.url_pdf_diploma;
                document.querySelector('#formEditarDiploma [name="id_instructor"]').value = diploma.id_instructor;

                abrirModal('modalEditar');
            }
        }

        async function actualizarDiploma() {
            const form = document.getElementById('formEditarDiploma');
            const formData = new FormData(form);
            formData.append('actualizar', 'true');

            const result = await apiRequest('?url=diploma', formData);
            if (result.code === 200) {
                cerrarModal('modalEditar');
                buscarTodos();
                alert(result.message);
            } else {
                alert('Error: ' + result.message);
            }
        }

        async function eliminarDiploma(id) {
            if (!confirm('¿Está seguro de que desea eliminar este diploma?')) return;

            const formData = new FormData();
            formData.append('eliminar', id);

            const result = await apiRequest('?url=diploma', formData);
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

            const result = await apiRequest('?url=diploma', formData);
            if (result.code === 200) {
                const diploma = result.data;
                document.getElementById('detail_id_diploma').textContent = diploma.id_diploma;
                document.getElementById('detail_titulo').textContent = diploma.nombre_diploma;
                document.getElementById('detail_descripcion').textContent = diploma.descripcion_diploma || 'N/A';
                document.getElementById('detail_categoria').textContent = diploma.categoria_diploma;
                document.getElementById('detail_instructor').textContent = diploma.nombre_instructor || diploma.id_instructor;

                abrirModal('modalDetalle');
            }
        }
    </script>
    <script src="src/assets/bootstrap-5.3.8/js/bootstrap.bundle.min.js"></script>
</body>

</html>