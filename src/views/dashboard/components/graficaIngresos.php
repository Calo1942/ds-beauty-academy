<!-- ======================================================== -->
<!-- COMPONENTE: GRÁFICA DE INGRESOS (DÍAS / MES)             -->
<!-- ======================================================== -->
<div class="dash-card">
    <div class="dash-card-header">
        <div class="dash-header-left">
            <div class="dash-header-icon">
                <i class="fas fa-chart-line"></i>
            </div>
            <div>
                <h2 class="dash-card-title">Ingresos</h2>
                <p class="dash-card-subtitle mb-0" id="dashIngresosSubtitle"><?= htmlspecialchars($metrics['ingresos_chart']['mes']['subtitleText']) ?></p>
            </div>
        </div>
        <div class="dash-pill-group">
            <button type="button" class="dash-pill-btn" data-period="dias">Días</button>
            <button type="button" class="dash-pill-btn active" data-period="mes">Mes</button>
        </div>
    </div>

    <!-- Canvas del Gráfico con Pin / Tooltip -->
    <div class="dash-chart-container">
        <div class="dash-chart-pin">
            <div class="dash-chart-pin-month"><?= htmlspecialchars($metrics['ingresos_chart']['mes']['pinLabel']) ?></div>
            <div class="dash-chart-pin-val"><?= htmlspecialchars($metrics['ingresos_chart']['mes']['pinVal']) ?></div>
        </div>
        <canvas id="chartIngresosMensuales"></canvas>
    </div>
</div>

