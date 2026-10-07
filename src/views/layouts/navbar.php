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
                    <span class="user-name">Dilenmar Sanchez</span>
                    <span class="user-role">Administradora <i class="fas fa-chevron-down"
                            style="font-size: 10px; margin-left:4px;"></i></span>
                </div>
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