<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprobantes de Pago | DS Beauty Academy</title>
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
            <h1 class="page-title">Verificación de Pagos</h1>
        </div>

        <div class="table-container">
            <table class="data-table" id="pagoTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Referencia</th>
                        <th>Estudiante</th>
                        <th>Curso</th>
                        <th>Banco</th>
                        <th>Monto</th>
                        <th>Fecha Pago</th>
                        <th>Estado Pago</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="pagoTbody">
                    <!-- Dinámico vía JavaScript / AJAX -->
                </tbody>
            </table>
        </div>
    </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            cargarPagos();
        });

        async function cargarPagos() {
            try {
                const formData = new FormData();
                formData.append('buscarTodos', 'true');
                const response = await fetch('?url=comprobantePago', {
                    method: 'POST',
                    body: formData
                });
                const res = await response.json();
                if (res.code === 200 && res.data) {
                    const tbody = document.getElementById('pagoTbody');
                    tbody.innerHTML = '';
                    res.data.forEach(p => {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td>${p.id_comprobante_pago}</td>
                            <td><strong>${p.referencia_transaccion}</strong></td>
                            <td>${p.nombre_estudiante}</td>
                            <td>${p.nombre_curso}</td>
                            <td>${p.nombre_banco}</td>
                            <td>$${parseFloat(p.monto).toFixed(2)}</td>
                            <td>${p.fecha_pago}</td>
                            <td><span class="badge ${p.estatus_pago === 'Verificado' ? 'bg-success' : (p.estatus_pago === 'Pendiente' ? 'bg-warning' : 'bg-danger')}">${p.estatus_pago}</span></td>
                            <td>
                                ${p.estatus_pago === 'Pendiente' ? `
                                    <button class="btn btn-sm btn-success" onclick="conciliar(${p.id_comprobante_pago}, 'Verificado')">
                                        <i class="fas fa-check"></i> Verificar
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="conciliar(${p.id_comprobante_pago}, 'Rechazado')">
                                        <i class="fas fa-times"></i> Rechazar
                                    </button>
                                ` : '-'}
                            </td>
                        `;
                        tbody.appendChild(tr);
                    });
                }
            } catch (err) {
                console.error("Error al cargar pagos:", err);
            }
        }

        async function conciliar(id, estatus) {
            if (!confirm(`¿Desea marcar este pago como ${estatus}?`)) return;
            const formData = new FormData();
            formData.append('conciliar_pago', 'true');
            formData.append('id_comprobante_pago', id);
            formData.append('estatus_pago', estatus);

            const response = await fetch('?url=comprobantePago', {
                method: 'POST',
                body: formData
            });
            const res = await response.json();
            alert(res.message);
            cargarPagos();
        }
    </script>
</body>

</html>