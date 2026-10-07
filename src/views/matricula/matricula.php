<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Matrículas | DS Beauty Academy</title>
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
            <h1 class="page-title">Gestión de Matrículas</h1>
            <div class="header-actions">
                <button class="btn btn-primary" onclick="alert('Formulario de nueva matrícula')">
                    <i class="fas fa-plus"></i> Nueva Matrícula
                </button>
            </div>
        </div>

        <div class="table-container">
            <div class="table-header">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchMatricula" placeholder="Buscar por estudiante, curso...">
                </div>
            </div>
            <table class="data-table" id="matriculaTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Estudiante</th>
                        <th>Cédula</th>
                        <th>Curso</th>
                        <th>Instructor</th>
                        <th>Precio Acordado</th>
                        <th>Estado Pago</th>
                        <th>Estado Cupo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="matriculaTbody">
                    <!-- Dinámico vía JavaScript / AJAX -->
                </tbody>
            </table>
        </div>
    </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            cargarMatriculas();
        });

        async function cargarMatriculas() {
            try {
                const formData = new FormData();
                formData.append('buscarTodos', 'true');
                const response = await fetch('src/Controllers/MatriculaController.php', {
                    method: 'POST',
                    body: formData
                });
                const res = await response.json();
                if (res.code === 200 && res.data) {
                    const tbody = document.getElementById('matriculaTbody');
                    tbody.innerHTML = '';
                    res.data.forEach(m => {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td>${m.id_matricula}</td>
                            <td>${m.nombre_estudiante}</td>
                            <td>${m.cedula_estudiante}</td>
                            <td>${m.nombre_curso}</td>
                            <td>${m.nombre_instructor}</td>
                            <td>$${parseFloat(m.precio_acordado).toFixed(2)}</td>
                            <td><span class="badge ${m.estatus_pago === 'Solvente' ? 'bg-success' : 'bg-warning'}">${m.estatus_pago}</span></td>
                            <td><span class="badge ${m.estatus_cupo === 'Confirmado' ? 'bg-success' : (m.estatus_cupo === 'Reservado' ? 'bg-info' : 'bg-danger')}">${m.estatus_cupo}</span></td>
                            <td>
                                <button class="btn btn-sm btn-outline-danger" onclick="cancelarMatricula(${m.id_matricula})">
                                    <i class="fas fa-times"></i>
                                </button>
                            </td>
                        `;
                        tbody.appendChild(tr);
                    });
                }
            } catch (err) {
                console.error("Error al cargar matrículas:", err);
            }
        }

        async function cancelarMatricula(id) {
            if (!confirm('¿Desea cancelar esta matrícula y liberar el cupo?')) return;
            const formData = new FormData();
            formData.append('eliminar', 'true');
            formData.append('id_matricula', id);
            const response = await fetch('src/Controllers/MatriculaController.php', {
                method: 'POST',
                body: formData
            });
            const res = await response.json();
            alert(res.message);
            cargarMatriculas();
        }
    </script>
</body>
</html>
