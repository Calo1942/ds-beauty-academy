/**
 * ------------------------------------------------------------------
 * DS Beauty Academy - Animaciones de Barras y Radial Ring
 * Archivo: src/assets/js/dashboard/dashboardAnimations.js
 * Descripción: Micro-animaciones para barras de progreso y anillo radial.
 * ------------------------------------------------------------------
 */

document.addEventListener('DOMContentLoaded', () => {
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
});

