<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Contraseña — DS Beauty Academy</title>
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
            max-width: 450px;
            padding: 38px 34px;
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
            margin-bottom: 12px;
            font-weight: 600;
        }

        .brand-logo {
            font-size: 26px;
            font-weight: 700;
            letter-spacing: 1px;
            margin-bottom: 6px;
            color: var(--text-main);
        }

        .brand-logo span {
            color: var(--accent);
            font-weight: 300;
        }

        .subtitle {
            color: var(--text-muted);
            font-size: 13px;
            margin-bottom: 22px;
            line-height: 1.45;
        }

        .form-group {
            text-align: left;
            margin-bottom: 16px;
        }

        label {
            display: block;
            font-size: 11.5px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            color: var(--text-muted);
            margin-bottom: 7px;
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
            padding: 12px 14px 12px 42px;
            color: #fff;
            font-size: 13.5px;
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

        /* Estilo especial para el código de 6 dígitos */
        input.code-input {
            font-family: 'Courier New', Courier, monospace;
            font-size: 19px;
            font-weight: 700;
            letter-spacing: 7px;
            text-align: center;
            padding-left: 20px;
            padding-right: 20px;
        }

        .btn-submit {
            width: 100%;
            background: var(--accent);
            color: #fff;
            border: none;
            border-radius: 9px;
            padding: 14px;
            font-size: 14.5px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 10px;
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
            margin-bottom: 18px;
            text-align: left;
            line-height: 1.45;
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
            margin-top: 22px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            font-size: 12.5px;
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

        .footer-note {
            margin-top: 24px;
            font-size: 11.5px;
            color: var(--text-muted);
        }
    </style>
</head>

<body>

    <div class="login-card">
        <div class="brand-badge">Paso 2 de 2</div>
        <div class="brand-logo">DS <span>Beauty</span> Academy</div>
        <p class="subtitle">
            Ingresa el código de 6 dígitos recibido por correo y establece tu nueva contraseña.
        </p>

        <div id="alertBox" class="alert-box"></div>

        <form id="formRestablecer" autocomplete="off">
            <!-- Correo Electrónico -->
            <div class="form-group">
                <label for="correoRecuperar">Correo Electrónico</label>
                <div class="input-wrap">
                    <i class="fas fa-envelope"></i>
                    <input type="email" id="correoRecuperar" name="correoRecuperar" required
                        placeholder="tu-correo@ejemplo.com" maxlength="100"
                        value="<?= htmlspecialchars($correoPrellenado ?? '', ENT_QUOTES, 'UTF-8') ?>">
                </div>
            </div>

            <!-- Código de 6 dígitos -->
            <div class="form-group">
                <label for="codigoRecuperar">Código de Verificación (6 dígitos)</label>
                <div class="input-wrap">
                    <input type="text" id="codigoRecuperar" name="codigoRecuperar" required
                        placeholder="••••••" maxlength="6" inputmode="numeric"
                        pattern="[0-9]{6}" autocomplete="one-time-code" class="code-input" autofocus>
                </div>
                <small style="color: var(--text-muted); font-size: 11px; display: block; margin-top: 5px;">
                    El código vence en 10 minutos y solo puede utilizarse una vez.
                </small>
            </div>

            <!-- Nueva Contraseña -->
            <div class="form-group">
                <label for="nuevaClave">Nueva Contraseña</label>
                <div class="input-wrap">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="nuevaClave" name="nuevaClave" required
                        placeholder="Mínimo 8 caracteres (letras y números)" maxlength="72">
                </div>
            </div>

            <!-- Confirmación de Contraseña -->
            <div class="form-group">
                <label for="confirmarClave">Confirmar Nueva Contraseña</label>
                <div class="input-wrap">
                    <i class="fas fa-check-double"></i>
                    <input type="password" id="confirmarClave" name="confirmarClave" required
                        placeholder="Repite la contraseña" maxlength="72">
                </div>
            </div>

            <button type="submit" class="btn-submit" id="btnSubmit">
                <i class="fas fa-save" id="btnIcon"></i>
                <span id="btnText">Guardar Nueva Contraseña</span>
            </button>
        </form>

        <div class="links-group">
            <a href="?url=recuperar" class="link-secondary">
                <i class="fas fa-redo-alt me-1"></i> Solicitar otro código
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
        // Permitir únicamente dígitos numéricos en el campo de código
        const inputCodigo = document.getElementById('codigoRecuperar');
        inputCodigo.addEventListener('input', function () {
            this.value = this.value.replace(/\D/g, '').slice(0, 6);
        });

        document.getElementById('formRestablecer').addEventListener('submit', async function (e) {
            e.preventDefault();
            const alertBox = document.getElementById('alertBox');
            const btn = document.getElementById('btnSubmit');
            const btnText = document.getElementById('btnText');
            const btnIcon = document.getElementById('btnIcon');

            const correo = document.getElementById('correoRecuperar').value.trim();
            const codigo = inputCodigo.value.trim();
            const clave = document.getElementById('nuevaClave').value;
            const confirmacion = document.getElementById('confirmarClave').value;

            // Validaciones del lado del cliente
            if (!correo) {
                mostrarError('Por favor ingresa tu correo electrónico.');
                document.getElementById('correoRecuperar').focus();
                return;
            }

            if (!/^[0-9]{6}$/.test(codigo)) {
                mostrarError('El código de verificación debe contener exactamente 6 dígitos.');
                inputCodigo.focus();
                return;
            }

            const regexClave = /^(?=.*[A-Za-z])(?=.*\d).{8,72}$/;
            if (!regexClave.test(clave)) {
                mostrarError('La contraseña debe tener al menos 8 caracteres e incluir letras y números.');
                document.getElementById('nuevaClave').focus();
                return;
            }

            if (clave !== confirmacion) {
                mostrarError('Las contraseñas no coinciden. Por favor verifícalas.');
                document.getElementById('confirmarClave').focus();
                return;
            }

            alertBox.style.display = 'none';
            btn.disabled = true;
            btnIcon.className = 'fas fa-spinner fa-spin';
            btnText.innerText = 'Validando y guardando...';

            const formData = new FormData(this);

            try {
                const response = await fetch('?url=restablecer', {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();

                if (data.estado || data.status === 'success') {
                    alertBox.className = 'alert-box success';
                    alertBox.innerHTML = '<i class="fas fa-check-circle"></i> ' + data.mensaje;
                    alertBox.style.display = 'block';

                    btnText.innerText = '¡Contraseña Actualizada!';
                    btnIcon.className = 'fas fa-check';

                    setTimeout(() => {
                        window.location.href = '?url=login';
                    }, 1800);

                } else {
                    mostrarError(data.mensaje || 'No se pudo restablecer la contraseña.');
                    btn.disabled = false;
                    btnIcon.className = 'fas fa-save';
                    btnText.innerText = 'Guardar Nueva Contraseña';
                }
            } catch (err) {
                mostrarError('Error de comunicación con el servidor.');
                btn.disabled = false;
                btnIcon.className = 'fas fa-save';
                btnText.innerText = 'Guardar Nueva Contraseña';
            }

            function mostrarError(mensaje) {
                alertBox.className = 'alert-box error';
                alertBox.innerHTML = '<i class="fas fa-exclamation-circle"></i> ' + mensaje;
                alertBox.style.display = 'block';
            }
        });
    </script>

</body>

</html>
