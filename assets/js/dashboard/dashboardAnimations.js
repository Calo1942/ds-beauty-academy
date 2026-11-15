/**
 * ------------------------------------------------------------------
 * DS Beauty Academy - Animaciones de Barras y Radial Ring
 * Archivo: assets/js/dashboard/dashboardAnimations.js
 * Descripción: Micro-animaciones para barras de progreso y anillo radial.
 * ------------------------------------------------------------------
 */

function revealDashboardContent() {
    const skeleton = document.getElementById('dashboardSkeleton');
    const realContent = document.getElementById('dashboardRealContent');

    if (skeleton && realContent) {
        // Garantizar que se oculte el skeleton y se muestre el contenido
        skeleton.style.display = 'none';
        realContent.style.display = 'block';
        realContent.style.opacity = '1';

        // Disparar resize para que Chart.js dibuje los gráficos perfectamente ajustados
        setTimeout(() => {
            window.dispatchEvent(new Event('resize'));
        }, 100);
    }

    // 1. Animar barras de progreso horizontales
    const progressBars = document.querySelectorAll('.dash-progress-fill');
    progressBars.forEach(bar => {
        const targetWidth = bar.dataset.width || '0%';
        bar.style.width = '0%';
        setTimeout(() => {
            bar.style.width = targetWidth;
        }, 150);
    });

    // 2. Animar anillo radial del KPI 4 (Cupos alcanzados)
    const radialFill = document.querySelector('.dash-radial-fill');
    if (radialFill) {
        const circumference = 2 * Math.PI * 22; // ~138.23
        const percentage = parseFloat(radialFill.dataset.percent || 78);
        const offset = circumference - (percentage / 100) * circumference;
        radialFill.style.strokeDasharray = `${circumference}`;
        radialFill.style.strokeDashoffset = `${circumference}`;
        setTimeout(() => {
            radialFill.style.strokeDashoffset = `${offset}`;
        }, 200);
    }
}

// Ejecutar cuando el DOM esté listo o inmediatamente si ya cargó
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        setTimeout(revealDashboardContent, 350);
    });
} else {
    setTimeout(revealDashboardContent, 350);
}

// Respaldo de seguridad por si la ventana tarda en cargar recursos
window.addEventListener('load', () => {
    revealDashboardContent();
});
