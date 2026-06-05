<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | DS Beauty Academy</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --bg-dark: #0f0f11;
            --bg-panel: #161618;
            --bg-card: #1c1c1e;
            --text-main: #ffffff;
            --text-muted: #a1a1aa;
            --accent: #E83382;
            --accent-hover: #D81B60;
            --success: #10b981;
            --border-color: rgba(255, 255, 255, 0.05);
            --sidebar-width: 260px;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: var(--bg-dark);
            color: var(--text-main);
            display: flex;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* --- SIDEBAR --- */
        .sidebar {
            width: var(--sidebar-width);
            background-color: var(--bg-dark);
            border-right: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            z-index: 100;
        }

        .brand {
            padding: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .brand-logo {
            font-size: 24px;
            font-weight: 700;
            color: var(--accent);
            letter-spacing: -1px;
            display: flex;
            align-items: center;
            line-height: 1;
        }
        .brand-logo span {
            color: white;
            font-size: 18px;
            margin-left: 8px;
            line-height: 1.1;
        }

        .nav-menu {
            padding: 0 16px;
            flex-grow: 1;
            overflow-y: auto;
        }

        .nav-item {
            list-style: none;
            margin-bottom: 4px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            color: var(--text-muted);
            text-decoration: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .nav-link:hover, .nav-link.active {
            background-color: rgba(232, 51, 130, 0.1);
            color: var(--accent);
        }

        .nav-link.active {
            background-color: rgba(232, 51, 130, 0.15);
        }

        .nav-link i {
            font-size: 16px;
            width: 20px;
            text-align: center;
        }

        .nav-item.has-children {
            background-color: var(--bg-card);
            border-radius: 8px;
            margin-bottom: 8px;
        }
        .nav-item.has-children > .nav-link {
            color: var(--text-main);
            background: transparent;
            justify-content: space-between;
        }
        .nav-item.has-children > .nav-link .left-part {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .submenu {
            list-style: none;
            padding-left: 44px;
            padding-bottom: 12px;
        }
        .submenu li {
            margin-bottom: 10px;
        }
        .submenu a {
            color: var(--text-muted);
            text-decoration: none;
            font-size: 13px;
            transition: color 0.2s;
        }
        .submenu a:hover {
            color: var(--text-main);
        }

        /* --- MAIN LAYOUT --- */
        .main-wrapper {
            flex-grow: 1;
            margin-left: var(--sidebar-width);
            display: flex;
            flex-direction: column;
            background-color: #121214; /* Slightly lighter than dark for depth */
        }

        /* --- TOPBAR --- */
        .topbar {
            height: 72px;
            padding: 0 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: var(--bg-panel);
            border-bottom: 1px solid var(--border-color);
        }

        .search-box {
            background-color: #1e1e20;
            border-radius: 8px;
            display: flex;
            align-items: center;
            padding: 8px 16px;
            width: 300px;
        }

        .search-box i {
            color: var(--text-muted);
            margin-right: 12px;
        }

        .search-box input {
            background: none;
            border: none;
            color: white;
            outline: none;
            width: 100%;
            font-size: 14px;
        }
        .search-box input::placeholder {
            color: var(--text-muted);
        }
        
        .search-btn {
            background-color: var(--accent);
            border: none;
            color: white;
            padding: 6px;
            border-radius: 6px;
            cursor: pointer;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 24px;
        }

        .notification {
            position: relative;
            color: var(--text-muted);
            font-size: 18px;
            cursor: pointer;
        }
        .notification::after {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 8px;
            height: 8px;
            background-color: var(--accent);
            border-radius: 50%;
            border: 2px solid var(--bg-panel);
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
        }

        .user-info {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            font-size: 14px;
            font-weight: 600;
        }

        .user-role {
            font-size: 12px;
            color: var(--text-muted);
        }

        /* --- CONTENT --- */
        .content {
            padding: 32px;
            flex-grow: 1;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .page-title {
            font-size: 24px;
            font-weight: 600;
        }

        .header-actions {
            display: flex;
            gap: 12px;
        }

        .btn {
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background-color: var(--accent);
            color: white;
        }
        .btn-primary:hover {
            background-color: var(--accent-hover);
        }

        /* --- STATS CARDS --- */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 32px;
        }

        .stat-card {
            background-color: var(--bg-card);
            border-radius: 12px;
            padding: 20px;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .stat-title {
            font-size: 13px;
            color: var(--text-muted);
            margin-bottom: 8px;
        }

        .stat-value {
            font-size: 28px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .stat-trend {
            font-size: 14px;
            color: var(--success);
        }

        .stat-chart-container {
            margin-top: 16px;
            height: 40px;
            width: 100%;
        }
        
        .stat-chart-container svg {
            width: 100%;
            height: 100%;
        }

        .progress-bar-container {
            margin-top: 16px;
            width: 100%;
            height: 6px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 3px;
            overflow: hidden;
        }

        .progress-bar {
            height: 100%;
            background-color: var(--accent);
            border-radius: 3px;
        }

        /* --- BOTTOM GRID --- */
        .bottom-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
        }

        .panel {
            background-color: var(--bg-card);
            border-radius: 12px;
            padding: 24px;
        }

        .panel-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .panel-title {
            font-size: 16px;
            font-weight: 600;
        }

        /* --- TABLE --- */
        .search-table-box {
            background-color: var(--bg-panel);
            border-radius: 8px;
            padding: 8px 16px;
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            width: 100%;
        }
        .search-table-box i {
            color: var(--text-muted);
            margin-right: 12px;
        }
        .search-table-box input {
            background: none;
            border: none;
            color: white;
            outline: none;
            width: 100%;
            font-size: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            text-align: left;
            color: var(--text-muted);
            font-size: 13px;
            font-weight: 500;
            padding-bottom: 12px;
            border-bottom: 1px solid var(--border-color);
        }

        td {
            padding: 16px 0;
            font-size: 14px;
            border-bottom: 1px solid var(--border-color);
        }

        tr:last-child td {
            border-bottom: none;
        }

        .td-user {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .td-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover;
        }

        /* --- RIGHT PANELS --- */
        .right-panels {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .payment-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid var(--border-color);
        }
        .payment-row:last-child {
            border-bottom: none;
        }

        .payment-col {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .payment-label {
            color: var(--text-muted);
            font-size: 13px;
        }

        .payment-val {
            font-size: 14px;
            font-weight: 600;
        }

        .cert-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 0;
            border-bottom: 1px solid var(--border-color);
        }
        .cert-row:last-child {
            border-bottom: none;
        }

        .cert-user {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .cert-info {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }
        
        .cert-name {
            font-size: 14px;
            font-weight: 500;
        }

        .cert-role {
            font-size: 12px;
            color: var(--text-muted);
        }

        .cert-date {
            font-size: 13px;
            color: var(--text-muted);
        }

    </style>
</head>
<body>

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="brand">
            <div class="brand-logo">DS <span style="font-weight:300;">Beauty<br><strong style="font-weight:600;">Academy</strong></span></div>
        </div>
        
        <ul class="nav-menu">
            <li class="nav-item">
                <a href="#" class="nav-link active">
                    <i class="fas fa-home"></i> Dashboard
                </a>
            </li>
            <li class="nav-item has-children">
                <div class="nav-link">
                    <div class="left-part">
                        <i class="fas fa-book-open"></i> Cursos
                    </div>
                    <i class="fas fa-chevron-down" style="font-size: 12px; color: var(--text-muted);"></i>
                </div>
                <ul class="submenu">
                    <li><a href="#">Categorías</a></li>
                    <li><a href="#">Contenido</a></li>
                    <li><a href="#">Evaluaciones</a></li>
                    <li><a href="#">Reportes</a></li>
                </ul>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-user-tie"></i> Instructores
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-users"></i> Estudiantes
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-credit-card"></i> Pagos
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-certificate"></i> Certificados
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-cog"></i> Configuración
                </a>
            </li>
        </ul>
    </aside>

    <!-- MAIN CONTENT -->
    <div class="main-wrapper">
        <!-- TOPBAR -->
        <header class="topbar">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Buscar...">
                <button class="search-btn"><i class="fas fa-sliders-h" style="margin:0; color:white;"></i></button>
            </div>
            
            <div class="topbar-right">
                <div class="notification">
                    <i class="far fa-bell"></i>
                </div>
                <div class="user-profile">
                    <img src="https://ui-avatars.com/api/?name=Alek+Santana&background=random" alt="User" class="user-avatar">
                    <div class="user-info">
                        <span class="user-name">Alek Santana</span>
                        <span class="user-role">Administrador <i class="fas fa-chevron-down" style="font-size: 10px; margin-left:4px;"></i></span>
                    </div>
                </div>
            </div>
        </header>

        <!-- CONTENT -->
        <main class="content">
            <div class="page-header">
                <h1 class="page-title">Dashboard</h1>
                <div class="header-actions">
                    <button class="btn btn-primary"><i class="fas fa-plus"></i> Añadir Nuevo Curso</button>
                    <button class="btn btn-primary" style="background-color: var(--accent);">Ver Reportes</button>
                </div>
            </div>

            <!-- STATS GRID -->
            <div class="stats-grid">
                <!-- Stat 1 -->
                <div class="stat-card">
                    <div class="stat-title">Estudiantes Activos</div>
                    <div class="stat-value"><?= htmlspecialchars($stats['estudiantes_activos']) ?></div>
                    <div class="stat-chart-container" style="position: absolute; bottom: 0; left: 0; height: 50px; width: 100%;">
                        <svg viewBox="0 0 100 30" preserveAspectRatio="none">
                            <defs>
                                <linearGradient id="gradient" x1="0%" y1="0%" x2="0%" y2="100%">
                                    <stop offset="0%" stop-color="#E83382" stop-opacity="0.5"/>
                                    <stop offset="100%" stop-color="#E83382" stop-opacity="0"/>
                                </linearGradient>
                            </defs>
                            <path d="M0 30 L0 25 Q20 30 40 15 T80 20 T100 5 L100 30 Z" fill="url(#gradient)"></path>
                            <path d="M0 25 Q20 30 40 15 T80 20 T100 5" fill="none" stroke="#E83382" stroke-width="2"></path>
                        </svg>
                    </div>
                </div>

                <!-- Stat 2 -->
                <div class="stat-card">
                    <div class="stat-title">Ingresos Mensuales</div>
                    <div class="stat-value">
                        <?= htmlspecialchars($stats['ingresos_mensuales']) ?>
                        <span class="stat-trend"><i class="fas fa-arrow-up"></i></span>
                    </div>
                </div>

                <!-- Stat 3 -->
                <div class="stat-card">
                    <div class="stat-title">Cursos en Curso</div>
                    <div class="stat-value"><?= htmlspecialchars($stats['cursos_en_curso']) ?></div>
                    <div class="progress-bar-container">
                        <div class="progress-bar" style="width: <?= $stats['progreso_cursos'] ?>%;"></div>
                    </div>
                </div>

                <!-- Stat 4 -->
                <div class="stat-card">
                    <div class="stat-title">Instructores Registrados</div>
                    <div class="stat-value"><?= htmlspecialchars($stats['instructores_registrados']) ?></div>
                </div>
            </div>

            <!-- BOTTOM GRID -->
            <div class="bottom-grid">
                <!-- MATRICULAS -->
                <div class="panel">
                    <div class="panel-header">
                        <h2 class="panel-title">Matrículas Recientes</h2>
                        <button class="btn btn-primary" style="padding: 6px 16px; font-size:13px;">Ver Reportes</button>
                    </div>
                    
                    <div class="search-table-box">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="Buscar...">
                    </div>

                    <table>
                        <thead>
                            <tr>
                                <th>Estudiante</th>
                                <th>Curso</th>
                                <th>Fecha</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($enrollments as $enroll): ?>
                            <tr>
                                <td class="td-user">
                                    <img src="<?= htmlspecialchars($enroll['avatar']) ?>" alt="Avatar" class="td-avatar">
                                    <span><?= htmlspecialchars($enroll['estudiante']) ?></span>
                                </td>
                                <td style="color: var(--text-muted);"><?= htmlspecialchars($enroll['curso']) ?></td>
                                <td style="color: var(--text-muted);"><?= htmlspecialchars($enroll['fecha']) ?></td>
                                <td style="color: var(--text-muted);"><?= htmlspecialchars($enroll['estado']) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- RIGHT PANELS -->
                <div class="right-panels">
                    <!-- PROXIMOS PAGOS -->
                    <div class="panel">
                        <h2 class="panel-title" style="margin-bottom: 20px;">Próximos Pagos</h2>
                        
                        <div class="payment-row" style="border-bottom: none; padding-bottom: 0;">
                            <div class="payment-col" style="flex: 1;">
                                <span class="payment-label">Mejor de Pagos</span>
                            </div>
                            <div class="payment-col" style="flex: 1; align-items: flex-end;">
                                <span class="payment-label">Apredora</span>
                            </div>
                        </div>
                        
                        <?php foreach($payments as $index => $pay): ?>
                        <div class="payment-row">
                            <div class="payment-col" style="flex: 1;">
                                <span class="payment-val"><?= htmlspecialchars($pay['monto'] ?: ($index > 0 ? 'Mejor de Pagos' : '')) ?></span>
                            </div>
                            <div class="payment-col" style="flex: 1; align-items: flex-end;">
                                <span class="payment-val"><?= htmlspecialchars($pay['monto_tipo']) ?></span>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- CERTIFICACIONES -->
                    <div class="panel">
                        <h2 class="panel-title" style="margin-bottom: 20px;">Últimas Certificaciones</h2>
                        
                        <?php foreach($certifications as $cert): ?>
                        <div class="cert-row">
                            <div class="cert-user">
                                <img src="<?= htmlspecialchars($cert['avatar']) ?>" alt="Avatar" class="td-avatar">
                                <div class="cert-info">
                                    <span class="cert-name"><?= htmlspecialchars($cert['nombre']) ?></span>
                                    <span class="cert-role"><?= htmlspecialchars($cert['rol']) ?></span>
                                </div>
                            </div>
                            <div class="cert-date"><?= htmlspecialchars($cert['fecha']) ?></div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </main>
    </div>

</body>
</html>
