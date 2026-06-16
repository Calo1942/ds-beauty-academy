<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | DS Beauty Academy</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="src/views/styles.css">
</head>

<body>

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="brand">
            <div class="brand-logo">DS <span style="font-weight:300;">Beauty<br><strong
                        style="font-weight:600;">Academy</strong></span></div>
        </div>

        <ul class="nav-menu">
            <!-- Principal -->
            <li class="nav-item">
                <a href="#" class="nav-link active">
                    <i class="fas fa-home"></i> Dashboard
                </a>
            </li>

            <!-- Académico -->
            <li class="nav-item has-children">
                <div class="nav-link">
                    <div class="left-part">
                        <i class="fas fa-book-open"></i> Gestión Académica
                    </div>
                    <i class="fas fa-chevron-down" style="font-size: 12px; color: var(--text-muted);"></i>
                </div>
                <ul class="submenu">
                    <li><a href="#">Catálogo de Cursos</a></li>
                    <li><a href="#">Clases y Recursos</a></li>
                </ul>
            </li>

            <li class="nav-item has-children">
                <div class="nav-link">
                    <div class="left-part">
                        <i class="fas fa-tasks"></i> Evaluaciones
                    </div>
                    <i class="fas fa-chevron-down" style="font-size: 12px; color: var(--text-muted);"></i>
                </div>
                <ul class="submenu">
                    <li><a href="#">Gestión de Evaluaciones</a></li>
                    <li><a href="#">Revisión de Entregas</a></li>
                </ul>
            </li>

            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-award"></i> Certificados
                </a>
            </li>

            <!-- Usuarios -->
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-users"></i> Estudiantes
                </a>
            </li>

            <li class="nav-item has-children">
                <div class="nav-link">
                    <div class="left-part">
                        <i class="fas fa-chalkboard-teacher"></i> Instructores
                    </div>
                    <i class="fas fa-chevron-down" style="font-size: 12px; color: var(--text-muted);"></i>
                </div>
                <ul class="submenu">
                    <li><a href="#">Directorio</a></li>
                    <li><a href="#">Especialidades</a></li>
                </ul>
            </li>

            <!-- Administrativo -->
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-id-card"></i> Matrículas
                </a>
            </li>

            <li class="nav-item has-children">
                <div class="nav-link">
                    <div class="left-part">
                        <i class="fas fa-file-invoice-dollar"></i> Pagos
                    </div>
                    <i class="fas fa-chevron-down" style="font-size: 12px; color: var(--text-muted);"></i>
                </div>
                <ul class="submenu">
                    <li><a href="#">Verificación de Pagos</a></li>
                    <li><a href="#">Bancos</a></li>
                </ul>
            </li>

            <!-- Sistema -->
            <li class="nav-item has-children">
                <div class="nav-link">
                    <div class="left-part">
                        <i class="fas fa-cog"></i> Configuración
                    </div>
                    <i class="fas fa-chevron-down" style="font-size: 12px; color: var(--text-muted);"></i>
                </div>
                <ul class="submenu">
                    <li><a href="#">Etiquetas de Recursos</a></li>
                    <li><a href="#">Ajustes Generales</a></li>
                </ul>
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
                    <img src="https://ui-avatars.com/api/?name=Alek+Santana&background=random" alt="User"
                        class="user-avatar">
                    <div class="user-info">
                        <span class="user-name">Alek Santana</span>
                        <span class="user-role">Administrador <i class="fas fa-chevron-down"
                                style="font-size: 10px; margin-left:4px;"></i></span>
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
                    <div class="stat-chart-container"
                        style="position: absolute; bottom: 0; left: 0; height: 50px; width: 100%;">
                        <svg viewBox="0 0 100 30" preserveAspectRatio="none">
                            <defs>
                                <linearGradient id="gradient" x1="0%" y1="0%" x2="0%" y2="100%">
                                    <stop offset="0%" stop-color="#E83382" stop-opacity="0.5" />
                                    <stop offset="100%" stop-color="#E83382" stop-opacity="0" />
                                </linearGradient>
                            </defs>
                            <path d="M0 30 L0 25 Q20 30 40 15 T80 20 T100 5 L100 30 Z" fill="url(#gradient)"></path>
                            <path d="M0 25 Q20 30 40 15 T80 20 T100 5" fill="none" stroke="#E83382" stroke-width="2">
                            </path>
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
                            <?php foreach ($enrollments as $enroll): ?>
                                <tr>
                                    <td class="td-user">
                                        <img src="<?= htmlspecialchars($enroll['avatar']) ?>" alt="Avatar"
                                            class="td-avatar">
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

                        <?php foreach ($payments as $index => $pay): ?>
                            <div class="payment-row">
                                <div class="payment-col" style="flex: 1;">
                                    <span
                                        class="payment-val"><?= htmlspecialchars($pay['monto'] ?: ($index > 0 ? 'Mejor de Pagos' : '')) ?></span>
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

                        <?php foreach ($certifications as $cert): ?>
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