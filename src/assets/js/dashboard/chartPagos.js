/**
 * ------------------------------------------------------------------
 * DS Beauty Academy - Gráfico Donut de Resumen de Pagos
 * Archivo: src/assets/js/dashboard/chartPagos.js
 * Descripción: Configuración del Donut con total central y tooltips
 * sobrepuestos de alta legibilidad para montos y porcentajes.
 * Conectado con la BD real mediante window.DASHBOARD_METRICS.
 * ------------------------------------------------------------------
 */

document.addEventListener('DOMContentLoaded', () => {
    const canvas = document.getElementById('chartResumenPagos');
    if (!canvas || typeof Chart === 'undefined') return;

    const backendData = window.DASHBOARD_METRICS?.resumen_pagos || {
        total: '$12,450',
        verificados_monto: '$9,750',
        verificados_pct: 78,
        pendientes_monto: '$1,850',
        pendientes_pct: 15,
        rechazados_monto: '$850',
        rechazados_pct: 7
    };

    new Chart(canvas.getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: ['Pagos verificados', 'Pagos pendientes', 'Pagos rechazados'],
            datasets: [{
                data: [
                    backendData.verificados_pct,
                    backendData.pendientes_pct,
                    backendData.rechazados_pct
                ],
                backgroundColor: ['#E83382', '#8b5cf6', '#fda4af'],
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
                            const montos = [
                                backendData.verificados_monto,
                                backendData.pendientes_monto,
                                backendData.rechazados_monto
                            ];
                            return ` ${context.label}: ${montos[context.dataIndex]} (${context.parsed}%)`;
                        }
                    }
                },
                centerText: {
                    mainText: backendData.total,
                    subText: 'Total'
                }
            }
        }
    });
});
