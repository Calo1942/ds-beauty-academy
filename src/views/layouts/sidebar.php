<?php
// Obtiene el rol de la sesión activa
$userRole = $_SESSION['rol'] ?? 'Invitado';
$isAdmin = ($userRole === 'Administrador');
$isInstructor = ($userRole === 'Instructor');
$isEstudiante = ($userRole === 'Estudiante');

// Obtiene la URL actual o asigna 'dashboard' por defecto
$actualUrl = isset($_GET['url']) ? $_GET['url'] : 'dashboard';

// Variables booleanas para saber si estamos en páginas de submódulos
$isCurso = ($actualUrl == 'curso' || $actualUrl == 'recurso' || $actualUrl == 'clase');
$isStaff = ($actualUrl == 'instructor' || $actualUrl == 'especialidad' || $actualUrl == 'diploma');
$isMatricula = ($actualUrl == 'matricula' || $actualUrl == 'evaluacion' || $actualUrl == 'certificado');
$isPagos = ($actualUrl == 'pago' || $actualUrl == 'comprobantePago' || $actualUrl == 'banco');
$isConfig = ($actualUrl == 'configuracion');
?>

<!-- SIDEBAR -->
<aside class="sidebar">
    <div class="brand">
        <div class="brand-logo">DS <span style="font-weight:300;">Beauty<br><strong
                    style="font-weight:600;">Academy</strong></span></div>
    </div>

    <ul class="nav-menu">
        <!-- Dashboard (Común para todos) -->
        <li class="nav-item">
            <a href="?url=dashboard" class="nav-link <?= ($actualUrl == 'dashboard') ? 'active' : '' ?>">
                <i class="fas fa-home"></i> Dashboard
            </a>
        </li>

        <?php if ($isAdmin): ?>
            <!-- ================= VISTAS PARA ADMINISTRADOR ================= -->
            
            <!-- Académico -->
            <li class="nav-item has-children <?= $isCurso ? 'open' : '' ?>">
                <div class="nav-link">
                    <a aria-expanded="<?= $isCurso ? 'true' : 'false' ?>"
                        class="left-part <?= $isCurso ? 'active' : '' ?>">
                        <i class="fas fa-book-open"></i> Contenido Académico
                    </a>
                    <i class="fas fa-chevron-down" style="font-size: 12px; color: var(--text-muted);"></i>
                </div>
                <ul class="submenu <?= $isCurso ? 'show' : '' ?>">
                    <li><a href="?url=curso" class="<?= ($actualUrl == 'curso') ? 'active' : '' ?>"><i
                                class="fas fa-book"></i> Cursos</a></li>
                    <li><a href="?url=recurso" class="<?= ($actualUrl == 'recurso') ? 'active' : '' ?>"><i
                                class="fas fa-folder-open"></i> Recursos</a></li>
                    <li><a href="?url=clase" class="<?= ($actualUrl == 'clase') ? 'active' : '' ?>"><i
                                class="fas fa-video"></i> Clases</a></li>
                </ul>
            </li>

            <!-- Estudiantes -->
            <li class="nav-item">
                <a href="?url=estudiante" class="nav-link <?= ($actualUrl == 'estudiante') ? 'active' : '' ?>">
                    <i class="fas fa-users"></i> Estudiantes
                </a>
            </li>

            <!-- Staff de Especialistas -->
            <li class="nav-item has-children <?= $isStaff ? 'open' : '' ?>">
                <div class="nav-link">
                    <a aria-expanded="<?= $isStaff ? 'true' : 'false' ?>"
                        class="left-part <?= $isStaff ? 'active' : '' ?>">
                        <i class="fas fa-chalkboard-teacher"></i> Staff de Especialistas
                    </a>
                    <i class="fas fa-chevron-down" style="font-size: 12px; color: var(--text-muted);"></i>
                </div>
                <ul class="submenu <?= $isStaff ? 'show' : '' ?>">
                    <li><a href="?url=instructor" class="<?= ($actualUrl == 'instructor') ? 'active' : '' ?>"><i
                                class="fas fa-chalkboard-teacher"></i> Instructores</a></li>
                    <li><a href="?url=diploma" class="<?= ($actualUrl == 'diploma') ? 'active' : '' ?>"><i
                                class="fas fa-certificate"></i> Diplomas</a></li>
                    <li><a href="?url=especialidad" class="<?= ($actualUrl == 'especialidad') ? 'active' : '' ?>"><i
                                class="fas fa-star"></i> Especialidades</a></li>
                </ul>
            </li>

            <!-- Admisiones y Logros -->
            <li class="nav-item has-children <?= $isMatricula ? 'open' : '' ?>">
                <div class="nav-link">
                    <a aria-expanded="<?= $isMatricula ? 'true' : 'false' ?>"
                        class="left-part <?= $isMatricula ? 'active' : '' ?>">
                        <i class="fas fa-id-card"></i> Admisiones y Logros
                    </a>
                    <i class="fas fa-chevron-down" style="font-size: 12px; color: var(--text-muted);"></i>
                </div>
                <ul class="submenu <?= $isMatricula ? 'show' : '' ?>">
                    <li><a href="?url=matricula" class="<?= ($actualUrl == 'matricula') ? 'active' : '' ?>"><i
                                class="fas fa-id-card"></i> Matrículas</a></li>
                    <li><a href="?url=evaluacion" class="<?= ($actualUrl == 'evaluacion') ? 'active' : '' ?>"><i
                                class="fas fa-clipboard-check"></i> Evaluaciones</a></li>
                    <li><a href="?url=certificado" class="<?= ($actualUrl == 'certificado') ? 'active' : '' ?>"><i
                                class="fas fa-award"></i> Certificados</a></li>
                </ul>
            </li>

            <!-- Pagos -->
            <li class="nav-item has-children <?= $isPagos ? 'open' : '' ?>">
                <div class="nav-link">
                    <a aria-expanded="<?= $isPagos ? 'true' : 'false' ?>"
                        class="left-part <?= $isPagos ? 'active' : '' ?>">
                        <i class="fas fa-file-invoice-dollar"></i> Pagos
                    </a>
                    <i class="fas fa-chevron-down" style="font-size: 12px; color: var(--text-muted);"></i>
                </div>
                <ul class="submenu <?= $isPagos ? 'show' : '' ?>">
                    <li><a href="?url=pago" class="<?= ($actualUrl == 'pago' || $actualUrl == 'comprobantePago') ? 'active' : '' ?>"><i
                                class="fas fa-check-circle"></i> Verificación de Pagos</a></li>
                    <li><a href="?url=banco" class="<?= ($actualUrl == 'banco') ? 'active' : '' ?>"><i
                                class="fas fa-university"></i> Bancos</a></li>
                </ul>
            </li>

            <!-- Reportes -->
            <li class="nav-item">
                <a href="?url=reporte" class="nav-link <?= ($actualUrl == 'reporte') ? 'active' : '' ?>">
                    <i class="fas fa-chart-bar"></i> Reportes
                </a>
            </li>

            <!-- Configuración -->
            <li class="nav-item has-children <?= $isConfig ? 'open' : '' ?>">
                <div class="nav-link">
                    <a href="?url=configuracion" aria-expanded="<?= $isConfig ? 'true' : 'false' ?>"
                        class="left-part <?= $isConfig ? 'active' : '' ?>">
                        <i class="fas fa-cog"></i> Configuración
                    </a>
                    <i class="fas fa-chevron-down" style="font-size: 12px; color: var(--text-muted);"></i>
                </div>
                <ul class="submenu <?= $isConfig ? 'show' : '' ?>">
                    <li><a href="?url=configuracion" class="<?= ($actualUrl == 'configuracion') ? 'active' : '' ?>"><i
                                class="fas fa-sliders-h"></i> Ajustes Generales</a></li>
                </ul>
            </li>

        <?php elseif ($isInstructor): ?>
            <!-- ================= VISTAS PARA INSTRUCTOR ================= -->
            
            <li class="nav-item has-children <?= ($actualUrl == 'curso' || $actualUrl == 'clase') ? 'open' : '' ?>">
                <div class="nav-link">
                    <a class="left-part <?= ($actualUrl == 'curso' || $actualUrl == 'clase') ? 'active' : '' ?>">
                        <i class="fas fa-book-reader"></i> Gestión de Cursos
                    </a>
                    <i class="fas fa-chevron-down" style="font-size: 12px; color: var(--text-muted);"></i>
                </div>
                <ul class="submenu <?= ($actualUrl == 'curso' || $actualUrl == 'clase') ? 'show' : '' ?>">
                    <li><a href="?url=curso" class="<?= ($actualUrl == 'curso') ? 'active' : '' ?>"><i
                                class="fas fa-book"></i> Mis Cursos</a></li>
                    <li><a href="?url=clase" class="<?= ($actualUrl == 'clase') ? 'active' : '' ?>"><i
                                class="fas fa-video"></i> Clases y Sesiones</a></li>
                </ul>
            </li>

            <li class="nav-item">
                <a href="?url=evaluacion" class="nav-link <?= ($actualUrl == 'evaluacion') ? 'active' : '' ?>">
                    <i class="fas fa-clipboard-check"></i> Evaluaciones y Calificaciones
                </a>
            </li>

            <li class="nav-item">
                <a href="?url=diploma" class="nav-link <?= ($actualUrl == 'diploma') ? 'active' : '' ?>">
                    <i class="fas fa-certificate"></i> Mis Diplomas
                </a>
            </li>

        <?php elseif ($isEstudiante): ?>
            <!-- ================= VISTAS PARA ESTUDIANTE ================= -->
            
            <li class="nav-item">
                <a href="?url=curso" class="nav-link <?= ($actualUrl == 'curso') ? 'active' : '' ?>">
                    <i class="fas fa-book"></i> Mis Cursos
                </a>
            </li>

            <li class="nav-item">
                <a href="?url=clase" class="nav-link <?= ($actualUrl == 'clase') ? 'active' : '' ?>">
                    <i class="fas fa-video"></i> Mis Clases
                </a>
            </li>

            <li class="nav-item">
                <a href="?url=evaluacion" class="nav-link <?= ($actualUrl == 'evaluacion') ? 'active' : '' ?>">
                    <i class="fas fa-clipboard-check"></i> Mis Evaluaciones
                </a>
            </li>

            <li class="nav-item">
                <a href="?url=certificado" class="nav-link <?= ($actualUrl == 'certificado') ? 'active' : '' ?>">
                    <i class="fas fa-award"></i> Mis Certificados
                </a>
            </li>

        <?php endif; ?>
    </ul>
</aside>

<script>
    document.querySelectorAll('.nav-item.has-children .nav-link').forEach(link => {
        link.addEventListener('click', function (e) {
            const parent = this.closest('.nav-item');
            const submenu = parent.querySelector('.submenu');

            // Toggle class 'open' on parent for chevron rotation
            parent.classList.toggle('open');

            // Toggle class 'show' on submenu
            if (submenu) {
                if (submenu.style.display === 'block' || submenu.classList.contains('show')) {
                    submenu.style.display = 'none';
                    submenu.classList.remove('show');
                    const leftPart = this.querySelector('.left-part');
                    if (leftPart) leftPart.setAttribute('aria-expanded', 'false');
                } else {
                    submenu.style.display = 'block';
                    submenu.classList.add('show');
                    const leftPart = this.querySelector('.left-part');
                    if (leftPart) leftPart.setAttribute('aria-expanded', 'true');
                }
            }
        });
    });
</script>