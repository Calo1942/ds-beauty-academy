<?php
// Obtiene la URL actual o asigna 'dashboard' por defecto
$actualUrl = isset($_GET['url']) ? $_GET['url'] : 'dashboard';
// Variable booleana para saber si estamos en páginas relacionadas con el modulo principal
$isCurso = ($actualUrl == 'curso' || $actualUrl == 'recurso' || $actualUrl == 'clase');
$isInstructor = ($actualUrl == 'instructor' || $actualUrl == 'especialidad' || $actualUrl == 'diploma');
$isMatricula = ($actualUrl == 'matricula' || $actualUrl == 'evaluacion' || $actualUrl == 'certificado');
$isPagos = ($actualUrl == 'pago' || $actualUrl == 'banco');
$isConfig = ($actualUrl == 'configuracion' || $actualUrl == 'etiqueta');
?>

<!-- SIDEBAR -->
<aside class="sidebar">
    <div class="brand">
        <div class="brand-logo">DS <span style="font-weight:300;">Beauty<br><strong
                    style="font-weight:600;">Academy</strong></span></div>
    </div>

    <ul class="nav-menu">
        <!-- Principal -->
        <li class="nav-item">
            <a href="?url=dashboard" class="nav-link <?php if ($actualUrl == 'dashboard') echo 'active'; ?>">
                <i class="fas fa-home "></i> Dashboard
            </a>
        </li>

        <!-- Académico -->
        <li class="nav-item has-children <?php if ($isCurso) echo 'open'; ?>">
            <div class="nav-link">
                <a
                    aria-expanded="<?php echo $isCurso ? 'true' : 'false'; ?>"
                    class="left-part <?php if ($isCurso) echo 'active'; ?>">
                    <i class="fas fa-book-open"></i> Contenido Académico
                </a>
                <i class="fas fa-chevron-down" style="font-size: 12px; color: var(--text-muted);"></i>
            </div>
            <ul class="submenu <?php if ($isCurso) echo 'show'; ?>">
                <li><a href="?url=curso" class="<?php if ($actualUrl == 'curso') echo 'active'; ?>"><i class="fas fa-book"></i> Cursos</a></li>
                <li><a href="?url=recurso" class="<?php if ($actualUrl == 'recurso') echo 'active'; ?>"><i class="fas fa-folder-open"></i> Recursos</a></li>
                <li><a href="?url=clase" class="<?php if ($actualUrl == 'clase') echo 'active'; ?>"><i class="fas fa-video"></i> Clases</a></li>
            </ul>
        </li>

        <li class="nav-item">
            <a href="?url=estudiante" class="nav-link <?php if ($actualUrl == 'estudiante') echo 'active'; ?>">
                <i class="fas fa-users"></i> Estudiantes
            </a>
        </li>

        <li class="nav-item has-children <?php if ($isInstructor) echo 'open'; ?>">
            <div class="nav-link">
                <a
                    aria-expanded="<?php echo $isInstructor ? 'true' : 'false'; ?>"
                    class="left-part <?php if ($isInstructor) echo 'active'; ?>">
                    <i class="fas fa-chalkboard-teacher"></i> Staff de Especialistas
                </a>
                <i class="fas fa-chevron-down" style="font-size: 12px; color: var(--text-muted);"></i>
            </div>
            <ul class="submenu <?php if ($isInstructor) echo 'show'; ?>">
                <li><a href="?url=instructor" class="<?php if ($actualUrl == 'instructor') echo 'active'; ?>"><i class="fas fa-chalkboard-teacher"></i> Instructores</a></li>
                <li><a href="?url=diploma" class="<?php if ($actualUrl == 'diploma') echo 'active'; ?>"><i class="fas fa-certificate"></i> Diplomas</a></li>
                <li><a href="?url=especialidad" class="<?php if ($actualUrl == 'especialidad') echo 'active'; ?>"><i class="fas fa-star"></i> Especialidades</a></li>
            </ul>
        </li>
        <li class="nav-item has-children <?php if ($isMatricula) echo 'open'; ?>">
            <div class="nav-link">
                <a
                    aria-expanded="<?php echo $isMatricula ? 'true' : 'false'; ?>"
                    class="left-part <?php if ($isMatricula) echo 'active'; ?>">
                    <i class="fas fa-id-card"></i> Admisiones y Logros
                </a>
                <i class="fas fa-chevron-down" style="font-size: 12px; color: var(--text-muted);"></i>
            </div>
            <ul class="submenu <?php if ($isMatricula) echo 'show'; ?>">
                <li><a href="?url=matricula" class="<?php if ($actualUrl == 'matricula') echo 'active'; ?>"><i class="fas fa-id-card"></i> Matrículas</a></li>
                <li><a href="?url=evaluacion" class="<?php if ($actualUrl == 'evaluacion') echo 'active'; ?>"><i class="fas fa-clipboard-check"></i> Evaluaciones</a></li>
                <li><a href="?url=certificado" class="<?php if ($actualUrl == 'certificado') echo 'active'; ?>"><i class="fas fa-award"></i> Certificados</a></li>
            </ul>
        </li>

        <li class="nav-item has-children <?php if ($isPagos) echo 'open'; ?>">
            <div class="nav-link">
                <a
                    aria-expanded="<?php echo $isPagos ? 'true' : 'false'; ?>"
                    class="left-part <?php if ($isPagos) echo 'active'; ?>">
                    <i class="fas fa-file-invoice-dollar"></i> Pagos
                </a>
                <i class="fas fa-chevron-down" style="font-size: 12px; color: var(--text-muted);"></i>
            </div>
            <ul class="submenu <?php if ($isPagos) echo 'show'; ?>">
                <li><a href="?url=pago" class="<?php if ($actualUrl == 'pago') echo 'active'; ?>"><i class="fas fa-check-circle"></i> Verificación de Pagos</a></li>
                <li><a href="?url=banco" class="<?php if ($actualUrl == 'banco') echo 'active'; ?>"><i class="fas fa-university"></i> Bancos</a></li>
            </ul>
        </li>
        <li class="nav-item">
            <a href="?url=reporte" class="nav-link  <?php if ($actualUrl == 'reporte') echo 'active'; ?>">
                <i class="fas fa-chart-bar"></i> Reportes
            </a>
        </li>
        <!-- Sistema -->
        <li class="nav-item has-children <?php if ($isConfig) echo 'open'; ?>">
            <div class="nav-link">
                <a
                    href="?url=configuracion"
                    aria-expanded="<?php echo $isConfig ? 'true' : 'false'; ?>"
                    class="left-part <?php if ($isConfig) echo 'active'; ?>">
                    <i class="fas fa-cog"></i> Configuración
                </a>
                <i class="fas fa-chevron-down" style="font-size: 12px; color: var(--text-muted);"></i>
            </div>
            <ul class="submenu <?php if ($isConfig) echo 'show'; ?>">
                <li><a href="?url=etiqueta" class="<?php if ($actualUrl == 'etiqueta') echo 'active'; ?>"><i class="fas fa-tags"></i> Etiquetas de Recursos</a></li>
                <li><a href="?url=configuracion" class="<?php if ($actualUrl == 'configuracion') echo 'active'; ?>"><i class="fas fa-sliders-h"></i> Ajustes Generales</a></li>
            </ul>
        </li>
    </ul>
</aside>

<script>
    document.querySelectorAll('.nav-item.has-children .nav-link').forEach(link => {
        link.addEventListener('click', function(e) {
            const parent = this.closest('.nav-item');
            const submenu = parent.querySelector('.submenu');

            // Toggle class 'open' on parent for chevron rotation
            parent.classList.toggle('open');

            // Toggle class 'show' on submenu
            if (submenu) {
                if (submenu.style.display === 'block' || submenu.classList.contains('show')) {
                    submenu.style.display = 'none';
                    submenu.classList.remove('show');
                    this.querySelector('.left-part').setAttribute('aria-expanded', 'false');
                } else {
                    submenu.style.display = 'block';
                    submenu.classList.add('show');
                    this.querySelector('.left-part').setAttribute('aria-expanded', 'true');
                }
            }
        });
    });
</script>