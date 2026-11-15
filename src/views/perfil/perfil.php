<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil | DS Beauty Academy</title>
    <?php
    // Inclusión centralizada de dependencias CSS y fuentes
    include_once __DIR__ . '/../../../assets/linksFront.php';
    echo $css_links;
    ?>
    <link rel="stylesheet" href="src/views/cropper.min.css">
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
            <p class="perfil-sub-heading">Gestiona tu información personal, certificaciones y preferencias</p>
        </div>

        <!-- Fila Superior: Perfil Hero Card + Mis Especialidades / Mis Cursos -->
        <div class="perfil-top-grid">
            <!-- 1. Tarjeta de Información Principal del Perfil -->
            <div class="perfil-card perfil-hero-card">
                <div class="perfil-hero-content">
                    <div class="perfil-avatar-container">
                        <div class="perfil-avatar-circle" id="perfilAvatarCircle" title="Haz clic para cambiar foto">
                            <i class="fas fa-user-circle perfil-avatar-icon" id="perfilAvatarIcon"></i>
                            <img src="" alt="Foto de Perfil" id="perfilAvatarImg" class="perfil-avatar-img" style="display: none;">
                        </div>
                        <button type="button" class="perfil-camera-btn" id="btnEditarFoto" title="Cambiar foto de perfil">
                            <i class="fas fa-camera"></i>
                        </button>
                        <input type="file" id="inputFotoPerfil" accept="image/jpeg,image/png,image/webp,image/jpg" style="display: none;">
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

            <!-- 2. Tarjeta: Mis Especialidades (Instructor) o Mis Cursos (Estudiante) -->
            <div class="perfil-card perfil-especialidades-card" id="perfilCardEspecialidades">
                <div class="perfil-card-header">
                    <div class="perfil-card-title">
                        <i class="far fa-star text-accent" id="iconoCardEspecialidades"></i>
                        <span id="tituloCardEspecialidades">Mis Especialidades</span>
                    </div>
                    <a href="?url=especialidad" class="perfil-link-action" id="linkCardEspecialidades">Ver todas &rarr;</a>
                </div>

                <div class="perfil-especialidades-grid" id="perfilEspecialidadesContainer">
                    <div class="perfil-empty-box">
                        <i class="fas fa-spinner fa-spin"></i>
                        <p>Cargando información...</p>
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
                            <span id="tituloCardCertificados">Mis Certificados</span>
                        </div>
                        <p class="perfil-card-desc" id="descCardCertificados">Tus certificaciones y avales académicos</p>
                    </div>
                    <button type="button" class="btn-certificados-download-all" id="btnDescargarTodosCertificados" style="display: none;">
                        <i class="fas fa-download"></i> Descargar todos
                    </button>
                </div>

                <div class="perfil-certificados-carousel-wrapper" id="perfilCertificadosWrapper">
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
                        <span class="stat-value" id="statValor1">0</span>
                    </div>
                    <div class="perfil-stat-row">
                        <div class="stat-left">
                            <i class="fas fa-users text-accent"></i>
                            <span id="statTitulo2">Estudiantes Capacitados</span>
                        </div>
                        <span class="stat-value" id="statValor2">0</span>
                    </div>
                    <div class="perfil-stat-row">
                        <div class="stat-left">
                            <i class="fas fa-award text-accent"></i>
                            <span id="statTitulo3">Acreditaciones</span>
                        </div>
                        <span class="stat-value" id="statValor3">0</span>
                    </div>
                </div>

                <div class="perfil-thanks-badge">
                    <i class="fas fa-trophy"></i>
                    <span>Gracias por formar parte de <strong>DS Beauty Academy</strong> ❤️</span>
                </div>
            </div>
        </div>
    </main>

    <!-- Cierre de main-wrapper -->
    </div>

    <!-- Modal Editar Datos del Perfil (Sistema Nativo del Proyecto) -->
    <div id="modalEditarPerfil" class="modal">
        <div class="modal-content" style="max-width: 540px;">
            <div class="modal-header">
                <h2 class="modal-title">
                    <i class="fas fa-user-edit me-2"></i> Editar Mi Perfil
                </h2>
                <span class="close-modal" id="btnCerrarModalPerfil">&times;</span>
            </div>
            <form id="formEditarPerfil">
                <div class="modal-body">
                    <!-- Nombres y Apellidos -->
                    <div class="row g-2 mb-3" id="rowNombresPerfil">
                        <div class="col-md-6 form-group">
                            <label class="form-label" for="inputPrimerNombre">Primer Nombre</label>
                            <input type="text" class="form-control" id="inputPrimerNombre" name="primer_nombre" placeholder="Tu primer nombre">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="form-label" for="inputPrimerApellido">Primer Apellido</label>
                            <input type="text" class="form-control" id="inputPrimerApellido" name="primer_apellido" placeholder="Tu primer apellido">
                        </div>
                    </div>

                    <!-- Teléfonos Principal y Alternativo -->
                    <div class="row g-2 mb-3">
                        <div class="col-md-6 form-group">
                            <label class="form-label" for="inputTelefono">Teléfono Principal</label>
                            <input type="text" class="form-control" id="inputTelefono" name="telefono" placeholder="+58 412 1234567">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="form-label" for="inputTelefonoAlt">Teléfono Alternativo <small class="text-muted">(opcional)</small></label>
                            <input type="text" class="form-control" id="inputTelefonoAlt" name="telefono_alternativo" placeholder="+58 414 7654321">
                        </div>
                    </div>

                    <!-- Instagram -->
                    <div class="form-group mb-3">
                        <label class="form-label" for="inputInstagram">Usuario de Instagram</label>
                        <div class="input-group">
                            <span class="input-group-text bg-dark border-secondary text-accent" style="border-color: var(--border-color);"><i class="fab fa-instagram"></i></span>
                            <input type="text" class="form-control" id="inputInstagram" name="instagram" placeholder="usuario_instagram">
                        </div>
                    </div>

                    <!-- Nueva Contraseña y Confirmar Contraseña -->
                    <div class="row g-2 mb-3">
                        <div class="col-md-6 form-group">
                            <label class="form-label" for="inputPassword">Nueva Contraseña <small class="text-muted">(opcional)</small></label>
                            <input type="password" class="form-control" id="inputPassword" name="password_usuario" placeholder="••••••••" autocomplete="new-password">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="form-label" for="inputPasswordConfirm">Confirmar Contraseña</label>
                            <input type="password" class="form-control" id="inputPasswordConfirm" name="password_confirm" placeholder="••••••••" autocomplete="new-password">
                        </div>
                    </div>

                    <!-- Gestión Rápida de Foto de Perfil -->
                    <div class="form-group mb-2 p-3" style="background: rgba(255,255,255,0.02); border-radius: 8px; border: 1px solid rgba(255,255,255,0.08);">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <label class="form-label mb-0 fw-semibold text-white">Foto de Perfil</label>
                                <small class="d-block text-muted">Formatos JPG, PNG o WEBP (Máx. 5MB)</small>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-outline-pink btn-sm" id="btnModalSubirFoto">
                                    <i class="fas fa-upload"></i> Subir Foto
                                </button>
                                <button type="button" class="btn btn-outline-danger btn-sm" id="btnModalEliminarFoto" style="display: none;" title="Eliminar foto actual">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="btnCancelarEditarPerfil">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="btnGuardarPerfil">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Recortar y Ajustar Foto de Perfil -->
    <div id="modalRecortarFoto" class="modal">
        <div class="modal-content" style="max-width: 560px;">
            <div class="modal-header">
                <h2 class="modal-title">
                    <i class="fas fa-crop-alt me-2"></i> Ajustar Foto de Perfil
                </h2>
                <span class="close-modal" id="btnCerrarModalRecorte">&times;</span>
            </div>
            <div class="modal-body">
                <p class="text-muted small mb-3">
                    Arrastra, amplía o encuadra tu foto. Esta vista circular representa cómo se verá en tu perfil.
                </p>

                <!-- Área de recorte -->
                <div class="perfil-cropper-container" id="cropperContainerWrapper">
                    <img id="imagenParaRecortar" src="" alt="Ajustar Imagen">
                </div>

                <!-- Barra de herramientas para redimensionar y girar -->
                <div class="perfil-cropper-toolbar mt-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div class="btn-group" role="group" aria-label="Herramientas de ajuste">
                        <button type="button" class="btn btn-secondary btn-sm" id="btnCropperZoomIn" title="Acercar (+)">
                            <i class="fas fa-search-plus"></i>
                        </button>
                        <button type="button" class="btn btn-secondary btn-sm" id="btnCropperZoomOut" title="Alejar (-)">
                            <i class="fas fa-search-minus"></i>
                        </button>
                        <button type="button" class="btn btn-secondary btn-sm" id="btnCropperRotateLeft" title="Girar 90° a la izquierda">
                            <i class="fas fa-undo"></i>
                        </button>
                        <button type="button" class="btn btn-secondary btn-sm" id="btnCropperRotateRight" title="Girar 90° a la derecha">
                            <i class="fas fa-redo"></i>
                        </button>
                        <button type="button" class="btn btn-secondary btn-sm" id="btnCropperReset" title="Restablecer encuadre">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>

                    <!-- Vista previa en miniatura circular -->
                    <div class="d-flex align-items-center gap-2">
                        <span class="text-muted small">Vista previa:</span>
                        <div class="perfil-cropper-preview"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="btnCancelarRecorte">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btnGuardarRecorte">
                    <i class="fas fa-check me-1"></i> Aceptar y Guardar Foto
                </button>
            </div>
        </div>
    </div>

    <!-- Scripts globales -->
    <?php echo $scripts_links; ?>

    <!-- Biblioteca Cropper.js local -->
    <script src="assets/js/cropper.min.js"></script>

    <!-- Lógica JS del Perfil -->
    <script type="module" src="assets/js/perfil/perfil.js?v=2.1"></script>
</body>

</html>
