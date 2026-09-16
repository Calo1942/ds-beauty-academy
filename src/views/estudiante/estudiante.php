<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estudiantes | DS Beauty Academy</title>
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
            <h1 class="page-title">Gestión de Estudiantes</h1>
            <div class="header-actions">
                <button class="btn btn-primary" onclick="abrirModal('modalCrear')">
                    <i class="fas fa-plus"></i> Registrar Estudiante
                </button>
            </div>
        </div>

        <?php include __DIR__ . '/components/estudianteDataTable.php'; ?>
    </main>
    </div>

    <!-- Modales -->
    <?php include __DIR__ . '/components/modales/estudianteModalCrear.php'; ?>
    <?php include __DIR__ . '/components/modales/estudianteModalEditar.php'; ?>
    <?php include __DIR__ . '/components/modales/estudianteModalDetalle.php'; ?>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            buscarTodos();

            document.getElementById('formCrearEstudiante').addEventListener('submit', (e) => {
                e.preventDefault();
                guardarEstudiante();
            });

            document.getElementById('formEditarEstudiante').addEventListener('submit', (e) => {
                e.preventDefault();
                actualizarEstudiante();
            });

            document.getElementById('searchEstudiante').addEventListener('input', function() {
                const filter = this.value.toLowerCase();
                const rows = document.querySelectorAll('#estudianteTable tbody tr');
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
            if (id === 'modalCrear') document.getElementById('formCrearEstudiante').reset();
            if (id === 'modalEditar') document.getElementById('formEditarEstudiante').reset();
        }

        async function apiRequest(data) {
            try {
                const response = await fetch('?url=estudiante', {
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
            const tbody = document.querySelector('#estudianteTable tbody');
            tbody.innerHTML = '';

            data.forEach(estudiante => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${estudiante.id_estudiante}</td>
                    <td>${estudiante.primer_nombre_estudiante} ${estudiante.primer_apellido_estudiante}</td>
                    <td>${estudiante.cedula_estudiante}</td>
                    <td>${estudiante.correo_estudiante}</td>
                    <td>${estudiante.usuario_instagram_estudiante || 'N/A'}</td>
                    <td class="action-btns">
                        <button class="btn-action btn-view" onclick="verDetalle(${estudiante.id_estudiante})" title="Ver Detalle">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn-action btn-edit" onclick="editarEstudiante(${estudiante.id_estudiante})" title="Editar">
                            <i class="fas fa-pen"></i>
                        </button>
                        <button class="btn-action btn-delete" onclick="eliminarEstudiante(${estudiante.id_estudiante})" title="Eliminar">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }

        async function guardarEstudiante() {
            const form = document.getElementById('formCrearEstudiante');
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

        async function editarEstudiante(id) {
            const formData = new FormData();
            formData.append('buscar', id);

            const result = await apiRequest(formData);
            if (result.code === 200) {
                const estudiante = result.data;
                document.getElementById('edit_id_estudiante').value = estudiante.id_estudiante;

                const fields = [
                    'primer_nombre_estudiante', 'segundo_nombre_estudiante',
                    'primer_apellido_estudiante', 'segundo_apellido_estudiante',
                    'cedula_estudiante', 'correo_estudiante',
                    'telefono_principal_estudiante', 'telefono_alternativo_estudiante',
                    'usuario_instagram_estudiante'
                ];

                fields.forEach(field => {
                    const input = document.querySelector(`#formEditarEstudiante [name="${field}"]`);
                    if (input) input.value = estudiante[field] || '';
                });

                abrirModal('modalEditar');
            }
        }

        async function actualizarEstudiante() {
            const form = document.getElementById('formEditarEstudiante');
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

        async function eliminarEstudiante(id) {
            if (!confirm('¿Está seguro de que desea eliminar este estudiante?')) return;

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
                const estudiante = result.data;
                document.getElementById('detail_id_estudiante').textContent = estudiante.id_estudiante;
                document.getElementById('detail_id_estudiante_badge').textContent = estudiante.id_estudiante;
                const nombreCompleto = `${estudiante.primer_nombre_estudiante} ${estudiante.segundo_nombre_estudiante || ''} ${estudiante.primer_apellido_estudiante} ${estudiante.segundo_apellido_estudiante || ''}`;
                document.getElementById('detail_nombre_completo_header').textContent = nombreCompleto.toLowerCase();
                document.getElementById('detail_cedula').textContent = estudiante.cedula_estudiante;
                document.getElementById('detail_correo').textContent = estudiante.correo_estudiante;
                document.getElementById('detail_telefono').textContent = estudiante.telefono_principal_estudiante;
                document.getElementById('detail_telefono_alternativo').textContent = estudiante.telefono_alternativo_estudiante || 'N/A';
                document.getElementById('detail_instagram').textContent = estudiante.usuario_instagram_estudiante || 'N/A';

                abrirModal('modalDetalle');
            }
        }
    </script>
    <script src="src/assets/bootstrap-5.3.8/js/bootstrap.bundle.min.js"></script>
</body>

</html>