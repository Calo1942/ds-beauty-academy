<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bancos | DS Beauty Academy</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="src/views/styles.css">
</head>

<body>
    <?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>

    <?php require_once __DIR__ . '/../layouts/navbar.php'; ?>

    <main class="content">
        <div class="page-header">
            <h1 class="page-title">Gestión de Bancos</h1>
            <div class="header-actions">
                <button class="btn btn-primary" onclick="abrirModal('modalCrear')">
                    <i class="fas fa-plus"></i> Nuevo Banco
                </button>
            </div>
        </div>

        <?php include __DIR__ . '/components/bancoDataTable.php'; ?>
    </main>
    </div>

    <!-- Modales -->
    <?php include __DIR__ . '/components/modales/bancoModalCrear.php'; ?>
    <?php include __DIR__ . '/components/modales/bancoModalEditar.php'; ?>
    <?php include __DIR__ . '/components/modales/bancoModalDetalle.php'; ?>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            buscarTodos();

            // Event Listeners para formularios
            document.getElementById('formCrearBanco').addEventListener('submit', (e) => {
                e.preventDefault();
                guardarBanco();
            });

            document.getElementById('formEditarBanco').addEventListener('submit', (e) => {
                e.preventDefault();
                actualizarBanco();
            });

            // Buscador en tiempo real
            document.getElementById('searchBanco').addEventListener('input', function() {
                const filter = this.value.toLowerCase();
                const rows = document.querySelectorAll('#bancoTable tbody tr');
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
            if (id === 'modalCrear') document.getElementById('formCrearBanco').reset();
            if (id === 'modalEditar') document.getElementById('formEditarBanco').reset();
        }

        async function apiRequest(data) {
            try {
                const response = await fetch('?url=banco', {
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
            const tbody = document.querySelector('#bancoTable tbody');
            tbody.innerHTML = '';

            data.forEach(banco => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${banco.id_banco}</td>
                    <td>${banco.nombre_banco}</td>
                    <td class="action-btns">
                        <button class="btn-action btn-view" onclick="verDetalle(${banco.id_banco})" title="Ver Detalle">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn-action btn-edit" onclick="editarBanco(${banco.id_banco})" title="Editar">
                            <i class="fas fa-pen"></i>
                        </button>
                        <button class="btn-action btn-delete" onclick="eliminarBanco(${banco.id_banco})" title="Eliminar">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }

        async function guardarBanco() {
            const form = document.getElementById('formCrearBanco');
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

        async function editarBanco(id) {
            const formData = new FormData();
            formData.append('buscar', id);

            const result = await apiRequest(formData);
            if (result.code === 200) {
                const banco = result.data;
                document.getElementById('edit_id_banco').value = banco.id_banco;
                document.querySelector('#formEditarBanco #nombre_banco').value = banco.nombre_banco;
                abrirModal('modalEditar');
            }
        }

        async function actualizarBanco() {
            const form = document.getElementById('formEditarBanco');
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

        async function eliminarBanco(id) {
            if (!confirm('¿Está seguro de que desea eliminar este banco?')) return;

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
                const banco = result.data;
                document.getElementById('detail_id_banco').textContent = banco.id_banco;
                document.getElementById('detail_nombre_banco').textContent = banco.nombre_banco;
                document.getElementById('detail_estatus_banco').textContent = banco.estatus_banco == 1 ? 'Activo' : 'Inactivo';
                abrirModal('modalDetalle');
            }
        }
    </script>
</body>

</html>