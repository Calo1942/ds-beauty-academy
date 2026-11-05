<!-- ======================================================== -->
<!-- COMPONENTE: CURSOS MÁS VENDIDOS                          -->
<!-- ======================================================== -->
<div class="dash-card">
    <div class="dash-card-header">
        <div class="dash-header-left">
            <div class="dash-header-icon">
                <i class="far fa-star"></i>
            </div>
            <div>
                <h2 class="dash-card-title">Cursos más vendidos</h2>
                <p class="dash-card-subtitle mb-0">Por cantidad de inscripciones</p>
            </div>
        </div>
    </div>

    <div class="dash-courses-list">
        <?php foreach ($metrics['cursos_mas_vendidos'] as $curso): ?>
            <div class="dash-course-item">
                <span class="dash-course-name" title="<?= htmlspecialchars($curso['nombre']) ?>">
                    <?= htmlspecialchars($curso['nombre']) ?>
                </span>
                <div class="dash-progress-track">
                    <div class="dash-progress-fill" data-width="<?= $curso['porcentaje'] ?>%"></div>
                </div>
                <span class="dash-course-count"><?= htmlspecialchars($curso['cantidad']) ?></span>
            </div>
        <?php endforeach; ?>
    </div>

    <a href="?url=curso" class="dash-more-link">
        Ver todos <i class="fas fa-arrow-right"></i>
    </a>
</div>

