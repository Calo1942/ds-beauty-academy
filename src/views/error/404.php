<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - En Desarrollo | DS Beauty Academy</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="src/assets/bootstrap-5.3.8/css/bootstrap.min.css">
    <link rel="stylesheet" href="src/views/styles.css">
    <style>
        .error-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: calc(100vh - 120px);
            text-align: center;
            padding: 40px 20px;
        }

        .error-glow {
            position: relative;
            margin-bottom: 24px;
        }

        .error-icon-wrapper {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: rgba(232, 51, 130, 0.1);
            border: 2px solid rgba(232, 51, 130, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            box-shadow: 0 0 35px rgba(232, 51, 130, 0.25);
            animation: pulseGlow 3s infinite ease-in-out;
        }

        .error-icon {
            font-size: 50px;
            color: var(--accent);
        }

        .error-code {
            font-size: 64px;
            font-weight: 700;
            color: var(--text-main);
            letter-spacing: -2px;
            margin-bottom: 8px;
            font-family: 'The Seasons', serif;
        }

        .error-code span {
            color: var(--accent);
        }

        .error-title {
            font-size: 24px;
            font-weight: 600;
            color: var(--text-main);
            margin-bottom: 12px;
        }

        .error-desc {
            color: var(--text-muted);
            max-width: 480px;
            font-size: 15px;
            line-height: 1.6;
            margin-bottom: 32px;
        }

        .error-badge {
            display: inline-block;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--border-color);
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 13px;
            color: var(--accent);
            margin-bottom: 20px;
            font-weight: 500;
        }

        @keyframes pulseGlow {
            0%, 100% {
                transform: scale(1);
                box-shadow: 0 0 25px rgba(232, 51, 130, 0.2);
            }
            50% {
                transform: scale(1.05);
                box-shadow: 0 0 45px rgba(232, 51, 130, 0.4);
            }
        }
    </style>
</head>

<body>
    <?php 
    if (file_exists(__DIR__ . '/../layouts/sidebar.php')) {
        require_once __DIR__ . '/../layouts/sidebar.php'; 
    }
    if (file_exists(__DIR__ . '/../layouts/navbar.php')) {
        require_once __DIR__ . '/../layouts/navbar.php'; 
    }
    ?>

    <main class="content">
        <div class="error-container">
            <div class="error-glow">
                <div class="error-icon-wrapper">
                    <i class="fas fa-magic error-icon"></i>
                </div>
            </div>

            <div class="error-badge">
                <i class="fas fa-sparkles"></i> Módulo en Construcción
            </div>

            <h1 class="error-code">4<span>0</span>4</h1>
            <h2 class="error-title">Estamos trabajando en ello</h2>
            <p class="error-desc">
                Esta sección está siendo perfeccionada con los más altos estándares de belleza y tecnología. Muy pronto estará disponible para potenciar la experiencia académica.
            </p>

            <div>
                <a href="?url=dashboard" class="btn btn-primary" style="padding: 12px 28px; font-weight: 500;">
                    <i class="fas fa-arrow-left me-2"></i> Regresar al Dashboard
                </a>
            </div>
        </div>
    </main>
    </div>

    <script src="src/assets/bootstrap-5.3.8/js/bootstrap.bundle.min.js"></script>
</body>
</html>
