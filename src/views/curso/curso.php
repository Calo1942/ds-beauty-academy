<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cursos | DS Beauty Academy</title>
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
            <h1 class="page-title">Gestión de Cursos</h1>
            <div class="header-actions">
                <button class="btn btn-primary" onclick="abrirModal('modalCrear')">
                    <i class="fas fa-plus"></i> Registrar Curso
                </button>
            </div>
        </div>

        <?php include __DIR__ . '/components/cursoDataTable.php'; ?>
    </main>
    </div>

    <!-- Modales -->
    <?php include __DIR__ . '/components/modales/cursoModalCrear.php'; ?>
    <?php include __DIR__ . '/components/modales/cursoModalEditar.php'; ?>
    <?php include __DIR__ . '/components/modales/cursoModalDetalle.php'; ?>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            buscarTodos();

            document.getElementById('formCrearCurso').addEventListener('submit', (e) => {
                e.preventDefault();
                guardarCurso();
            });

            document.getElementById('formEditarCurso').addEventListener('submit', (e) => {
                e.preventDefault();
                actualizarCurso();
            });

            document.getElementById('searchCurso').addEventListener('input', function() {
                const filter = this.value.toLowerCase();
                const rows = document.querySelectorAll('#cursoTable tbody tr');
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
            if (id === 'modalCrear') document.getElementById('formCrearCurso').reset();
            if (id === 'modalEditar') document.getElementById('formEditarCurso').reset();
        }

        async function apiRequest(data) {
            try {
                const response = await fetch('?url=curso', {
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
            if (result.code === 200) renderTable(result.data);
        }

        function renderTable(data) {
            const tbody = document.querySelector('#cursoTable tbody');
            tbody.innerHTML = '';
            data.forEach(curso => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${curso.id_curso}</td>
                    <td>${curso.nombre_curso}</td>
                    <td>${curso.tipo_curso}</td>
                    <td>${curso.precio_normal}</td>
                    <td>${curso.estatus_curso}</td>
                    <td class="action-btns">
                        <button class="btn-action btn-view" onclick="verDetalle(${curso.id_curso})" title="Ver Detalle"><i class="fas fa-eye"></i></button>
                        <button class="btn-action btn-edit" onclick="editarCurso(${curso.id_curso})" title="Editar"><i class="fas fa-pen"></i></button>
                        <button class="btn-action btn-delete" onclick="eliminarCurso(${curso.id_curso})" title="Eliminar"><i class="fas fa-trash"></i></button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }

        async function guardarCurso() {
            const formData = new FormData(document.getElementById('formCrearCurso'));
            formData.append('guardar', 'true');
            const result = await apiRequest(formData);
            if (result.code === 201) {
                cerrarModal('modalCrear');
                buscarTodos();
                alert(result.message);
            } else alert('Error: ' + result.message);
        }

        async function editarCurso(id) {
            const formData = new FormData();
            formData.append('buscar', id);
            const result = await apiRequest(formData);
            if (result.code === 200) {
                const c = result.data;
                document.getElementById('edit_id_curso').value = c.id_curso;
                const fields = ['nombre_curso', 'descripcion_curso', 'tipo_curso', 'precio_preventa', 'precio_normal', 'limite_cupos', 'estatus_curso'];
                fields.forEach(f => {
                    const el = document.querySelector(`#formEditarCurso [name="${f}"]`);
                    if (el) el.value = c[f] || '';
                });
                ['fecha_fin_preventa', 'fecha_inicio_curso', 'fecha_fin_curso'].forEach(f => {
                    const el = document.querySelector(`#formEditarCurso [name="${f}"]`);
                    if (el) el.value = c[f] ? c[f].replace(' ', 'T') : '';
                });
                abrirModal('modalEditar');
            }
        }

        async function actualizarCurso() {
            const formData = new FormData(document.getElementById('formEditarCurso'));
            formData.append('actualizar', 'true');
            const result = await apiRequest(formData);
            if (result.code === 200) {
                cerrarModal('modalEditar');
                buscarTodos();
                alert(result.message);
            } else alert('Error: ' + result.message);
        }

        async function eliminarCurso(id) {
            if (!confirm('¿Está seguro de que desea eliminar este curso?')) return;
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
                document.getElementById('detail_id_curso').textContent = c.id_curso;
                document.getElementById('detail_nombre_curso').textContent = c.nombre_curso;
                document.getElementById('detail_descripcion_curso').textContent = c.descripcion_curso || 'N/A';
                document.getElementById('detail_tipo_curso').textContent = c.tipo_curso;
                document.getElementById('detail_precio_preventa').textContent = c.precio_preventa || 'N/A';
                document.getElementById('detail_precio_normal').textContent = c.precio_normal;
                document.getElementById('detail_fecha_fin_preventa').textContent = c.fecha_fin_preventa || 'N/A';
                document.getElementById('detail_fecha_inicio_curso').textContent = c.fecha_inicio_curso;
                document.getElementById('detail_fecha_fin_curso').textContent = c.fecha_fin_curso || 'N/A';
                document.getElementById('detail_limite_cupos').textContent = c.limite_cupos;
                document.getElementById('detail_estatus_curso').textContent = c.estatus_curso;
                abrirModal('modalDetalle');
            }
        }
    </script>
    <script src="src/assets/bootstrap-5.3.8/js/bootstrap.bundle.min.js"></script>
</body>

</html>