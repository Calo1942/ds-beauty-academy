<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recursos | DS Beauty Academy</title>
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
            <h1 class="page-title">Gestión de Recursos</h1>
            <div class="header-actions">
                <button class="btn btn-primary" onclick="abrirModal('modalCrear')">
                    <i class="fas fa-plus"></i> Registrar Recurso
                </button>
            </div>
        </div>

        <?php include __DIR__ . '/components/recursoDataTable.php'; ?>
    </main>
    </div>

    <!-- Modales -->
    <?php include __DIR__ . '/components/modales/recursoModalCrear.php'; ?>
    <?php include __DIR__ . '/components/modales/recursoModalEditar.php'; ?>
    <?php include __DIR__ . '/components/modales/recursoModalDetalle.php'; ?>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            buscarTodos();
            document.getElementById('formCrearRecurso').addEventListener('submit', (e) => {
                e.preventDefault();
                guardarRecurso();
            });
            document.getElementById('formEditarRecurso').addEventListener('submit', (e) => {
                e.preventDefault();
                actualizarRecurso();
            });
            document.getElementById('searchRecurso').addEventListener('input', function() {
                const filter = this.value.toLowerCase();
                document.querySelectorAll('#recursoTable tbody tr').forEach(row => {
                    row.style.display = row.innerText.toLowerCase().includes(filter) ? '' : 'none';
                });
            });
        });

        function abrirModal(id) {
            document.getElementById(id).classList.add('show');
        }

        function cerrarModal(id) {
            document.getElementById(id).classList.remove('show');
            if (id === 'modalCrear') document.getElementById('formCrearRecurso').reset();
            if (id === 'modalEditar') document.getElementById('formEditarRecurso').reset();
        }

        async function apiRequest(data) {
            try {
                const response = await fetch('?url=recurso', {
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
            const tbody = document.querySelector('#recursoTable tbody');
            tbody.innerHTML = '';
            data.forEach(recurso => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${recurso.id_recurso}</td>
                    <td>${recurso.titulo_recurso}</td>
                    <td>${recurso.id_etiqueta}</td>
                    <td class="action-btns">
                        <button class="btn-action btn-view" onclick="verDetalle(${recurso.id_recurso})" title="Ver Detalle"><i class="fas fa-eye"></i></button>
                        <button class="btn-action btn-edit" onclick="editarRecurso(${recurso.id_recurso})" title="Editar"><i class="fas fa-pen"></i></button>
                        <button class="btn-action btn-delete" onclick="eliminarRecurso(${recurso.id_recurso})" title="Eliminar"><i class="fas fa-trash"></i></button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }

        async function guardarRecurso() {
            const formData = new FormData(document.getElementById('formCrearRecurso'));
            formData.append('guardar', 'true');
            const result = await apiRequest(formData);
            if (result.code === 201) {
                cerrarModal('modalCrear');
                buscarTodos();
                alert(result.message);
            } else alert('Error: ' + result.message);
        }

        async function editarRecurso(id) {
            const formData = new FormData();
            formData.append('buscar', id);
            const result = await apiRequest(formData);
            if (result.code === 200) {
                const r = result.data;
                document.getElementById('edit_id_recurso').value = r.id_recurso;
                ['titulo_recurso', 'descripcion_recurso', 'url_archivo_recurso', 'id_etiqueta'].forEach(f => {
                    const el = document.querySelector(`#formEditarRecurso [name="${f}"]`);
                    if (el) el.value = r[f] || '';
                });
                abrirModal('modalEditar');
            }
        }

        async function actualizarRecurso() {
            const formData = new FormData(document.getElementById('formEditarRecurso'));
            formData.append('actualizar', 'true');
            const result = await apiRequest(formData);
            if (result.code === 200) {
                cerrarModal('modalEditar');
                buscarTodos();
                alert(result.message);
            } else alert('Error: ' + result.message);
        }

        async function eliminarRecurso(id) {
            if (!confirm('¿Está seguro de que desea eliminar este recurso?')) return;
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
                const r = result.data;
                document.getElementById('detail_id_recurso').textContent = r.id_recurso;
                document.getElementById('detail_titulo_recurso').textContent = r.titulo_recurso;
                document.getElementById('detail_descripcion_recurso').textContent = r.descripcion_recurso || 'N/A';
                document.getElementById('detail_url_archivo_recurso').textContent = r.url_archivo_recurso;
                document.getElementById('detail_id_etiqueta').textContent = r.id_etiqueta;
                abrirModal('modalDetalle');
            }
        }
    </script>
    <script src="src/assets/bootstrap-5.3.8/js/bootstrap.bundle.min.js"></script>
</body>

</html>