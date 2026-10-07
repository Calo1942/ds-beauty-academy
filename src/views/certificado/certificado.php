<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificados | DS Beauty Academy</title>
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
            <h1 class="page-title">Certificados Emitidos</h1>
        </div>

        <div class="table-container">
            <div class="table-header">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchCertificado" placeholder="Buscar certificado...">
                </div>
            </div>
            <table class="data-table" id="certificadoTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Título del Certificado</th>
                        <th>Categoría</th>
                        <th>Matrícula ID</th>
                        <th>Estatus</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="certificadoTbody">
                    <!-- Dinámico vía JavaScript -->
                </tbody>
            </table>
        </div>
    </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            cargarCertificados();

            document.getElementById('searchCertificado').addEventListener('input', function() {
                const filter = this.value.toLowerCase();
                const rows = document.querySelectorAll('#certificadoTable tbody tr');
                rows.forEach(row => {
                    const text = row.innerText.toLowerCase();
                    row.style.display = text.includes(filter) ? '' : 'none';
                });
            });
        });

        async function cargarCertificados() {
            try {
                const formData = new FormData();
                formData.append('buscarTodos', 'true');
                const response = await fetch('?url=certificado', {
                    method: 'POST',
                    body: formData
                });
                const res = await response.json();
                if (res.code === 200 && res.data) {
                    const tbody = document.getElementById('certificadoTbody');
                    tbody.innerHTML = '';
                    res.data.forEach(c => {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td>${c.id_certificado}</td>
                            <td><strong>${c.titulo_certificado}</strong></td>
                            <td><span class="badge bg-secondary">${c.categoria_certificado}</span></td>
                            <td>Matrícula #${c.id_matricula}</td>
                            <td><span class="badge ${c.estatus_certificado === 'Emitido' ? 'bg-success' : (c.estatus_certificado === 'Pendiente de Emisión' ? 'bg-warning' : 'bg-danger')}">${c.estatus_certificado}</span></td>
                            <td class="action-btns">
                                ${c.url_pdf_certificado ? `
                                    <a href="${c.url_pdf_certificado}" target="_blank" class="btn btn-sm btn-outline-primary" title="Descargar PDF">
                                        <i class="fas fa-file-pdf"></i> PDF
                                    </a>
                                ` : '<span class="text-muted" style="font-size:12px;">Sin archivo</span>'}
                            </td>
                        `;
                        tbody.appendChild(tr);
                    });
                }
            } catch (err) {
                console.error("Error al cargar certificados:", err);
            }
        }
    </script>
    <script src="src/assets/bootstrap-5.3.8/js/bootstrap.bundle.min.js"></script>
</body>
</html>
