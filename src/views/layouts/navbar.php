<?php
$isFirstLogin = !empty($_SESSION['login_welcome_fullscreen']);
if ($isFirstLogin) {
    unset($_SESSION['login_welcome_fullscreen']);
}
?>
<!-- MAIN CONTENT -->
<div class="main-wrapper">
    <!-- TOPBAR -->
    <header class="topbar">
        <div class="topbar-left" style="display: flex; align-items: center;">
            <button class="btn-sidebar-toggle" id="btnSidebarToggle" type="button" aria-label="Abrir menú de navegación">
                <i class="fas fa-bars"></i>
            </button>
            <div class="navbar-brand-collapsed">
                <span class="brand-ds">DS</span>
                <div class="brand-text">
                    <span>Beauty</span>
                    <strong>Academy</strong>
                </div>
            </div>
        </div>
        <div class="topbar-right">
            <div class="notification">
                <i class="far fa-bell"></i>
            </div>
            <div class="user-profile">
                <a href="?url=perfil" class="user-profile-link" title="Ir a mi perfil" style="display: flex; align-items: center; gap: 12px; text-decoration: none; color: inherit;">
                    <div class="user-avatar-wrapper">
                        <?php if (!empty($_SESSION['foto_perfil_usuario'])): ?>
                            <img src="<?= htmlspecialchars($_SESSION['foto_perfil_usuario']) ?>" alt="Avatar" class="user-avatar" id="navbarUserAvatar" style="object-fit: cover;">
                        <?php else: ?>
                            <i class="fas fa-user-circle user-avatar" id="navbarUserAvatar"></i>
                        <?php endif; ?>
                    </div>
                    <div class="user-info">
                        <span class="user-name"><?= htmlspecialchars($_SESSION['nombre'] ?? 'Usuario') ?></span>
                        <span class="user-role"><?= htmlspecialchars($_SESSION['rol'] ?? 'Invitado') ?></span>
                    </div>
                </a>
                <a href="?url=logout" class="btn-logout" title="Cerrar Sesión" style="margin-left: 12px; color: var(--accent); font-size: 20px; text-decoration: none; display: flex; align-items: center; cursor: pointer; transition: transform 0.2s, color 0.2s;">
                    <i class="fas fa-sign-out-alt"></i>
                </a>
            </div>
        </div>
    </header>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- PRELOADER / ANIMACIÓN DE CARGA (Pantalla completa solo en login inicial; después solo en área de módulos) -->
    <div id="pageLuxuryLoader" class="page-luxury-preloader <?= $isFirstLogin ? 'is-fullscreen' : '' ?>">
        <div class="luxury-preloader-content">
            <div class="luxury-spinner-large">
                <div class="spinner-ring ring-1"></div>
                <div class="spinner-ring ring-2"></div>
                <div class="spinner-ring ring-3"></div>
                <div class="spinner-core">
                    <i class="fas fa-gem preloader-gem"></i>
                </div>
            </div>
            <div class="preloader-branding">
                <span class="preloader-title">DS <strong style="color: var(--accent, #E83382);">BEAUTY ACADEMY</strong></span>
                <span class="preloader-subtitle" id="pageLoaderMessage"><?= $isFirstLogin ? 'Iniciando sesión...' : 'Cargando módulo...' ?></span>
            </div>
        </div>
    </div>

    <script>
        (function () {
            const loader = document.getElementById('pageLuxuryLoader');
            const fromLogin = (sessionStorage.getItem('ds_fullscreen_login') === 'true');

            if (fromLogin && loader) {
                loader.classList.add('is-fullscreen');
                sessionStorage.removeItem('ds_fullscreen_login');
            }

            function dismissPageLoader() {
                if (loader && !loader.classList.contains('fade-out')) {
                    setTimeout(function () {
                        loader.classList.add('fade-out');
                        setTimeout(function () {
                            loader.style.display = 'none';
                            loader.classList.remove('is-fullscreen');
                        }, 400);
                    }, fromLogin ? 600 : 250);
                }
            }

            if (document.readyState === 'complete') {
                dismissPageLoader();
            } else {
                window.addEventListener('load', dismissPageLoader);
                setTimeout(dismissPageLoader, 1400);
            }

            // Para actualizaciones o navegación: cubre únicamente el área de contenido (sin tapar sidebar ni navbar)
            window.showPageLoader = function (msg, fullscreen = false) {
                if (loader) {
                    const text = document.getElementById('pageLoaderMessage');
                    if (text && msg) text.textContent = msg;
                    if (fullscreen) {
                        loader.classList.add('is-fullscreen');
                    } else {
                        loader.classList.remove('is-fullscreen');
                    }
                    loader.style.display = 'flex';
                    loader.classList.remove('fade-out');
                }
            };

            window.hidePageLoader = function () {
                if (loader) {
                    loader.classList.add('fade-out');
                    setTimeout(function () {
                        loader.style.display = 'none';
                        loader.classList.remove('is-fullscreen');
                    }, 350);
                }
            };

            // Al hacer clic en enlaces de módulos del sidebar, mostrar animación solo en el área de módulos
            document.addEventListener('click', function (e) {
                const link = e.target.closest('a[href^="?url="]');
                const isLogout = link && (link.classList.contains('btn-logout') || link.classList.contains('logout-link') || link.getAttribute('href') === '?url=logout');
                if (link && !link.getAttribute('target') && !isLogout && !e.ctrlKey && !e.metaKey) {
                    window.showPageLoader('Cargando módulo...', false);
                }
            });
        })();

        document.addEventListener('DOMContentLoaded', () => {
            const btnToggle = document.getElementById('btnSidebarToggle');
            const sidebar = document.querySelector('.sidebar');
            const overlay = document.getElementById('sidebarOverlay');

            if (btnToggle && sidebar && overlay) {
                btnToggle.addEventListener('click', () => {
                    sidebar.classList.toggle('active');
                    overlay.classList.toggle('active');
                });

                overlay.addEventListener('click', () => {
                    sidebar.classList.remove('active');
                    overlay.classList.remove('active');
                });
            }
        });
    </script>