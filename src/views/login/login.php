<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión — DS Beauty Academy</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        :root {
            --bg-dark: #0d0d11;
            --card-bg: rgba(24, 24, 30, 0.85);
            --accent: #E83382;
            --accent-hover: #d02270;
            --accent-glow: rgba(232, 51, 130, 0.35);
            --text-main: #f5f5f7;
            --text-muted: #8e8e9f;
            --input-bg: #131318;
            --border: #262633;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }

        body {
            background: radial-gradient(circle at 20% 20%, rgba(232, 51, 130, 0.12), transparent 40%),
                radial-gradient(circle at 80% 80%, rgba(232, 51, 130, 0.08), transparent 40%),
                var(--bg-dark);
            color: var(--text-main);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
        }

        .login-card {
            background: var(--card-bg);
            border: 1px solid var(--border);
            backdrop-filter: blur(12px);
            border-radius: 16px;
            width: 100%;
            max-width: 430px;
            padding: 42px 34px;
            box-shadow: 0 20px 45px rgba(0, 0, 0, 0.6);
            text-align: center;
            position: relative;
        }

        .brand-badge {
            display: inline-block;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: var(--accent);
            background: rgba(232, 51, 130, 0.12);
            padding: 5px 12px;
            border-radius: 20px;
            margin-bottom: 14px;
            font-weight: 600;
        }

        .brand-logo {
            font-size: 26px;
            font-weight: 700;
            letter-spacing: 1px;
            margin-bottom: 8px;
            color: var(--text-main);
        }

        .brand-logo span {
            color: var(--accent);
            font-weight: 300;
        }

        .subtitle {
            color: var(--text-muted);
            font-size: 13.5px;
            margin-bottom: 28px;
        }

        .form-group {
            text-align: left;
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            color: var(--text-muted);
            margin-bottom: 8px;
        }

        .input-wrap {
            position: relative;
        }

        .input-wrap i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 14px;
            transition: color 0.2s;
        }

        input {
            width: 100%;
            background: var(--input-bg);
            border: 1px solid var(--border);
            border-radius: 9px;
            padding: 13px 14px 13px 44px;
            color: #fff;
            font-size: 14px;
            outline: none;
            transition: border-color 0.25s, box-shadow 0.25s;
        }

        input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 12px var(--accent-glow);
        }

        input:focus+i,
        .input-wrap:focus-within i {
            color: var(--accent);
        }

        .btn-submit {
            width: 100%;
            background: var(--accent);
            color: #fff;
            border: none;
            border-radius: 9px;
            padding: 14px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 10px;
            transition: background 0.2s, transform 0.1s, box-shadow 0.2s;
            box-shadow: 0 4px 15px rgba(232, 51, 130, 0.3);
        }

        .btn-submit:hover {
            background: var(--accent-hover);
            box-shadow: 0 6px 20px rgba(232, 51, 130, 0.45);
        }

        .btn-submit:active {
            transform: scale(0.99);
        }

        .btn-submit:disabled {
            opacity: 0.65;
            cursor: not-allowed;
        }

        .alert-box {
            display: none;
            padding: 12px 14px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 20px;
            text-align: left;
            line-height: 1.4;
        }

        .alert-box.error {
            background: rgba(235, 87, 87, 0.12);
            border: 1px solid #eb5757;
            color: #ff7b7b;
        }

        .alert-box.success {
            background: rgba(39, 174, 96, 0.12);
            border: 1px solid #27ae60;
            color: #38d67a;
        }

        .footer-note {
            margin-top: 24px;
            font-size: 12px;
            color: var(--text-muted);
        }
    </style>
</head>

<body>

    <div class="login-card">
        <div class="brand-badge">Control de Acceso</div>
        <div class="brand-logo">DS <span>Beauty</span> Academy</div>
        <p class="subtitle">Ingresa tus credenciales autorizadas</p>

        <div id="alertBox" class="alert-box"></div>

        <form id="formLogin" autocomplete="off">
            <div class="form-group">
                <label for="email">Correo Electrónico</label>
                <div class="input-wrap">
                    <i class="fas fa-envelope"></i>
                    <input type="email" id="email" name="email" required placeholder="admin@beautyacademy.com"
                        autofocus>
                </div>
            </div>

            <div class="form-group">
                <label for="password">Contraseña</label>
                <div class="input-wrap">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="password" name="password" required placeholder="••••••••">
                </div>
            </div>

            <button type="submit" class="btn-submit" id="btnSubmit">
                <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
            </button>
        </form>

        <div class="footer-note">
            DS Beauty Academy <?= date('Y') ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.getElementById('formLogin').addEventListener('submit', async function (e) {
            e.preventDefault();
            const alertBox = document.getElementById('alertBox');
            const btn = document.getElementById('btnSubmit');

            alertBox.style.display = 'none';
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Verificando credenciales...';

            const formData = new FormData(this);

            try {
                const response = await fetch('?url=login', {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();

                if (data.status === 'success') {
                    alertBox.className = 'alert-box success';
                    alertBox.innerHTML = '<i class="fas fa-check-circle"></i> ' + data.message;
                    alertBox.style.display = 'block';

                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 600);
                } else {
                    alertBox.className = 'alert-box error';
                    alertBox.innerHTML = '<i class="fas fa-exclamation-circle"></i> ' + (data.message || 'Error al iniciar sesión.');
                    alertBox.style.display = 'block';
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-sign-in-alt"></i> Iniciar Sesión';
                }
            } catch (err) {
                alertBox.className = 'alert-box error';
                alertBox.innerHTML = '<i class="fas fa-wifi"></i> Error de conexión con el servidor.';
                alertBox.style.display = 'block';
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-sign-in-alt"></i> Iniciar Sesión';
            }
        });
    </script>

</body>

</html>