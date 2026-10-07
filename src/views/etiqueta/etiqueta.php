<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Etiquetas de Recursos | DS Beauty Academy</title>
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
            <h1 class="page-title">Etiquetas de Recursos</h1>
            <div class="header-actions">
                <button class="btn btn-primary" onclick="abrirModal('modalCrear')">
                    <i class="fas fa-plus"></i> Nueva Etiqueta
                </button>
            </div>
        </div>

        <div class="table-container">
            <div class="table-header">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchEtiqueta" placeholder="Buscar etiqueta...">
                </div>
            </div>
            <table class="data-table" id="etiquetaTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre de Etiqueta</th>
                        <th>Estatus</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="etiquetaTbody">
                    <!-- Dinámico vía JavaScript -->
                </tbody>
            </table>
        </div>
    </main>
    </div>

    <!-- Modal Crear -->
    <div id="modalCrear" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Registrar Etiqueta</h2>
                <span class="close-modal" onclick="cerrarModal('modalCrear')">&times;</span>
            </div>
            <form id="formCrearEtiqueta">
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label class="form-label">Nombre de Etiqueta</label>
                        <input type="text" name="nombre_etiqueta" class="form-control" placeholder="Ej. Guías PDF, Videos, Plantillas" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="cerrarModal('modalCrear')">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            cargarEtiquetas();

            document.getElementById('formCrearEtiqueta').addEventListener('submit', async (e) => {
                e.preventDefault();
                const formData = new FormData(e.target);
                formData.append('guardar', 'true');
                const response = await fetch('?url=etiqueta', { method: 'POST', body: formData });
                const res = await response.json();
                alert(res.message);
                if (res.code === 201) {
                    cerrarModal('modalCrear');
                    e.target.reset();
                    cargarEtiquetas();
                }
            });

            document.getElementById('searchEtiqueta').addEventListener('input', function() {
                const filter = this.value.toLowerCase();
                const rows = document.querySelectorAll('#etiquetaTable tbody tr');
                rows.forEach(row => {
                    const text = row.innerText.toLowerCase();
                    row.style.display = text.includes(filter) ? '' : 'none';
                });
            });
        });

        function abrirModal(id) { document.getElementById(id).classList.add('show'); }
        function cerrarModal(id) { document.getElementById(id).classList.remove('show'); }

        async function cargarEtiquetas() {
            try {
                const formData = new FormData();
                formData.append('buscarTodos', 'true');
                const response = await fetch('?url=etiqueta', { method: 'POST', body: formData });
                const res = await response.json();
                if (res.code === 200 && res.data) {
                    const tbody = document.getElementById('etiquetaTbody');
                    tbody.innerHTML = '';
                    res.data.forEach(e => {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td>${e.id_etiqueta}</td>
                            <td><strong>${e.nombre_etiqueta}</strong></td>
                            <td><span class="badge ${e.estatus_etiqueta == 1 ? 'bg-success' : 'bg-danger'}">${e.estatus_etiqueta == 1 ? 'Activa' : 'Inactiva'}</span></td>
                            <td class="action-btns">
                                <button class="btn-action btn-delete" onclick="eliminarEtiqueta(${e.id_etiqueta})" title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        `;
                        tbody.appendChild(tr);
                    });
                }
            } catch (err) {
                console.error("Error al cargar etiquetas:", err);
            }
        }

        async function eliminarEtiqueta(id) {
            if (!confirm('¿Desea eliminar esta etiqueta?')) return;
            const formData = new FormData();
            formData.append('eliminar', id);
            const response = await fetch('?url=etiqueta', { method: 'POST', body: formData });
            const res = await response.json();
            alert(res.message);
            cargarEtiquetas();
        }
    </script>
    <script src="src/assets/bootstrap-5.3.8/js/bootstrap.bundle.min.js"></script>
</body>
</html>
