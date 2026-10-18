<!-- MAIN CONTENT -->
<div class="main-wrapper">
    <!-- TOPBAR -->
    <header class="topbar">
        <div class="topbar-left">
            <button class="btn-sidebar-toggle" id="btnSidebarToggle" type="button" aria-label="Abrir menú de navegación">
                <i class="fas fa-bars"></i>
            </button>
        </div>
        <div class="topbar-right">
            <div class="notification">
                <i class="far fa-bell"></i>
            </div>
            <div class="user-profile">
                <i class="fas fa-user-circle user-avatar"></i>
                <div class="user-info">
                    <span class="user-name"><?= htmlspecialchars($_SESSION['nombre'] ?? 'Usuario') ?></span>
                    <span class="user-role"><?= htmlspecialchars($_SESSION['rol'] ?? 'Invitado') ?></span>
                </div>
                <a href="?url=logout" class="btn-logout" title="Cerrar Sesión" style="margin-left: 12px; color: var(--accent); font-size: 18px; text-decoration: none; display: flex; align-items: center; cursor: pointer; transition: transform 0.2s;">
                    <i class="fas fa-sign-out-alt"></i>
                </a>
            </div>
        </div>
    </header>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <script>
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