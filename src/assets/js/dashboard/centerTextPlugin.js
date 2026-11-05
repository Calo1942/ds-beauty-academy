/**
 * ------------------------------------------------------------------
 * DS Beauty Academy - Plugin de Texto Centrado para Donut Charts
 * Archivo: src/assets/js/dashboard/centerTextPlugin.js
 * Descripción: Dibuja el total y subtítulo en el centro del Donut.
 * Utiliza 'beforeTooltipDraw' para garantizar que el Tooltip de Chart.js
 * se renderice SIEMPRE SOBREPUESTO al texto y nunca detrás de él.
 * ------------------------------------------------------------------
 */

const centerTextPlugin = {
    id: 'centerText',
    beforeTooltipDraw(chart) {
        if (!chart.config.options.plugins?.centerText) return;
        const { ctx, chartArea: { width, height, top, left } } = chart;
        const { mainText, subText } = chart.config.options.plugins.centerText;

        ctx.save();
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';
        const centerX = left + width / 2;
        const centerY = top + height / 2;

        // Texto Principal (ej: 186 o $12,450)
        ctx.font = '700 20px "Inter", sans-serif';
        ctx.fillStyle = '#ffffff';
        ctx.fillText(mainText, centerX, centerY - 8);

        // Texto Secundario (ej: Total)
        ctx.font = '500 11px "Inter", sans-serif';
        ctx.fillStyle = '#9ca3af';
        ctx.fillText(subText, centerX, centerY + 13);
        ctx.restore();
    }
};

// Registrar globalmente en Chart.js
if (typeof Chart !== 'undefined') {
    Chart.register(centerTextPlugin);
}

