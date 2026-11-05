<!-- ======================================================== -->
<!-- COMPONENTE: RESUMEN DE PAGOS (DONUT)                     -->
<!-- ======================================================== -->
<div class="dash-card">
    <div class="dash-card-header">
        <div class="dash-header-left">
            <div class="dash-header-icon">
                <i class="far fa-credit-card"></i>
            </div>
            <div>
                <h2 class="dash-card-title">Resumen de pagos</h2>
                <p class="dash-card-subtitle mb-0">Estado de los comprobantes</p>
            </div>
        </div>
    </div>

    <div class="dash-donut-flex">
        <div class="dash-donut-canvas-box">
            <canvas id="chartResumenPagos"></canvas>
        </div>
        <div class="dash-legend-list">
            <div class="dash-legend-item">
                <span class="dash-legend-dot" style="background-color: var(--accent);"></span>
                <div>
                    <div class="dash-legend-label">Pagos verificados</div>
                    <div class="dash-legend-value" style="margin-left: 0;">
                        <?= htmlspecialchars($metrics['resumen_pagos']['verificados_monto']) ?> (<?= $metrics['resumen_pagos']['verificados_pct'] ?>%)
                    </div>
                </div>
            </div>
            <div class="dash-legend-item">
                <span class="dash-legend-dot" style="background-color: #8b5cf6;"></span>
                <div>
                    <div class="dash-legend-label">Pagos pendientes</div>
                    <div class="dash-legend-value" style="margin-left: 0;">
                        <?= htmlspecialchars($metrics['resumen_pagos']['pendientes_monto']) ?> (<?= $metrics['resumen_pagos']['pendientes_pct'] ?>%)
                    </div>
                </div>
            </div>
            <div class="dash-legend-item">
                <span class="dash-legend-dot" style="background-color: #fda4af;"></span>
                <div>
                    <div class="dash-legend-label">Pagos rechazados</div>
                    <div class="dash-legend-value" style="margin-left: 0;">
                        <?= htmlspecialchars($metrics['resumen_pagos']['rechazados_monto']) ?> (<?= $metrics['resumen_pagos']['rechazados_pct'] ?>%)
                    </div>
                </div>
            </div>
        </div>
    </div>

    <a href="?url=pago" class="dash-more-link">
        Ver detalles <i class="fas fa-arrow-right"></i>
    </a>
</div>
