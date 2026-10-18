<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Acceso Denegado | DS Beauty Academy</title>
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
            background: rgba(235, 87, 87, 0.12);
            border: 2px solid rgba(235, 87, 87, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            box-shadow: 0 0 35px rgba(235, 87, 87, 0.25);
            animation: pulseGlow 3s infinite ease-in-out;
        }

        .error-icon {
            font-size: 50px;
            color: #eb5757;
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
            color: #eb5757;
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
            margin-bottom: 28px;
            font-size: 15px;
            line-height: 1.6;
        }

        .btn-back {
            background: var(--accent);
            color: #fff;
            padding: 10px 24px;
            border-radius: 8px;
            font-weight: 500;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }

        .btn-back:hover {
            background: #d02270;
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(232, 51, 130, 0.4);
        }

        @keyframes pulseGlow {
            0%, 100% {
                transform: scale(1);
                box-shadow: 0 0 25px rgba(235, 87, 87, 0.2);
            }
            50% {
                transform: scale(1.05);
                box-shadow: 0 0 40px rgba(235, 87, 87, 0.4);
            }
        }
    </style>
</head>

<body class="dark-theme">
    <div class="app-container">
        <?php 
        if (file_exists(__DIR__ . '/../layouts/sidebar.php')) {
            include __DIR__ . '/../layouts/sidebar.php'; 
        }
        ?>
        <div class="main-content">
            <?php 
            if (file_exists(__DIR__ . '/../layouts/navbar.php')) {
                include __DIR__ . '/../layouts/navbar.php'; 
            }
            ?>
            <div class="error-container">
                <div class="error-glow">
                    <div class="error-icon-wrapper">
                        <i class="fas fa-shield-alt error-icon"></i>
                    </div>
                </div>
                <div class="error-code">4<span>0</span>3</div>
                <h1 class="error-title">Acceso Denegado</h1>
                <p class="error-desc">
                    Tu rol actual (<strong><?= htmlspecialchars($_SESSION['rol'] ?? 'Invitado') ?></strong>) no cuenta con los permisos necesarios para acceder a esta sección o recurso.
                </p>
                <a href="?url=dashboard" class="btn-back">
                    <i class="fas fa-arrow-left"></i> Volver al Dashboard
                </a>
            </div>
        </div>
    </div>
</body>

</html>
