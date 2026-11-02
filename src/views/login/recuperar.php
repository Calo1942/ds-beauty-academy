<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña — DS Beauty Academy</title>
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
            max-width: 440px;
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
            margin-bottom: 24px;
            line-height: 1.5;
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
            margin-top: 6px;
            transition: background 0.2s, transform 0.1s, box-shadow 0.2s;
            box-shadow: 0 4px 15px rgba(232, 51, 130, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
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
            padding: 13px 15px;
            border-radius: 9px;
            font-size: 13px;
            margin-bottom: 20px;
            text-align: left;
            line-height: 1.5;
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

        .links-group {
            margin-top: 24px;
            display: flex;
            flex-direction: column;
            gap: 12px;
            font-size: 13px;
        }

        .link-secondary {
            color: var(--text-muted);
            text-decoration: none;
            transition: color 0.2s;
        }

        .link-secondary:hover {
            color: #fff;
            text-decoration: underline;
        }

        .link-accent {
            color: var(--accent);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }

        .link-accent:hover {
            color: var(--accent-hover);
            text-decoration: underline;
        }

        .footer-note {
            margin-top: 26px;
            font-size: 12px;
            color: var(--text-muted);
        }
    </style>
</head>

<body>

    <div class="login-card">
        <div class="brand-badge">Paso 1 de 2</div>
        <div class="brand-logo">DS <span>Beauty</span> Academy</div>
        <p class="subtitle">
            Ingresa tu correo electrónico registrado y te enviaremos un código de seguridad de 6 dígitos.
        </p>

        <div id="alertBox" class="alert-box"></div>

        <form id="formRecuperar" autocomplete="off">
            <div class="form-group">
                <label for="correoRecuperar">Correo Electrónico</label>
                <div class="input-wrap">
                    <i class="fas fa-envelope"></i>
                    <input type="email" id="correoRecuperar" name="correoRecuperar" required
                        placeholder="tu-correo@ejemplo.com" autofocus maxlength="100">
                </div>
            </div>

            <button type="submit" class="btn-submit" id="btnSubmit">
                <i class="fas fa-paper-plane" id="btnIcon"></i>
                <span id="btnText">Enviar Código de 6 Dígitos</span>
            </button>
        </form>

        <div class="links-group">
            <a href="?url=restablecer" class="link-accent">
                <i class="fas fa-key me-1"></i> ¿Ya tienes un código? Ingrésalo aquí
            </a>
            <a href="?url=login" class="link-secondary">
                <i class="fas fa-arrow-left me-1"></i> Volver al Inicio de Sesión
            </a>
        </div>

        <div class="footer-note">
            DS Beauty Academy &copy; <?= date('Y') ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.getElementById('formRecuperar').addEventListener('submit', async function (e) {
            e.preventDefault();
            const alertBox = document.getElementById('alertBox');
            const btn = document.getElementById('btnSubmit');
            const btnText = document.getElementById('btnText');
            const btnIcon = document.getElementById('btnIcon');
            const inputCorreo = document.getElementById('correoRecuperar');

            const correo = inputCorreo.value.trim();
            const regexCorreo = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (!regexCorreo.test(correo)) {
                alertBox.className = 'alert-box error';
                alertBox.innerHTML = '<i class="fas fa-exclamation-circle"></i> Por favor ingresa un correo electrónico válido.';
                alertBox.style.display = 'block';
                inputCorreo.focus();
                return;
            }

            alertBox.style.display = 'none';
            btn.disabled = true;
            btnIcon.className = 'fas fa-spinner fa-spin';
            btnText.innerText = 'Enviando código...';

            const formData = new FormData();
            formData.append('correoRecuperar', correo);

            try {
                const response = await fetch('?url=recuperar', {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();

                if (data.estado || data.status === 'success') {
                    alertBox.className = 'alert-box success';
                    alertBox.innerHTML = `
                        <div style="margin-bottom: 10px;">
                            <i class="fas fa-check-circle"></i> <strong>${data.mensaje}</strong>
                        </div>
                        <div style="font-size: 12.5px; opacity: 0.9;">
                            Revisa tu bandeja de entrada o spam.
                        </div>
                        <div style="margin-top: 12px; text-align: center;">
                            <a href="?url=restablecer" style="display: inline-block; background: #27ae60; color: #fff; text-decoration: none; padding: 7px 16px; border-radius: 6px; font-weight: 600; font-size: 12.5px;">
                                Continuar al Paso 2: Escribir Código &rarr;
                            </a>
                        </div>
                    `;
                    alertBox.style.display = 'block';

                    btnText.innerText = 'Código Enviado';
                    btnIcon.className = 'fas fa-check';

                    // Redirigir suavemente tras 3 segundos si el usuario no pulsa el botón
                    setTimeout(() => {
                        window.location.href = '?url=restablecer';
                    }, 3500);

                } else {
                    alertBox.className = 'alert-box error';
                    alertBox.innerHTML = '<i class="fas fa-exclamation-circle"></i> ' + (data.mensaje || 'No se pudo procesar la solicitud.');
                    alertBox.style.display = 'block';

                    btn.disabled = false;
                    btnIcon.className = 'fas fa-paper-plane';
                    btnText.innerText = 'Enviar Código de 6 Dígitos';
                }
            } catch (err) {
                alertBox.className = 'alert-box error';
                alertBox.innerHTML = '<i class="fas fa-wifi"></i> Error de conexión con el servidor.';
                alertBox.style.display = 'block';

                btn.disabled = false;
                btnIcon.className = 'fas fa-paper-plane';
                btnText.innerText = 'Enviar Código de 6 Dígitos';
            }
        });
    </script>

</body>

</html>
