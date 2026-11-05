<!-- ======================================================== -->
<!-- COMPONENTE: GRÁFICA DE ESTADO DE LAS INSCRIPCIONES (DONUT)-->
<!-- ======================================================== -->
<div class="dash-card">
    <div class="dash-card-header">
        <div class="dash-header-left">
            <div class="dash-header-icon">
                <i class="fas fa-chart-pie"></i>
            </div>
            <div>
                <h2 class="dash-card-title">Estado de las inscripciones</h2>
            </div>
        </div>
    </div>

    <div class="dash-donut-flex">
        <div class="dash-donut-canvas-box">
            <canvas id="chartEstadoInscripciones"></canvas>
        </div>
        <div class="dash-legend-list">
            <div class="dash-legend-item">
                <span class="dash-legend-dot" style="background-color: var(--accent);"></span>
                <span class="dash-legend-label">Confirmadas</span>
                <span class="dash-legend-value"><?= htmlspecialchars($metrics['estado_inscripciones']['confirmadas_pct']) ?>%</span>
            </div>
            <div class="dash-legend-item">
                <span class="dash-legend-dot" style="background-color: #8b5cf6;"></span>
                <span class="dash-legend-label">Reservadas</span>
                <span class="dash-legend-value"><?= htmlspecialchars($metrics['estado_inscripciones']['reservadas_pct']) ?>%</span>
            </div>
            <div class="dash-legend-item">
                <span class="dash-legend-dot" style="background-color: #f472b6;"></span>
                <span class="dash-legend-label">Canceladas</span>
                <span class="dash-legend-value"><?= htmlspecialchars($metrics['estado_inscripciones']['canceladas_pct']) ?>%</span>
            </div>
        </div>
    </div>
</div>
