<!-- ======================================================== -->
<!-- COMPONENTE: SKELETON LOADER PARA EL DASHBOARD            -->
<!-- ======================================================== -->
<div id="dashboardSkeleton" class="skeleton-dashboard">
    <!-- FILA 1: 4 Tarjetas KPI -->
    <div class="row g-3 g-lg-4 mb-4">
        <?php for ($i = 0; $i < 4; $i++): ?>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="skeleton-card" style="min-height: 120px; padding: 20px;">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="skeleton-cell" style="width: 48px; height: 48px; border-radius: 12px; flex: none;"></div>
                        <div class="d-flex flex-column gap-2 flex-grow-1">
                            <div class="skeleton-cell" style="height: 14px; width: 60%;"></div>
                            <div class="skeleton-cell" style="height: 22px; width: 45%;"></div>
                        </div>
                    </div>
                    <div class="skeleton-cell" style="height: 12px; width: 35%;"></div>
                </div>
            </div>
        <?php endfor; ?>
    </div>

    <!-- FILA 2: Gráfico de Ingresos + Cursos Más Vendidos -->
    <div class="row g-3 g-lg-4 mb-4">
        <div class="col-12 col-xl-8">
            <div class="skeleton-card" style="min-height: 380px; padding: 24px;">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="skeleton-cell" style="height: 20px; width: 220px; flex: none;"></div>
                    <div class="skeleton-cell" style="height: 32px; width: 120px; flex: none; border-radius: 8px;"></div>
                </div>
                <div class="skeleton-cell" style="height: 260px; border-radius: 10px;"></div>
            </div>
        </div>
        <div class="col-12 col-xl-4">
            <div class="skeleton-card" style="min-height: 380px; padding: 24px;">
                <div class="skeleton-cell mb-4" style="height: 20px; width: 180px; flex: none;"></div>
                <?php for ($k = 0; $k < 4; $k++): ?>
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="skeleton-cell" style="width: 40px; height: 40px; border-radius: 8px; flex: none;"></div>
                        <div class="d-flex flex-column gap-2 flex-grow-1">
                            <div class="skeleton-cell" style="height: 14px; width: 75%;"></div>
                            <div class="skeleton-cell" style="height: 11px; width: 40%;"></div>
                        </div>
                        <div class="skeleton-cell" style="height: 16px; width: 50px; flex: none;"></div>
                    </div>
                <?php endfor; ?>
            </div>
        </div>
    </div>

    <!-- FILA 3: 3 Gráficos Inferiores -->
    <div class="row g-3 g-lg-4">
        <?php for ($j = 0; $j < 3; $j++): ?>
            <div class="col-12 col-md-6 col-lg-4">
                <div class="skeleton-card" style="min-height: 300px; padding: 22px;">
                    <div class="skeleton-cell mb-4" style="height: 18px; width: 160px; flex: none;"></div>
                    <div class="d-flex justify-content-center align-items-center" style="height: 180px;">
                        <div class="skeleton-cell" style="width: 140px; height: 140px; border-radius: 50%; flex: none;"></div>
                    </div>
                </div>
            </div>
        <?php endfor; ?>
    </div>
</div>
