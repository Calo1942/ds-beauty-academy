<!-- ======================================================== -->
<!-- COMPONENTE: CUPOS ALCANZADOS POR CURSO                   -->
<!-- ======================================================== -->
<div class="dash-card">
    <div class="dash-card-header">
        <div class="dash-header-left">
            <div class="dash-header-icon">
                <i class="fas fa-chart-bar"></i>
            </div>
            <div>
                <h2 class="dash-card-title">Cupos alcanzados</h2>
                <p class="dash-card-subtitle mb-0">Disponibilidad en tus cursos</p>
            </div>
        </div>
    </div>

    <div class="dash-cupos-list">
        <?php foreach ($metrics['cupos_cursos'] as $cupo): ?>
            <div class="dash-cupos-item">
                <div class="dash-cupos-header">
                    <span class="dash-cupos-name"><?= htmlspecialchars($cupo['nombre']) ?></span>
                    <span class="dash-cupos-percent"><?= $cupo['porcentaje'] ?>%</span>
                </div>
                <span class="dash-cupos-sub"><?= $cupo['ocupados'] ?> / <?= $cupo['total'] ?></span>
                <div class="dash-progress-track">
                    <div class="dash-progress-fill" data-width="<?= $cupo['porcentaje'] ?>%"></div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

