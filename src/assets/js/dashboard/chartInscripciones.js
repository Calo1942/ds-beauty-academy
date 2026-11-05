/**
 * ------------------------------------------------------------------
 * DS Beauty Academy - Gráfico Donut de Estado de las Inscripciones
 * Archivo: src/assets/js/dashboard/chartInscripciones.js
 * Descripción: Configuración del Donut con total central y tooltips
 * sobrepuestos de alta legibilidad. Conectado con la BD real.
 * ------------------------------------------------------------------
 */

document.addEventListener('DOMContentLoaded', () => {
    const canvas = document.getElementById('chartEstadoInscripciones');
    if (!canvas || typeof Chart === 'undefined') return;

    const backendData = window.DASHBOARD_METRICS?.estado_inscripciones || {
        total: 186,
        confirmadas_pct: 68,
        reservadas_pct: 20,
        canceladas_pct: 12
    };

    new Chart(canvas.getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: ['Confirmadas', 'Reservadas', 'Canceladas'],
            datasets: [{
                data: [
                    backendData.confirmadas_pct,
                    backendData.reservadas_pct,
                    backendData.canceladas_pct
                ],
                backgroundColor: ['#E83382', '#8b5cf6', '#f472b6'],
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '74%',
            animation: {
                animateRotate: true,
                duration: 1000
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    enabled: true,
                    backgroundColor: 'rgba(21, 21, 28, 0.98)',
                    titleColor: '#ffffff',
                    bodyColor: '#e4e4e7',
                    titleFont: { size: 12, weight: '700', family: 'Inter' },
                    bodyFont: { size: 12, family: 'Inter' },
                    borderColor: 'rgba(232, 51, 130, 0.5)',
                    borderWidth: 1,
                    padding: 10,
                    cornerRadius: 8,
                    displayColors: true,
                    boxWidth: 8,
                    boxHeight: 8,
                    usePointStyle: false,
                    callbacks: {
                        label(context) {
                            return ` ${context.label}: ${context.parsed}%`;
                        }
                    }
                },
                centerText: {
                    mainText: backendData.total.toString(),
                    subText: 'Total'
                }
            }
        }
    });
});
