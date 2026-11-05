/**
 * ------------------------------------------------------------------
 * DS Beauty Academy - Gráfico de Ingresos (Días / Mes)
 * Archivo: src/assets/js/dashboard/chartIngresos.js
 * Descripción: Gráfico de área con curvatura suave, gradiente Glow Pink,
 * filtros dinámicos por Días (mes actual) y Mes (año actual).
 * Escala del eje Y auto-ajustable al monto MÁXIMO real de los datos
 * para garantizar visualización óptima sin importar el rango de cifras.
 * ------------------------------------------------------------------
 */

document.addEventListener('DOMContentLoaded', () => {
    const canvas = document.getElementById('chartIngresosMensuales');
    if (!canvas || typeof Chart === 'undefined') return;

    const ctx = canvas.getContext('2d');
    const pin = document.querySelector('.dash-chart-pin');
    const pinMonth = document.querySelector('.dash-chart-pin-month');
    const pinVal = document.querySelector('.dash-chart-pin-val');
    const subtitle = document.getElementById('dashIngresosSubtitle');

    // Gradiente Glow Pink vertical
    const gradient = ctx.createLinearGradient(0, 0, 0, 260);
    gradient.addColorStop(0, 'rgba(232, 51, 130, 0.45)');
    gradient.addColorStop(0.65, 'rgba(232, 51, 130, 0.12)');
    gradient.addColorStop(1, 'rgba(232, 51, 130, 0.00)');

    // Fallbacks si la BD no tuviese registros
    const diasLabelsFallback = Array.from({ length: 30 }, (_, i) => (i + 1).toString());
    const diasDataFallback = [
        320, 290, 450, 380, 520, 710, 890, 340, 390, 420,
        610, 730, 950, 380, 420, 510, 640, 790, 1150, 410,
        460, 520, 680, 810, 940, 430, 560, 710, 880, 1050
    ];
    const mesesLabelsFallback = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
    const mesesDataFallback = [6800, 5900, 7200, 10500, 11800, 12450, 11200, 13100, 12800, 14200, 13900, 15400];

    const backendData = window.DASHBOARD_METRICS?.ingresos_chart;

    /**
     * Calcula de forma inteligente el límite superior del eje Y a partir
     * del valor máximo real de los datos, agregando un 15% de holgura.
     */
    function calculateDynamicScale(dataArray) {
        const rawMax = Math.max(...(dataArray || []), 0);
        if (rawMax <= 0) {
            return { max: 100, stepSize: 20 };
        }

        const target = rawMax * 1.15;
        const power = Math.pow(10, Math.floor(Math.log10(target)));
        const fraction = target / power;

        let multiplier;
        if (fraction <= 1.2) multiplier = 1.2;
        else if (fraction <= 1.5) multiplier = 1.5;
        else if (fraction <= 2) multiplier = 2;
        else if (fraction <= 2.5) multiplier = 2.5;
        else if (fraction <= 3) multiplier = 3;
        else if (fraction <= 5) multiplier = 5;
        else if (fraction <= 7.5) multiplier = 7.5;
        else multiplier = 10;

        const niceMax = Math.ceil(multiplier * power);
        const stepSize = niceMax / 5;

        return { max: niceMax, stepSize: stepSize };
    }

    /**
     * Formateador legible para los valores del eje Y
     */
    function formatCurrencyTick(value) {
        if (value === 0) return '$0';
        if (value >= 1000000) {
            const formatted = (value / 1000000).toFixed(value % 1000000 !== 0 ? 1 : 0);
            return '$' + formatted + 'M';
        }
        if (value >= 1000) {
            const formatted = (value / 1000).toFixed(value % 1000 !== 0 ? 1 : 0);
            return '$' + formatted + 'k';
        }
        return '$' + Math.round(value).toLocaleString('en-US');
    }

    // Datasets para los filtros "dias" y "mes"
    const datasets = {
        'mes': {
            labels: backendData?.mes?.labels || mesesLabelsFallback,
            data: backendData?.mes?.data || mesesDataFallback,
            pinLabel: backendData?.mes?.pinLabel || 'Mes actual',
            pinVal: backendData?.mes?.pinVal || '$12,450',
            subtitleText: backendData?.mes?.subtitleText || 'Evolución de los ingresos durante el año actual',
            tooltipTitle: (items) => `${items[0].label} (Año actual)`
        },
        'dias': {
            labels: backendData?.dias?.labels || diasLabelsFallback,
            data: backendData?.dias?.data || diasDataFallback,
            pinLabel: backendData?.dias?.pinLabel || 'Día 30',
            pinVal: backendData?.dias?.pinVal || '$1,050',
            subtitleText: backendData?.dias?.subtitleText || 'Evolución de los ingresos durante el mes actual',
            tooltipTitle: (items) => `Día ${items[0].label} del mes`
        }
    };

    let currentPeriod = 'mes';
    let currentConfig = datasets[currentPeriod];
    let currentScale = calculateDynamicScale(currentConfig.data);

    const chartIngresos = new Chart(ctx, {
        type: 'line',
        data: {
            labels: currentConfig.labels,
            datasets: [{
                label: 'Ingresos',
                data: currentConfig.data,
                borderColor: '#E83382',
                borderWidth: 3,
                backgroundColor: gradient,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#E83382',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 8,
                pointHoverBackgroundColor: '#ffffff',
                pointHoverBorderColor: '#E83382',
                pointHoverBorderWidth: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: {
                duration: 800,
                easing: 'easeOutQuart'
            },
            layout: {
                padding: { top: 35, right: 20, left: 10, bottom: 0 }
            },
            interaction: {
                intersect: false,
                mode: 'index'
            },
            onHover: (event, activeElements) => {
                if (pin) {
                    if (activeElements && activeElements.length > 0) {
                        pin.classList.add('dash-pin-hidden');
                    } else {
                        pin.classList.remove('dash-pin-hidden');
                    }
                }
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(21, 21, 28, 0.98)',
                    titleColor: '#9ca3af',
                    bodyColor: '#ffffff',
                    titleFont: { size: 11, family: 'Inter' },
                    bodyFont: { size: 14, weight: '700', family: 'Inter' },
                    borderColor: 'rgba(232, 51, 130, 0.4)',
                    borderWidth: 1,
                    padding: 10,
                    cornerRadius: 8,
                    displayColors: false,
                    callbacks: {
                        title(items) {
                            return datasets[currentPeriod].tooltipTitle(items);
                        },
                        label(context) {
                            return `$${context.parsed.y.toLocaleString('en-US')}`;
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    border: { display: false },
                    ticks: {
                        color: '#9ca3af',
                        font: { size: 12, family: 'Inter' },
                        maxTicksLimit: 12,
                        autoSkip: true
                    }
                },
                y: {
                    min: 0,
                    max: currentScale.max,
                    border: { display: false },
                    grid: {
                        color: 'rgba(255, 255, 255, 0.05)',
                        drawTicks: false
                    },
                    ticks: {
                        color: '#9ca3af',
                        font: { size: 12, family: 'Inter' },
                        stepSize: currentScale.stepSize,
                        callback: formatCurrencyTick
                    }
                }
            }
        }
    });

    // Eventos del Canvas para ocultar pin estático en hover
    canvas.addEventListener('mouseenter', () => {
        if (pin) pin.classList.add('dash-pin-hidden');
    });

    canvas.addEventListener('mouseleave', () => {
        if (pin) pin.classList.remove('dash-pin-hidden');
    });

    // Botones de filtro: "Días" vs "Mes"
    const filterBtns = document.querySelectorAll('.dash-pill-btn');
    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            filterBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            currentPeriod = btn.dataset.period || 'mes';
            const ds = datasets[currentPeriod];

            if (ds && chartIngresos) {
                // Actualizar subtítulo
                if (subtitle) {
                    subtitle.textContent = ds.subtitleText;
                }

                // Actualizar datos del gráfico
                chartIngresos.data.labels = ds.labels;
                chartIngresos.data.datasets[0].data = ds.data;

                // RECALCULAR ESCALA DINÁMICA SEGÚN EL MÁXIMO REAL
                const newScale = calculateDynamicScale(ds.data);
                chartIngresos.options.scales.y.max = newScale.max;
                chartIngresos.options.scales.y.ticks.stepSize = newScale.stepSize;
                chartIngresos.options.scales.x.ticks.maxTicksLimit = currentPeriod === 'dias' ? 10 : 12;

                // Actualizar pin estático
                if (pinMonth && pinVal) {
                    pinMonth.textContent = ds.pinLabel;
                    pinVal.textContent = ds.pinVal;
                }

                chartIngresos.update();
            }
        });
    });
});
