<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Centro de Reportes | DS Beauty Academy</title>
    <?php
    include_once __DIR__ . '/../../assets/linksFront.php';
    echo $css_links;
    ?>
    <style>
        .report-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(340px, 1fr));
            gap: 24px;
            margin-top: 20px;
        }

        .report-card {
            background: var(--card-bg, #18181c);
            border: 1px solid var(--border-color, #262633);
            border-radius: 14px;
            padding: 24px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: transform 0.25s, box-shadow 0.25s, border-color 0.25s;
        }

        .report-card:hover {
            transform: translateY(-3px);
            border-color: var(--accent, #E83382);
            box-shadow: 0 8px 25px rgba(232, 51, 130, 0.15);
        }

        .report-header {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 18px;
            padding-bottom: 16px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .report-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: rgba(232, 51, 130, 0.12);
            color: var(--accent, #E83382);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .report-title {
            font-size: 17px;
            font-weight: 700;
            color: #fff;
            margin: 0 0 4px 0;
        }

        .report-desc {
            font-size: 12.5px;
            color: var(--text-muted, #8e8e9f);
            margin: 0;
            line-height: 1.4;
        }

        .filter-group {
            margin-bottom: 14px;
        }

        .filter-group label {
            display: block;
            font-size: 11.5px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--text-muted, #8e8e9f);
            margin-bottom: 6px;
        }

        .filter-group input,
        .filter-group select {
            width: 100%;
            background: #121217;
            border: 1px solid #282836;
            color: #fff;
            border-radius: 8px;
            padding: 9px 12px;
            font-size: 13px;
            outline: none;
            transition: border-color 0.2s;
        }

        .filter-group input:focus,
        .filter-group select:focus {
            border-color: var(--accent, #E83382);
        }

        .date-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .btn-generate {
            width: 100%;
            background: var(--accent, #E83382);
            color: #fff;
            border: none;
            border-radius: 9px;
            padding: 12px;
            font-size: 13.5px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 10px;
            transition: background 0.2s, box-shadow 0.2s;
            box-shadow: 0 4px 14px rgba(232, 51, 130, 0.25);
            text-decoration: none;
        }

        .btn-generate:hover {
            background: #d02270;
            box-shadow: 0 6px 18px rgba(232, 51, 130, 0.4);
            color: #fff;
        }
    </style>
</head>

<body>
    <!-- Sidebar de navegación -->
    <?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>

    <!-- Navbar superior (abre el .main-wrapper) -->
    <?php require_once __DIR__ . '/../layouts/navbar.php'; ?>

    <main class="content">
        <div class="page-header">
            <div>
                <h1 class="page-title">Centro de Reportes y Documentos Oficiales</h1>
                <p class="text-muted" style="font-size: 13.5px; margin-top: 4px;">
                    Generación de estados contables, estadísticas académicas y emisión de certificados en formato PDF con <strong>FPDF</strong>.
                </p>
            </div>
        </div>

        <div class="report-grid">
            <!-- 1. Reporte de Caja y Conciliación -->
            <div class="report-card">
                <div>
                    <div class="report-header">
                        <div class="report-icon">
                            <i class="fas fa-file-invoice-dollar"></i>
                        </div>
                        <div>
                            <h3 class="report-title">Caja y Conciliación Bancaria</h3>
                            <p class="report-desc">Auditoría de ingresos, depósitos y estatus de cobros.</p>
                        </div>
                    </div>

                    <form action="?url=reporte" method="GET" target="_blank">
                        <input type="hidden" name="url" value="reporte">
                        <input type="hidden" name="action" value="pdf">
                        <input type="hidden" name="tipo" value="caja">

                        <div class="filter-group">
                            <label>Rango de Fechas</label>
                            <div class="date-row">
                                <input type="date" name="fecha_desde" title="Desde">
                                <input type="date" name="fecha_hasta" title="Hasta">
                            </div>
                        </div>

                        <div class="filter-group">
                            <label>Entidad Bancaria</label>
                            <select name="id_banco">
                                <option value="">-- Todos los bancos --</option>
                                <?php foreach ($bancos as $b): ?>
                                    <option value="<?= htmlspecialchars($b['id_banco']) ?>">
                                        <?= htmlspecialchars($b['nombre_banco']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="filter-group">
                            <label>Estatus del Comprobante</label>
                            <select name="estatus_comprobante">
                                <option value="">-- Todos los estados --</option>
                                <option value="Verificado">Verificado (Aprobado)</option>
                                <option value="Pendiente">Pendiente de Verificación</option>
                                <option value="Rechazado">Rechazado</option>
                            </select>
                        </div>

                        <button type="submit" class="btn-generate">
                            <i class="fas fa-file-pdf"></i> Generar Reporte de Caja
                        </button>
                    </form>
                </div>
            </div>

            <!-- 2. Reporte Académico de Matrículas -->
            <div class="report-card">
                <div>
                    <div class="report-header">
                        <div class="report-icon">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <div>
                            <h3 class="report-title">Matrículas y Aforo Académico</h3>
                            <p class="report-desc">Listas de inscritos, ocupación de cupos y solvencias.</p>
                        </div>
                    </div>

                    <form action="?url=reporte" method="GET" target="_blank">
                        <input type="hidden" name="url" value="reporte">
                        <input type="hidden" name="action" value="pdf">
                        <input type="hidden" name="tipo" value="matriculas">

                        <div class="filter-group">
                            <label>Curso / Masterclass</label>
                            <select name="id_curso">
                                <option value="">-- Todos los cursos activos --</option>
                                <?php foreach ($cursos as $c): ?>
                                    <option value="<?= htmlspecialchars($c['id_curso']) ?>">
                                        <?= htmlspecialchars($c['nombre_curso']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="filter-group">
                            <label>Estatus del Cupo</label>
                            <select name="estatus_cupo">
                                <option value="">-- Todos los cupos --</option>
                                <option value="Confirmado">Confirmado (Con Cupo)</option>
                                <option value="Reservado">Reservado (En Espera)</option>
                                <option value="Cancelado">Cancelado</option>
                            </select>
                        </div>

                        <div class="filter-group">
                            <label>Estatus Financiero del Alumno</label>
                            <select name="estatus_pago">
                                <option value="">-- Todas las situaciones --</option>
                                <option value="Solvente">Solvente (Pagado)</option>
                                <option value="Deudora">Deudora (Saldo pendiente)</option>
                            </select>
                        </div>

                        <button type="submit" class="btn-generate">
                            <i class="fas fa-file-pdf"></i> Generar Reporte de Matrículas
                        </button>
                    </form>
                </div>
            </div>

            <!-- 3. Reporte de Certificaciones -->
            <div class="report-card">
                <div>
                    <div class="report-header">
                        <div class="report-icon">
                            <i class="fas fa-award"></i>
                        </div>
                        <div>
                            <h3 class="report-title">Historial de Certificaciones</h3>
                            <p class="report-desc">Trazabilidad de diplomas y certificados otorgados.</p>
                        </div>
                    </div>

                    <form action="?url=reporte" method="GET" target="_blank">
                        <input type="hidden" name="url" value="reporte">
                        <input type="hidden" name="action" value="pdf">
                        <input type="hidden" name="tipo" value="certificados">

                        <div class="filter-group">
                            <label>Curso Asociado</label>
                            <select name="id_curso">
                                <option value="">-- Todos los cursos --</option>
                                <?php foreach ($cursos as $c): ?>
                                    <option value="<?= htmlspecialchars($c['id_curso']) ?>">
                                        <?= htmlspecialchars($c['nombre_curso']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="filter-group">
                            <label>Estatus del Certificado</label>
                            <select name="estatus_certificado">
                                <option value="">-- Todos --</option>
                                <option value="Emitido">Emitido</option>
                                <option value="Pendiente de Emisión">Pendiente de Emisión</option>
                            </select>
                        </div>

                        <div style="height: 60px;"></div> <!-- Espaciador para alinear botones -->

                        <button type="submit" class="btn-generate">
                            <i class="fas fa-file-pdf"></i> Generar Listado de Certificados
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <!-- Cierre de main-wrapper -->
    </div>

    <?php echo $scripts_links; ?>
</body>

</html>
