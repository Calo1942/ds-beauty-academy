<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instructores | DS Beauty Academy</title>
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
            <h1 class="page-title">Gestión de Instructores</h1>
            <div class="header-actions">
                <button class="btn btn-primary" onclick="abrirModal('modalCrear')">
                    <i class="fas fa-plus"></i> Registrar Instructor
                </button>
            </div>
        </div>

        <?php include __DIR__ . '/components/instructorDataTable.php'; ?>
    </main>

    <!-- Modales -->
    <?php include __DIR__ . '/components/modales/instructorModalCrear.php'; ?>
    <?php include __DIR__ . '/components/modales/instructorModalEditar.php'; ?>
    <?php include __DIR__ . '/components/modales/instructorModalDetalle.php'; ?>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            buscarTodos();

            document.getElementById('formCrearInstructor').addEventListener('submit', (e) => {
                e.preventDefault();
                guardarInstructor();
            });

            document.getElementById('formEditarInstructor').addEventListener('submit', (e) => {
                e.preventDefault();
                actualizarInstructor();
            });

            document.getElementById('searchInstructor').addEventListener('input', function() {
                const filter = this.value.toLowerCase();
                const rows = document.querySelectorAll('#instructorTable tbody tr');
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
            if (id === 'modalCrear') document.getElementById('formCrearInstructor').reset();
            if (id === 'modalEditar') document.getElementById('formEditarInstructor').reset();
        }

        async function apiRequest(data) {
            try {
                const response = await fetch('?url=instructor', {
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
            const tbody = document.querySelector('#instructorTable tbody');
            tbody.innerHTML = '';

            data.forEach(instructor => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${instructor.id_instructor}</td>
                    <td>${instructor.primer_nombre_instructor} ${instructor.primer_apellido_instructor}</td>
                    <td>${instructor.cedula_instructor}</td>
                    <td>${instructor.correo_instructor}</td>
                    <td>${instructor.usuario_instagram_instructor || 'N/A'}</td>
                    <td class="action-btns">
                        <button class="btn-action btn-view" onclick="verDetalle(${instructor.id_instructor})" title="Ver Detalle">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn-action btn-edit" onclick="editarInstructor(${instructor.id_instructor})" title="Editar">
                            <i class="fas fa-pen"></i>
                        </button>
                        <button class="btn-action btn-delete" onclick="eliminarInstructor(${instructor.id_instructor})" title="Eliminar">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }

        async function guardarInstructor() {
            const form = document.getElementById('formCrearInstructor');
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

        async function editarInstructor(id) {
            const formData = new FormData();
            formData.append('buscar', id);

            const result = await apiRequest(formData);
            if (result.code === 200) {
                const instructor = result.data;
                document.getElementById('edit_id_instructor').value = instructor.id_instructor;

                // Llenar campos del formulario
                const fields = [
                    'primer_nombre_instructor', 'segundo_nombre_instructor',
                    'primer_apellido_instructor', 'segundo_apellido_instructor',
                    'cedula_instructor', 'correo_instructor',
                    'telefono_principal_instructor', 'telefono_alternativo_instructor',
                    'usuario_instagram_instructor'
                ];

                fields.forEach(field => {
                    const input = document.querySelector(`#formEditarInstructor [name="${field}"]`);
                    if (input) input.value = instructor[field] || '';
                });

                abrirModal('modalEditar');
            }
        }

        async function actualizarInstructor() {
            const form = document.getElementById('formEditarInstructor');
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

        async function eliminarInstructor(id) {
            if (!confirm('¿Está seguro de que desea eliminar este instructor?')) return;

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
                const instructor = result.data;
                document.getElementById('detail_id_instructor').textContent = instructor.id_instructor;
                document.getElementById('detail_id_instructor_badge').textContent = instructor.id_instructor;
                const nombreCompleto = `${instructor.primer_nombre_instructor} ${instructor.segundo_nombre_instructor || ''} ${instructor.primer_apellido_instructor} ${instructor.segundo_apellido_instructor || ''}`;
                document.getElementById('detail_nombre_completo_header').textContent = nombreCompleto.toLowerCase();
                document.getElementById('detail_cedula').textContent = instructor.cedula_instructor;
                document.getElementById('detail_correo').textContent = instructor.correo_instructor;
                document.getElementById('detail_telefono').textContent = instructor.telefono_principal_instructor;
                document.getElementById('detail_instagram').textContent = instructor.usuario_instagram_instructor || 'N/A';

                abrirModal('modalDetalle');
            }
        }
    </script>
    <script src="src/assets/bootstrap-5.3.8/js/bootstrap.bundle.min.js"></script>
</body>

</html>