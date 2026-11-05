<!-- ======================================================== -->
<!-- COMPONENTE: TARJETAS KPI (METRIC CARDS)                  -->
<!-- ======================================================== -->
<div class="row g-3 g-lg-4 mb-4">
    
    <!-- KPI 1: Estudiantes activos -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="dash-card dash-kpi-card">
            <div class="dash-kpi-layout">
                <div class="dash-icon-badge">
                    <i class="fas fa-users"></i>
                </div>
                <div class="dash-kpi-info">
                    <span class="dash-kpi-title">Estudiantes activos</span>
                    <span class="dash-kpi-value"><?= htmlspecialchars($metrics['kpi']['estudiantes_activos']) ?></span>
                    <span class="dash-kpi-trend">
                        <i class="fas fa-arrow-up" style="font-size: 11px;"></i> <?= htmlspecialchars($metrics['kpi']['estudiantes_trend']) ?>
                    </span>
                </div>
            </div>
            <!-- Mini Sparkline Área (Curva) -->
            <div class="dash-sparkline-box">
                <svg viewBox="0 0 100 40" class="dash-sparkline-svg" preserveAspectRatio="none">
                    <defs>
                        <linearGradient id="sparkAreaGrad" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0%" stop-color="#E83382" stop-opacity="0.45"/>
                            <stop offset="100%" stop-color="#E83382" stop-opacity="0.0"/>
                        </linearGradient>
                    </defs>
                    <path d="M0 32 Q25 35 45 22 T80 25 T100 8 L100 40 L0 40 Z" fill="url(#sparkAreaGrad)"/>
                    <path d="M0 32 Q25 35 45 22 T80 25 T100 8" fill="none" stroke="#E83382" stroke-width="2.5" stroke-linecap="round"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- KPI 2: Ingresos del mes -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="dash-card dash-kpi-card">
            <div class="dash-kpi-layout">
                <div class="dash-icon-badge">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="dash-kpi-info">
                    <span class="dash-kpi-title">Ingresos del mes</span>
                    <span class="dash-kpi-value"><?= htmlspecialchars($metrics['kpi']['ingresos_mes']) ?></span>
                    <span class="dash-kpi-trend">
                        <i class="fas fa-arrow-up" style="font-size: 11px;"></i> <?= htmlspecialchars($metrics['kpi']['ingresos_trend']) ?>
                    </span>
                </div>
            </div>
            <!-- Mini Sparkline Barras -->
            <div class="dash-sparkline-box">
                <svg viewBox="0 0 75 40" class="dash-sparkline-svg">
                    <rect x="5" y="29" width="5.5" height="11" rx="2.5" fill="#E83382" opacity="0.3"/>
                    <rect x="15" y="25" width="5.5" height="15" rx="2.5" fill="#E83382" opacity="0.45"/>
                    <rect x="25" y="22" width="5.5" height="18" rx="2.5" fill="#E83382" opacity="0.55"/>
                    <rect x="35" y="16" width="5.5" height="24" rx="2.5" fill="#E83382" opacity="0.7"/>
                    <rect x="45" y="12" width="5.5" height="28" rx="2.5" fill="#E83382" opacity="0.85"/>
                    <rect x="55" y="7" width="5.5" height="33" rx="2.5" fill="#E83382" opacity="0.95"/>
                    <rect x="65" y="2" width="5.5" height="38" rx="2.5" fill="#E83382"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- KPI 3: Inscripciones confirmadas -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="dash-card dash-kpi-card">
            <div class="dash-kpi-layout">
                <div class="dash-icon-badge">
                    <i class="fas fa-check"></i>
                </div>
                <div class="dash-kpi-info">
                    <span class="dash-kpi-title">Inscripciones confirmadas</span>
                    <span class="dash-kpi-value"><?= htmlspecialchars($metrics['kpi']['inscripciones_confirmadas']) ?></span>
                    <span class="dash-kpi-trend">
                        <i class="fas fa-arrow-up" style="font-size: 11px;"></i> <?= htmlspecialchars($metrics['kpi']['inscripciones_trend']) ?>
                    </span>
                </div>
            </div>
            <!-- Mini Sparkline Barras en Onda -->
            <div class="dash-sparkline-box">
                <svg viewBox="0 0 75 40" class="dash-sparkline-svg">
                    <rect x="5" y="29" width="5.5" height="11" rx="2.5" fill="#E83382" opacity="0.35"/>
                    <rect x="15" y="25" width="5.5" height="15" rx="2.5" fill="#E83382" opacity="0.5"/>
                    <rect x="25" y="22" width="5.5" height="18" rx="2.5" fill="#E83382" opacity="0.6"/>
                    <rect x="35" y="16" width="5.5" height="24" rx="2.5" fill="#E83382" opacity="0.75"/>
                    <rect x="45" y="10" width="5.5" height="30" rx="2.5" fill="#E83382" opacity="0.88"/>
                    <rect x="55" y="5" width="5.5" height="35" rx="2.5" fill="#E83382" opacity="0.95"/>
                    <rect x="65" y="2" width="5.5" height="38" rx="2.5" fill="#E83382"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- KPI 4: Cupos alcanzados -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="dash-card dash-kpi-card">
            <div class="dash-kpi-layout">
                <div class="dash-icon-badge">
                    <i class="fas fa-trophy"></i>
                </div>
                <div class="dash-kpi-info">
                    <span class="dash-kpi-title">Cupos alcanzados</span>
                    <span class="dash-kpi-value"><?= htmlspecialchars($metrics['kpi']['cupos_alcanzados_pct']) ?>%</span>
                    <span class="dash-kpi-subtitle"><?= htmlspecialchars($metrics['kpi']['cupos_alcanzados_detalle']) ?></span>
                </div>
            </div>
            <!-- Anillo de Progreso Radial (78%) -->
            <div class="dash-radial-container">
                <svg viewBox="0 0 54 54" class="dash-radial-svg">
                    <circle cx="27" cy="27" r="22" class="dash-radial-track" />
                    <circle cx="27" cy="27" r="22" class="dash-radial-fill" 
                            data-percent="<?= htmlspecialchars($metrics['kpi']['cupos_alcanzados_pct']) ?>" />
                </svg>
            </div>
        </div>
    </div>

</div>

