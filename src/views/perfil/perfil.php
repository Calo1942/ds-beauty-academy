<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil | DS Beauty Academy</title>
    <?php
    // Inclusión centralizada de dependencias CSS y fuentes
    include_once __DIR__ . '/../../assets/linksFront.php';
    echo $css_links;
    ?>
</head>

<body>
    <!-- Sidebar de navegación -->
    <?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>

    <!-- Navbar superior (abre el .main-wrapper) -->
    <?php require_once __DIR__ . '/../layouts/navbar.php'; ?>

    <main class="content perfil-page-container">
        <!-- Encabezado de la página -->
        <div class="perfil-header-title">
            <h1 class="perfil-main-heading">Mi Perfil</h1>
            <p class="perfil-sub-heading">Gestiona tu información personal y preferencias</p>
        </div>

        <!-- Fila Superior: Perfil Hero Card + Mis Especialidades -->
        <div class="perfil-top-grid">
            <!-- 1. Tarjeta de Información Principal del Perfil -->
            <div class="perfil-card perfil-hero-card">
                <div class="perfil-hero-content">
                    <div class="perfil-avatar-container">
                        <div class="perfil-avatar-circle">
                            <i class="fas fa-user-circle perfil-avatar-icon" id="perfilAvatarIcon"></i>
                        </div>
                        <button class="perfil-camera-btn" id="btnEditarFoto" title="Cambiar foto de perfil">
                            <i class="fas fa-camera"></i>
                        </button>
                    </div>

                    <div class="perfil-hero-info">
                        <div class="perfil-role-badge-wrapper">
                            <span class="perfil-role-badge" id="perfilRolBadge">
                                <i class="fas fa-graduation-cap"></i> <span id="perfilRolTexto">Cargando...</span>
                            </span>
                        </div>
                        <h2 class="perfil-user-name" id="perfilNombre">Cargando...</h2>
                        
                        <div class="perfil-contact-list">
                            <div class="perfil-contact-item">
                                <i class="fas fa-envelope text-accent"></i>
                                <span id="perfilEmail">cargando@dsbeautyacademy.com</span>
                            </div>
                            <div class="perfil-contact-item">
                                <i class="fas fa-phone-alt text-accent"></i>
                                <span id="perfilTelefono">+58 000 0000000</span>
                            </div>
                            <div class="perfil-contact-item">
                                <i class="fas fa-calendar-alt text-accent"></i>
                                <span>Miembro desde: <strong id="perfilMiembroDesde">Cargando...</strong></span>
                            </div>
                        </div>

                        <div class="perfil-actions-btn-group">
                            <button type="button" class="btn btn-outline-pink btn-sm" id="btnAbrirEditarPerfil">
                                <i class="fas fa-user-edit"></i> Editar Información
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. Tarjeta: Mis Especialidades -->
            <div class="perfil-card perfil-especialidades-card">
                <div class="perfil-card-header">
                    <div class="perfil-card-title">
                        <i class="far fa-star text-accent"></i>
                        <span>Mis Especialidades</span>
                    </div>
                    <a href="?url=especialidad" class="perfil-link-action">Ver todas &rarr;</a>
                </div>

                <div class="perfil-especialidades-grid" id="perfilEspecialidadesContainer">
                    <div class="especialidad-mini-box">
                        <i class="fas fa-eye"></i>
                        <span>Diseño de Cejas</span>
                    </div>
                    <div class="especialidad-mini-box">
                        <i class="fas fa-feather-alt"></i>
                        <span>Pestañas</span>
                    </div>
                    <div class="especialidad-mini-box">
                        <i class="fas fa-pen-fancy"></i>
                        <span>Micropigmentación</span>
                    </div>
                    <div class="especialidad-mini-box">
                        <i class="fas fa-magic"></i>
                        <span>Uñas Acrílicas</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Fila Inferior: Mis Certificados + Resumen -->
        <div class="perfil-bottom-grid">
            <!-- 3. Tarjeta: Mis Certificados -->
            <div class="perfil-card perfil-certificados-card">
                <div class="perfil-card-header">
                    <div class="perfil-card-title-block">
                        <div class="perfil-card-title">
                            <i class="fas fa-award text-accent"></i>
                            <span>Mis Certificados</span>
                        </div>
                        <p class="perfil-card-desc">Tus certificaciones y avales académicos</p>
                    </div>
                    <button type="button" class="btn-certificados-download-all" id="btnDescargarTodosCertificados">
                        <i class="fas fa-download"></i> Descargar todos
                    </button>
                </div>

                <div class="perfil-certificados-carousel-wrapper">
                    <button class="cert-carousel-arrow cert-arrow-prev" id="btnCertPrev" aria-label="Anterior">
                        <i class="fas fa-chevron-left"></i>
                    </button>

                    <div class="perfil-certificados-track" id="perfilCertificadosTrack">
                        <!-- Items inyectados dinámicamente por JavaScript -->
                    </div>

                    <button class="cert-carousel-arrow cert-arrow-next" id="btnCertNext" aria-label="Siguiente">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>

            <!-- 4. Tarjeta: Tu Perfil en Resumen -->
            <div class="perfil-card perfil-resumen-card">
                <div class="perfil-card-header">
                    <div class="perfil-card-title">
                        <i class="fas fa-chart-bar text-accent"></i>
                        <span>Tu Perfil en Resumen</span>
                    </div>
                </div>

                <div class="perfil-stats-list">
                    <div class="perfil-stat-row">
                        <div class="stat-left">
                            <i class="fas fa-graduation-cap text-accent"></i>
                            <span id="statTitulo1">Cursos Impartidos</span>
                        </div>
                        <span class="stat-value" id="statValor1">5</span>
                    </div>
                    <div class="perfil-stat-row">
                        <div class="stat-left">
                            <i class="fas fa-users text-accent"></i>
                            <span id="statTitulo2">Estudiantes Capacitados</span>
                        </div>
                        <span class="stat-value" id="statValor2">720</span>
                    </div>
                    <div class="perfil-stat-row">
                        <div class="stat-left">
                            <i class="fas fa-star text-accent"></i>
                            <span id="statTitulo3">Años de Experiencia</span>
                        </div>
                        <span class="stat-value" id="statValor3">2+</span>
                    </div>
                </div>

                <div class="perfil-thanks-badge">
                    <i class="fas fa-trophy"></i>
                    <span>Gracias por ser parte de <strong>DS Beauty Academy</strong> ❤️</span>
                </div>
            </div>
        </div>
    </main>

    <!-- Cierre de main-wrapper -->
    </div>

    <!-- Modal Editar Datos del Perfil -->
    <div class="modal fade" id="modalEditarPerfil" tabindex="-1" aria-labelledby="modalEditarPerfilLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content custom-modal-beauty">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title font-title text-accent" id="modalEditarPerfilLabel">
                        <i class="fas fa-user-edit me-2"></i> Editar Mi Perfil
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formEditarPerfil">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label text-light">Teléfono Principal</label>
                            <input type="text" class="form-control custom-input" id="inputTelefono" name="telefono" placeholder="+58 412 1234567">
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-light">Usuario de Instagram</label>
                            <div class="input-group">
                                <span class="input-group-text bg-dark border-secondary text-accent"><i class="fab fa-instagram"></i></span>
                                <input type="text" class="form-control custom-input" id="inputInstagram" name="instagram" placeholder="usuario_instagram">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-light">Nueva Contraseña <small class="text-muted">(dejar en blanco para no modificarla)</small></label>
                            <input type="password" class="form-control custom-input" id="inputPassword" name="password_usuario" placeholder="••••••••">
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary" id="btnGuardarPerfil">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts globales -->
    <?php echo $scripts_links; ?>

    <!-- Lógica JS del Perfil -->
    <script type="module" src="src/assets/js/perfil/perfil.js"></script>
</body>

</html>

