<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Bancos | DS Beauty Academy</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --bg-color: #0f172a;
            --text-primary: #f8fafc;
            --text-secondary: #94a3b8;
            --accent-color: #ec4899;
            --accent-hover: #be185d;
            --glass-bg: rgba(30, 41, 59, 0.7);
            --glass-border: rgba(255, 255, 255, 0.1);
            --glass-shadow: 0 4px 30px rgba(0, 0, 0, 0.5);
            --success-color: #10b981;
            --danger-color: #ef4444;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background: linear-gradient(135deg, var(--bg-color), #1e1b4b);
            color: var(--text-primary);
            min-height: 100vh;
            padding: 2rem 1rem;
            display: flex;
            justify-content: center;
        }

        .app-container {
            width: 100%;
            max-width: 1000px;
        }

        .header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .header h1 {
            font-size: 2.5rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            background: linear-gradient(to right, #ec4899, #a855f7);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .header p {
            color: var(--text-secondary);
        }

        .main-content {
            display: grid;
            grid-template-columns: 1fr;
            gap: 2rem;
        }

        @media (min-width: 768px) {
            .main-content {
                grid-template-columns: 350px 1fr;
            }
        }

        .glass-panel {
            background: var(--glass-bg);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid var(--glass-border);
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: var(--glass-shadow);
        }

        .glass-panel h2 {
            font-size: 1.25rem;
            margin-bottom: 1.5rem;
            font-weight: 400;
            border-bottom: 1px solid var(--glass-border);
            padding-bottom: 0.5rem;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        .form-group input[type="text"] {
            width: 100%;
            padding: 0.75rem 1rem;
            background: rgba(15, 23, 42, 0.5);
            border: 1px solid var(--glass-border);
            border-radius: 8px;
            color: var(--text-primary);
            font-family: 'Outfit', sans-serif;
            outline: none;
            transition: all 0.3s ease;
        }

        .form-group input[type="text"]:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 2px rgba(236, 72, 153, 0.2);
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .switch {
            position: relative;
            display: inline-block;
            width: 46px;
            height: 24px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(15, 23, 42, 0.8);
            border: 1px solid var(--glass-border);
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 16px;
            width: 16px;
            left: 3px;
            bottom: 4px;
            background-color: var(--text-primary);
            transition: .4s;
        }

        input:checked + .slider {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
        }

        input:checked + .slider:before {
            transform: translateX(22px);
        }

        .slider.round {
            border-radius: 24px;
        }

        .slider.round:before {
            border-radius: 50%;
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-family: 'Outfit', sans-serif;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            flex: 1;
        }

        .btn-primary {
            background: var(--accent-color);
            color: white;
        }

        .btn-primary:hover {
            background: var(--accent-hover);
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: transparent;
            color: var(--text-primary);
            border: 1px solid var(--glass-border);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.05);
        }

        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            border-bottom: 1px solid var(--glass-border);
            padding-bottom: 0.5rem;
        }

        .table-header h2 {
            margin-bottom: 0;
            border-bottom: none;
            padding-bottom: 0;
        }

        .btn-icon {
            background: transparent;
            border: none;
            color: var(--text-secondary);
            cursor: pointer;
            font-size: 1.2rem;
            transition: color 0.3s ease;
        }

        .btn-icon:hover {
            color: var(--accent-color);
        }

        .table-responsive {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        th {
            color: var(--text-secondary);
            font-weight: 400;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        td {
            color: var(--text-primary);
            font-weight: 300;
        }

        .text-center {
            text-align: center;
        }

        .text-error {
            color: var(--danger-color);
        }

        .badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .badge-active {
            background: rgba(16, 185, 129, 0.2);
            color: var(--success-color);
        }

        .badge-inactive {
            background: rgba(239, 68, 68, 0.2);
            color: var(--danger-color);
        }

        .actions-cell {
            display: flex;
            gap: 0.5rem;
        }

        .btn-action {
            background: transparent;
            border: none;
            cursor: pointer;
            font-size: 1rem;
            padding: 0.5rem;
            border-radius: 6px;
            transition: all 0.2s ease;
        }

        .btn-edit {
            color: #3b82f6;
        }

        .btn-edit:hover {
            background: rgba(59, 130, 246, 0.1);
        }

        .btn-delete {
            color: var(--danger-color);
        }

        .btn-delete:hover {
            background: rgba(239, 68, 68, 0.1);
        }
        
        /* Toast Notifications */
        #toast-container {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .toast {
            background: var(--glass-bg);
            backdrop-filter: blur(12px);
            border: 1px solid var(--glass-border);
            color: var(--text-primary);
            padding: 1rem 1.5rem;
            border-radius: 8px;
            box-shadow: var(--glass-shadow);
            display: flex;
            align-items: center;
            gap: 10px;
            transform: translateX(120%);
            transition: transform 0.3s ease;
        }
        .toast.show {
            transform: translateX(0);
        }
        .toast-success i { color: var(--success-color); }
        .toast-error i { color: var(--danger-color); }
    </style>
</head>
<body>
    <div class="app-container">
        <header class="header">
            <h1>Gestión de Bancos</h1>
            <p>Administra los bancos registrados en el sistema</p>
        </header>

        <main class="main-content">
            <!-- Form Section -->
            <section class="glass-panel form-section">
                <h2 id="form-title">Registrar Nuevo Banco</h2>
                <form id="banco-form">
                    <input type="hidden" id="id_banco" name="id_banco">
                    
                    <div class="form-group">
                        <label for="nombre">Nombre del Banco</label>
                        <input type="text" id="nombre" name="nombre" placeholder="Ej. Banco Mercantil" required autocomplete="off">
                    </div>

                    <div class="form-group checkbox-group">
                        <label class="switch">
                            <input type="checkbox" id="estatus_banco" name="estatus_banco" checked value="1">
                            <span class="slider round"></span>
                        </label>
                        <span>Banco Activo</span>
                    </div>

                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary" id="btn-cancel" style="display: none;" onclick="resetForm()">Cancelar</button>
                        <button type="submit" class="btn btn-primary" id="btn-submit">
                            <i class="fas fa-save"></i> Guardar Banco
                        </button>
                    </div>
                </form>
            </section>

            <!-- Table Section -->
            <section class="glass-panel table-section">
                <div class="table-header">
                    <h2>Bancos Registrados</h2>
                    <button class="btn btn-icon" onclick="loadBancos()" title="Actualizar">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                </div>
                <div class="table-responsive">
                    <table id="bancos-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Estatus</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="bancos-tbody">
                            <!-- Rows will be populated by JS -->
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>

    <div id="toast-container"></div>

    <script>
        const API_URL = window.location.href;

        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            toast.className = `toast toast-${type}`;
            const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
            toast.innerHTML = `<i class="fas ${icon}"></i> <span>${message}</span>`;
            
            container.appendChild(toast);
            
            // Trigger animation
            setTimeout(() => {
                toast.classList.add('show');
            }, 10);

            // Remove after 3 seconds
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        async function sendRequest(actionData) {
            const formData = new FormData();
            for (const key in actionData) {
                formData.append(key, actionData[key]);
            }

            try {
                const response = await fetch(API_URL, {
                    method: 'POST',
                    body: formData
                });
                
                const text = await response.text();
                try {
                    return JSON.parse(text);
                } catch(e) {
                    console.error('JSON parse error:', text);
                    return { status: 'error', message: 'Respuesta inválida del servidor.' };
                }
            } catch (error) {
                console.error('Error fetching data:', error);
                return { status: 'error', message: 'Error de conexión con el servidor.' };
            }
        }

        async function loadBancos() {
            const tbody = document.getElementById('bancos-tbody');
            tbody.innerHTML = '<tr><td colspan="4" class="text-center"><i class="fas fa-spinner fa-spin"></i> Cargando...</td></tr>';
            
            const result = await sendRequest({ buscarTodos: '1' });
            
            if (result && result.status === 'success') {
                const bancos = result.data || [];
                tbody.innerHTML = '';
                
                if(bancos.length === 0) {
                     tbody.innerHTML = '<tr><td colspan="4" class="text-center">No hay bancos registrados.</td></tr>';
                     return;
                }

                bancos.forEach(banco => {
                    const tr = document.createElement('tr');
                    const isActive = parseInt(banco.estatus_banco) === 1;
                    const statusBadge = isActive 
                        ? '<span class="badge badge-active">Activo</span>' 
                        : '<span class="badge badge-inactive">Inactivo</span>';

                    tr.innerHTML = `
                        <td>${banco.id_banco}</td>
                        <td>${banco.nombre}</td>
                        <td>${statusBadge}</td>
                        <td class="actions-cell">
                            <button class="btn-action btn-edit" onclick="editBanco(${banco.id_banco})" title="Editar">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn-action btn-delete" onclick="deleteBanco(${banco.id_banco})" title="Eliminar">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>
                    `;
                    tbody.appendChild(tr);
                });
            } else {
                tbody.innerHTML = `<tr><td colspan="4" class="text-center text-error">${result?.message || 'Error al cargar los bancos'}</td></tr>`;
            }
        }

        async function editBanco(id) {
            const result = await sendRequest({ buscar: id });
            if (result && result.status === 'success' && result.data) {
                const banco = result.data;
                document.getElementById('id_banco').value = banco.id_banco;
                document.getElementById('nombre').value = banco.nombre;
                document.getElementById('estatus_banco').checked = parseInt(banco.estatus_banco) === 1;
                
                document.getElementById('form-title').innerText = 'Editar Banco';
                document.getElementById('btn-submit').innerHTML = '<i class="fas fa-save"></i> Actualizar Banco';
                document.getElementById('btn-cancel').style.display = 'inline-block';
                
                document.querySelector('.form-section').scrollIntoView({ behavior: 'smooth' });
            } else {
                showToast(result?.message || 'Error al obtener datos del banco', 'error');
            }
        }

        async function deleteBanco(id) {
            if (confirm('¿Estás seguro de que deseas eliminar este banco? Esta acción no se puede deshacer.')) {
                const result = await sendRequest({ eliminar: id });
                if (result && result.status === 'success') {
                    showToast('Banco eliminado correctamente');
                    loadBancos();
                } else {
                    showToast(result?.message || 'Error al eliminar el banco', 'error');
                }
            }
        }

        function resetForm() {
            document.getElementById('banco-form').reset();
            document.getElementById('id_banco').value = '';
            document.getElementById('estatus_banco').checked = true;
            
            document.getElementById('form-title').innerText = 'Registrar Nuevo Banco';
            document.getElementById('btn-submit').innerHTML = '<i class="fas fa-save"></i> Guardar Banco';
            document.getElementById('btn-cancel').style.display = 'none';
        }

        document.getElementById('banco-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const btnSubmit = document.getElementById('btn-submit');
            const originalBtnHtml = btnSubmit.innerHTML;
            btnSubmit.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';
            btnSubmit.disabled = true;

            const id_banco = document.getElementById('id_banco').value;
            const nombre = document.getElementById('nombre').value;
            const estatus_banco = document.getElementById('estatus_banco').checked ? 1 : 0;
            
            const isUpdate = id_banco !== '';
            const actionKey = isUpdate ? 'actualizar' : 'guardar';
            
            const data = {
                [actionKey]: '1',
                nombre: nombre,
                estatus_banco: estatus_banco
            };
            
            if (isUpdate) {
                data.id_banco = id_banco;
            }
            
            const result = await sendRequest(data);
            
            btnSubmit.innerHTML = originalBtnHtml;
            btnSubmit.disabled = false;

            if (result && result.status === 'success') {
                showToast(isUpdate ? 'Banco actualizado correctamente' : 'Banco registrado correctamente');
                resetForm();
                loadBancos();
            } else {
                const errorMsg = result?.error ? `${result.message}: ${result.error}` : (result?.message || 'Error al guardar el banco');
                showToast(errorMsg, 'error');
                console.error("Backend Error Details:", result);
            }
        });

        // Initialize
        document.addEventListener('DOMContentLoaded', loadBancos);
    </script>
</body>
</html>