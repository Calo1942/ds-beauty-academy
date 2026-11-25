<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DS Beauty Academy | Academia de Belleza Profesional</title>
    <!-- Fuentes y FontAwesome -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@1,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="assets/bootstrap-5.3.8/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="assets/css/landing.css?v=3.0">
</head>

<body class="landing-page-body">

    <!-- ================= NAVBAR SUPERIOR (SIN SIDEBAR) ================= -->
    <header class="landing-navbar-top">
        <a href="?url=inicio" class="landing-navbar-brand">
            <div class="landing-navbar-logo">
                <i class="fas fa-gem"></i>
            </div>
            <div class="landing-navbar-text">
                DS <span>Beauty</span> <strong>Academy</strong>
            </div>
        </a>

        <!-- Buscador en tiempo real de cursos -->
        <div class="landing-search-container">
            <div class="landing-search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="landingSearchInput" class="landing-search-input" placeholder="Buscar cursos por nombre...">
            </div>
        </div>

        <div>
            <a href="?url=login" class="btn-landing-login">
                <i class="fas fa-sign-in-alt"></i> <span>Iniciar sesión</span>
            </a>
        </div>
    </header>

    <!-- ================= CONTENEDOR PRINCIPAL ================= -->
    <main class="landing-main-container">

        <!-- HERO BANNER CON BIENVENIDA, UBICACIÓN, CONTACTO, INSTAGRAM Y FOTO CEO -->
        <section class="landing-hero" id="inicio">
            <div class="landing-hero-content">
                <span class="hero-pill-badge">BIENVENIDO A</span>
                <h1 class="hero-title">DS Beauty Academy</h1>
                
                <!-- Descripción e Historia de la Academia -->
                <p class="hero-subtitle">
                    Centro de formación profesional de alto nivel en diseño de miradas, estética integral y manicura avanzada.
                </p>
                <p class="hero-description">
                    Nacemos con el propósito de transformar tu vocación en un negocio próspero. A través de metodologías internacionales, prácticas en modelos vivas y acompañamiento personalizado, te brindamos las herramientas para certificarte y liderar tu propio estudio.
                </p>

                <!-- Ubicación, Instagram y Contacto Integrados -->
                <div class="hero-contact-strip">
                    <span class="hero-contact-pill">
                        <i class="fas fa-map-marker-alt"></i> 
                        <span>Barquisimeto, Lara, Venezuela</span>
                    </span>
                    <a href="https://www.instagram.com/difenmar_beauty/" target="_blank" rel="noopener noreferrer" class="hero-contact-pill" title="Visitar Instagram">
                        <i class="fab fa-instagram"></i> 
                        <span>@difenmar_beauty</span>
                    </a>
                    <a href="https://wa.me/584245123984?text=Hola,%20quisiera%20informaci%C3%B3n%20sobre%20DS%20Beauty%20Academy" target="_blank" rel="noopener noreferrer" class="hero-contact-pill" title="Escribir por WhatsApp">
                        <i class="fab fa-whatsapp"></i> 
                        <span>+58 424-5123984</span>
                    </a>
                </div>

                <!-- Pilares de Confianza -->
                <div class="hero-features">
                    <div class="hero-feature-item">
                        <div class="hero-feature-icon"><i class="fas fa-graduation-cap"></i></div>
                        <span>Instructores certificados</span>
                    </div>
                    <div class="hero-feature-item">
                        <div class="hero-feature-icon"><i class="fas fa-certificate"></i></div>
                        <span>Certificación oficial avalada</span>
                    </div>
                    <div class="hero-feature-item">
                        <div class="hero-feature-icon"><i class="fas fa-star"></i></div>
                        <span>Formación 100% práctica</span>
                    </div>
                </div>

                <div class="hero-actions">
                    <a href="#cursos" class="btn-hero-cta">
                        <span>Ver Cursos Disponibles</span> <i class="fas fa-arrow-down ms-1"></i>
                    </a>
                </div>
            </div>

            <!-- Foto de Portada de la Academia (Difenmar Sánchez) -->
            <div class="landing-hero-photo-wrapper">
                <div class="hero-photo-glow"></div>
                <img src="assets/img/landing/difenmar_ceo.png" alt="Difenmar Sánchez - CEO & Técnico Educador" class="landing-hero-photo">
            </div>
        </section>

        <!-- SECCIÓN: TODOS LOS CURSOS DISPONIBLES EN UNA SOLA PÁGINA -->
        <section class="landing-courses-section" id="cursos">
            <div class="landing-section-header">
                <div>
                    <h2 class="landing-section-title">
                        <i class="fas fa-graduation-cap"></i> Todos Nuestros Cursos y Masterclasses
                    </h2>
                    <div class="landing-section-subtitle">
                        Explora la oferta académica disponible, consulta cupos en tiempo real e inscríbete
                    </div>
                </div>
            </div>

            <div class="landing-courses-grid" id="landingCoursesGrid">
                <?php if (!empty($cursos)): ?>
                    <?php foreach ($cursos as $curso): ?>
                        <?php 
                            $id = $curso['id_curso'];
                            $nombre = htmlspecialchars($curso['nombre_curso']);
                            $descripcion = htmlspecialchars($curso['descripcion_curso'] ?? '');
                            $tipo = htmlspecialchars($curso['tipo_curso'] ?? 'Curso');
                            $precio = !empty($curso['precio_preventa']) ? $curso['precio_preventa'] : $curso['precio_normal'];
                            $precioNorm = $curso['precio_normal'] ?? null;
                            $cuposDisp = max(0, (int)($curso['cupos_disponibles'] ?? $curso['limite_cupos']));
                            $instructor = htmlspecialchars($curso['nombre_instructor'] ?? 'Difenmar Sánchez');
                            
                            // Iconos temáticos según nombre de curso
                            $thumbIcon = 'fa-magic';
                            $lower = strtolower($nombre);
                            if (strpos($lower, 'ceja') !== false || strpos($lower, 'microblading') !== false) {
                                $thumbIcon = 'fa-eye';
                            } elseif (strpos($lower, 'pestaña') !== false || strpos($lower, 'lashes') !== false) {
                                $thumbIcon = 'fa-feather-alt';
                            } elseif (strpos($lower, 'uña') !== false || strpos($lower, 'manicura') !== false) {
                                $thumbIcon = 'fa-hand-sparkles';
                            } elseif (strpos($lower, 'maquillaje') !== false || strpos($lower, 'makeup') !== false) {
                                $thumbIcon = 'fa-paint-brush';
                            } elseif (strpos($lower, 'colorimetría') !== false || strpos($lower, 'balayage') !== false) {
                                $thumbIcon = 'fa-palette';
                            } elseif (strpos($lower, 'barber') !== false) {
                                $thumbIcon = 'fa-cut';
                            }
                        ?>
                        <div class="landing-course-card" data-title="<?= strtolower($nombre) ?>">
                            <div class="course-card-thumb btn-trigger-detalle" 
                                 data-id="<?= $id ?>"
                                 data-nombre="<?= $nombre ?>"
                                 data-tipo="<?= $tipo ?>"
                                 data-descripcion="<?= $descripcion ?>"
                                 data-precio="$<?= number_format((float)$precio, 2) ?>"
                                 data-cupos="<?= $cuposDisp ?>"
                                 data-instructor="<?= $instructor ?>"
                                 data-inicio="<?= htmlspecialchars($curso['fecha_inicio_curso'] ?? 'Próximamente') ?>">
                                <span class="course-badge-tipo"><?= $tipo ?></span>
                                
                                <!-- Badge de Cupos Disponibles -->
                                <span class="course-badge-cupos <?= $cuposDisp > 0 ? 'cupos-disponibles-badge' : 'cupos-agotados-badge' ?>">
                                    <i class="fas fa-ticket-alt me-1"></i>
                                    <?= $cuposDisp > 0 ? "{$cuposDisp} cupos disponibles" : "Cupos agotados" ?>
                                </span>

                                <i class="fas <?= $thumbIcon ?> thumb-icon"></i>
                            </div>

                            <div class="course-card-body">
                                <div class="course-card-cat"><?= $tipo ?></div>
                                <h3 class="course-card-title btn-trigger-detalle" 
                                    title="<?= $nombre ?>"
                                    data-id="<?= $id ?>"
                                    data-nombre="<?= $nombre ?>"
                                    data-tipo="<?= $tipo ?>"
                                    data-descripcion="<?= $descripcion ?>"
                                    data-precio="$<?= number_format((float)$precio, 2) ?>"
                                    data-cupos="<?= $cuposDisp ?>"
                                    data-instructor="<?= $instructor ?>"
                                    data-inicio="<?= htmlspecialchars($curso['fecha_inicio_curso'] ?? 'Próximamente') ?>">
                                    <?= $nombre ?>
                                </h3>
                                
                                <div class="course-card-meta">
                                    <span><i class="far fa-clock"></i> Certificado</span>
                                    <span><i class="fas fa-chalkboard-teacher"></i> <?= $instructor ?></span>
                                </div>

                                <div class="course-card-prices">
                                    <span class="price-current">$<?= number_format((float)$precio, 2) ?></span>
                                    <?php if (!empty($curso['precio_preventa']) && !empty($precioNorm) && $precioNorm > $curso['precio_preventa']): ?>
                                        <span class="price-old">$<?= number_format((float)$precioNorm, 2) ?></span>
                                    <?php endif; ?>
                                </div>

                                <div class="card-action-btns">
                                    <button type="button" class="btn-course-ver btn-trigger-detalle"
                                            data-id="<?= $id ?>"
                                            data-nombre="<?= $nombre ?>"
                                            data-tipo="<?= $tipo ?>"
                                            data-descripcion="<?= $descripcion ?>"
                                            data-precio="$<?= number_format((float)$precio, 2) ?>"
                                            data-cupos="<?= $cuposDisp ?>"
                                            data-instructor="<?= $instructor ?>"
                                            data-inicio="<?= htmlspecialchars($curso['fecha_inicio_curso'] ?? 'Próximamente') ?>">
                                        <i class="fas fa-eye"></i> <span>Detalle</span>
                                    </button>

                                    <button type="button" class="btn-course-inscribirse btn-inscribirse-gate" 
                                            data-id="<?= $id ?>" 
                                            data-nombre="<?= $nombre ?>">
                                        <span>Inscribirme</span> <i class="fas fa-arrow-right"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center py-5 w-100 text-muted">
                        <i class="fas fa-info-circle fa-2x mb-3 d-block text-accent" style="color: #E83382;"></i>
                        No hay cursos disponibles para mostrar en este momento.
                    </div>
                <?php endif; ?>
            </div>
        </section>

    </main>

    <!-- ================= MODAL DE DETALLE DEL CURSO ================= -->
    <div class="modal-landing-custom" id="modalDetalleCurso">
        <div class="modal-landing-card">
            <div class="modal-landing-header">
                <h3 class="modal-landing-title">
                    <i class="fas fa-book-open" style="color: #E83382;"></i>
                    <span id="modalCursoTitulo">Detalle del Curso</span>
                </h3>
                <button type="button" class="modal-landing-close" id="btnCerrarModalDetalle" title="Cerrar modal">&times;</button>
            </div>

            <div class="modal-landing-body">
                <div class="modal-detail-item">
                    <span class="modal-detail-label">Modalidad / Tipo:</span>
                    <span class="modal-detail-value" id="modalCursoTipo">-</span>
                </div>

                <div class="modal-detail-item">
                    <span class="modal-detail-label">Cupos Disponibles:</span>
                    <span class="modal-detail-value" id="modalCursoCupos" style="color: #10b981;">-</span>
                </div>

                <div class="modal-detail-item">
                    <span class="modal-detail-label">Inversión / Precio:</span>
                    <span class="modal-detail-value" id="modalCursoPrecio" style="color: #E83382; font-size: 16px;">-</span>
                </div>

                <div class="modal-detail-item">
                    <span class="modal-detail-label">Instructor Asignado:</span>
                    <span class="modal-detail-value" id="modalCursoInstructor">-</span>
                </div>

                <div class="modal-detail-item">
                    <span class="modal-detail-label">Fecha de Inicio:</span>
                    <span class="modal-detail-value" id="modalCursoInicio">-</span>
                </div>

                <div class="mt-4">
                    <label class="modal-detail-label d-block mb-2 font-weight-bold">Contenido y Descripción del Programa:</label>
                    <p id="modalCursoDescripcion" style="color: #d1d5db; font-size: 13.5px; line-height: 1.6; background-color: rgba(255, 255, 255, 0.03); padding: 14px; border-radius: 12px; border: 1px solid rgba(255, 255, 255, 0.06); margin: 0;">
                        -
                    </p>
                </div>
            </div>

            <div class="modal-landing-footer">
                <button type="button" class="btn-modal-cancel" id="btnCerrarModalFooter">Cerrar</button>
                <button type="button" class="btn-course-inscribirse" id="btnModalInscribirse">
                    <span>Inscribirme en este curso</span> <i class="fas fa-arrow-right"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            let cursoSeleccionadoNombre = '';

            // Buscador en tiempo real de cursos
            $('#landingSearchInput').on('input', function() {
                const term = $(this).val().toLowerCase().trim();
                $('.landing-course-card').each(function() {
                    const title = $(this).data('title') || '';
                    if (title.includes(term) || term === '') {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });

            // Apertura del Modal de Detalle de Curso
            $('.btn-trigger-detalle').on('click', function(e) {
                e.preventDefault();
                const $btn = $(this);
                const nombre = $btn.data('nombre');
                cursoSeleccionadoNombre = nombre;

                $('#modalCursoTitulo').text(nombre);
                $('#modalCursoTipo').text($btn.data('tipo') || 'Curso Profesional');
                $('#modalCursoCupos').html(`<i class="fas fa-ticket-alt me-1"></i> ${$btn.data('cupos')} cupos disponibles`);
                $('#modalCursoPrecio').text($btn.data('precio'));
                $('#modalCursoInstructor').text($btn.data('instructor') || 'Difenmar Sánchez');
                $('#modalCursoInicio').text($btn.data('inicio') || 'Consultar con administración');
                $('#modalCursoDescripcion').text($btn.data('descripcion') || 'Formación especializada teórico-práctica con certificación oficial avalada por DS Beauty Academy.');

                $('#modalDetalleCurso').addClass('active');
            });

            // Cerrar Modal
            $('#btnCerrarModalDetalle, #btnCerrarModalFooter').on('click', function() {
                $('#modalDetalleCurso').removeClass('active');
            });

            // Cerrar haciendo clic en el fondo oscuro
            $('#modalDetalleCurso').on('click', function(e) {
                if (e.target === this) {
                    $(this).removeClass('active');
                }
            });

            // Gate de Inscripción: Iniciar Sesión obligatorio
            $('.btn-inscribirse-gate, #btnModalInscribirse').on('click', function(e) {
                e.preventDefault();
                const nombre = $(this).data('nombre') || cursoSeleccionadoNombre || 'el curso seleccionado';

                Swal.fire({
                    title: '¡Inscríbete en este curso!',
                    html: `
                        <p style="color: #ffffff; font-size: 15px; margin-bottom: 12px;">
                            Curso: <strong style="color: #E83382;">${nombre}</strong>
                        </p>
                        <p style="color: #d1d5db; font-size: 13.5px; line-height: 1.55;">
                            Para formalizar tu inscripción, apartar tu cupo disponible o gestionar pagos, 
                            debes <strong>iniciar sesión</strong> en la plataforma.
                        </p>
                    `,
                    icon: 'info',
                    iconColor: '#E83382',
                    background: '#151622',
                    color: '#ffffff',
                    showCancelButton: true,
                    confirmButtonText: '<i class="fas fa-sign-in-alt me-1"></i> Iniciar Sesión',
                    cancelButtonText: 'Cancelar',
                    confirmButtonColor: '#E83382',
                    cancelButtonColor: '#2a2b3d',
                    customClass: {
                        popup: 'border-luxury'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '?url=login';
                    }
                });
            });
        });
    </script>
</body>

</html>
