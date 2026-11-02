<?php

namespace DsBeautyAcademy\Helpers;

use PHPMailer\PHPMailer\PHPMailer;
use Throwable;

/**
 * ---------------------------------------------------------
 * Proyecto: DS Beauty Academy
 * Archivo: src/Helpers/CorreoTrait.php
 * Descripción:
 * Trait para inyectar capacidades de envío de correo electrónico
 * mediante PHPMailer en los modelos (UsuarioModel) sin crear
 * clases auxiliares independientes, respetando el diagrama de clases.
 * ---------------------------------------------------------
 */
trait CorreoTrait
{
    /**
     * Carga y almacena en caché la configuración de src/config/correo.php
     */
    public function obtenerConfiguracionCorreo(): array
    {
        static $config = null;
        if ($config === null) {
            $ruta = __DIR__ . '/../config/correo.php';
            $config = is_file($ruta) ? require $ruta : [];
        }

        if (!empty($config['clave'])) {
            $config['clave'] = preg_replace('/\s+/', '', (string) $config['clave']);
        }

        if (!empty($config['usuario'])) {
            $config['usuario'] = trim((string) $config['usuario']);
        }

        if (!empty($config['remitente'])) {
            $config['remitente'] = trim((string) $config['remitente']);
        }

        return $config;
    }

    /**
     * Verifica si el servicio SMTP se encuentra listo para enviar correos reales.
     */
    public function correoEstaConfigurado(): bool
    {
        $c = $this->obtenerConfiguracionCorreo();

        return !empty($c['activo'])
            && !empty($c['host'])
            && !empty($c['usuario'])
            && !empty($c['clave'])
            && strpos((string) $c['usuario'], 'tucorreo@') === false
            && strpos((string) $c['clave'], 'TU_APP_PASSWORD') === false;
    }

    /**
     * Envía el correo con el código de recuperación de 6 dígitos.
     *
     * @param string $email Correo de destino
     * @param string $nombre Nombre del destinatario
     * @param string $codigo Código numérico de 6 dígitos
     * @param int $vigencia Minutos de vigencia
     * @return array ['respuesta' => true] o ['error' => '...']
     */
    public function enviarCodigoRecuperacion(string $email, string $nombre, string $codigo, int $vigencia = 10): array
    {
        if (!$this->correoEstaConfigurado()) {
            return [
                'error' => 'El servicio de correo aún no está configurado en el servidor. Por favor, contacta al administrador.'
            ];
        }

        $c = $this->obtenerConfiguracionCorreo();

        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host       = $c['host'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $c['usuario'];
            $mail->Password   = $c['clave'];
            $mail->SMTPSecure = ($c['seguro'] === 'ssl')
                ? PHPMailer::ENCRYPTION_SMTPS
                : PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = (int) ($c['puerto'] ?? 587);
            $mail->CharSet    = 'UTF-8';

            $mail->setFrom($c['remitente'], $c['nombre'] ?? 'DS Beauty Academy');
            $mail->addAddress($email, $nombre !== '' ? $nombre : $email);

            $urlRestablecer = rtrim((string) ($c['urlBase'] ?? ''), '/') . '/?url=restablecer';

            $mail->isHTML(true);
            $mail->Subject = 'Código de Recuperación - DS Beauty Academy';
            $mail->Body    = $this->generarPlantillaCorreoRecuperacion($nombre, $codigo, $vigencia, $urlRestablecer);
            $mail->AltBody = "Hola " . ($nombre ?: 'Usuario') . ",\n\n"
                . "Tu código de recuperación es: " . $codigo . "\n\n"
                . "Vence en " . (int) $vigencia . " minutos y solo puede usarse una vez.\n"
                . "Ingrésalo en: " . $urlRestablecer . "\n\n"
                . "Si no solicitaste este código, ignora este mensaje.";

            $mail->send();
            return ['respuesta' => true];

        } catch (Throwable $e) {
            $msg = $e->getMessage();
            if (stripos($msg, 'authenticate') !== false || stripos($msg, '535') !== false) {
                $msg .= ' (Error de autenticación SMTP: Verifica la contraseña de aplicación de 16 caracteres en Google).';
            }
            error_log("DS Beauty Academy - Error PHPMailer: " . $msg);

            return ['error' => 'No se pudo enviar el correo de recuperación.'];
        }
    }

    /**
     * Genera la plantilla HTML con la estética visual Dark Luxury / Glow Pink de DS Beauty Academy.
     */
    private function generarPlantillaCorreoRecuperacion(string $nombre, string $codigo, int $vigencia, string $urlRestablecer): string
    {
        $nombreSeguro = htmlspecialchars($nombre ?: 'Estimada/o usuaria/o', ENT_QUOTES, 'UTF-8');
        $codigoSeguro = htmlspecialchars($codigo, ENT_QUOTES, 'UTF-8');
        $urlSegura    = htmlspecialchars($urlRestablecer, ENT_QUOTES, 'UTF-8');

        return '
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <title>Código de Recuperación</title>
        </head>
        <body style="margin: 0; padding: 0; background-color: #0b0b0e; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, Helvetica, Arial, sans-serif; color: #f5f5f7;">
            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color: #0b0b0e; padding: 30px 15px;">
                <tr>
                    <td align="center">
                        <table role="presentation" width="100%" style="max-width: 520px; background-color: #16161c; border-radius: 16px; border: 1px solid #2a2a38; box-shadow: 0 10px 30px rgba(0,0,0,0.5); overflow: hidden;" cellspacing="0" cellpadding="0">
                            <!-- Encabezado con línea de acento -->
                            <tr>
                                <td style="height: 4px; background: linear-gradient(90deg, #E83382, #ff6b9d);"></td>
                            </tr>
                            <tr>
                                <td style="padding: 32px 30px 20px 30px; text-align: center;">
                                    <div style="font-size: 11px; text-transform: uppercase; letter-spacing: 2px; color: #E83382; font-weight: 700; margin-bottom: 8px;">
                                        SEGURIDAD Y CONTROL DE ACCESO
                                    </div>
                                    <h1 style="margin: 0; font-size: 24px; font-weight: 700; color: #ffffff; letter-spacing: 0.5px;">
                                        DS <span style="color: #E83382; font-weight: 300;">Beauty</span> Academy
                                    </h1>
                                </td>
                            </tr>
                            <!-- Cuerpo -->
                            <tr>
                                <td style="padding: 10px 34px 20px 34px; color: #cfcfe1; font-size: 14.5px; line-height: 1.6;">
                                    <p style="margin: 0 0 14px 0;">Hola, <strong>' . $nombreSeguro . '</strong>:</p>
                                    <p style="margin: 0 0 20px 0; color: #9c9cb2;">
                                        Recibimos una solicitud para restablecer la contraseña de tu cuenta en la plataforma de <strong>DS Beauty Academy</strong>. Usa el siguiente código de verificación:
                                    </p>

                                    <!-- Caja del Código de 6 dígitos -->
                                    <div style="text-align: center; margin: 28px 0;">
                                        <div style="display: inline-block; background-color: #0d0d12; border: 2px dashed #E83382; border-radius: 12px; padding: 18px 32px; box-shadow: 0 0 25px rgba(232, 51, 130, 0.2);">
                                            <span style="font-family: monospace; font-size: 34px; font-weight: 800; letter-spacing: 10px; color: #ffffff; text-shadow: 0 0 10px rgba(232, 51, 130, 0.5); padding-left: 10px;">
                                                ' . $codigoSeguro . '
                                            </span>
                                        </div>
                                    </div>

                                    <p style="text-align: center; color: #8e8e9f; font-size: 13px; margin: 0 0 26px 0;">
                                        <span style="color: #E83382;">&#9201;</span> Este código vence en <strong>' . (int) $vigencia . ' minutos</strong> y es de un solo uso.
                                    </p>

                                    <!-- Botón de acción -->
                                    <div style="text-align: center; margin-bottom: 24px;">
                                        <a href="' . $urlSegura . '" style="display: inline-block; background-color: #E83382; color: #ffffff; text-decoration: none; padding: 13px 28px; border-radius: 8px; font-size: 14px; font-weight: 600; box-shadow: 0 4px 15px rgba(232, 51, 130, 0.4);">
                                            Restablecer mi Contraseña &rarr;
                                        </a>
                                    </div>

                                    <p style="margin: 20px 0 0 0; font-size: 12.5px; color: #767688; text-align: center;">
                                        Si no realizaste esta solicitud, puedes ignorar este mensaje; tu contraseña actual continuará siendo segura.
                                    </p>
                                </td>
                            </tr>
                            <!-- Pie -->
                            <tr>
                                <td style="padding: 20px 30px; background-color: #121217; border-top: 1px solid #242430; text-align: center; font-size: 11.5px; color: #6a6a7c;">
                                    &copy; ' . date('Y') . ' DS Beauty Academy. Todos los derechos reservados.
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </body>
        </html>
        ';
    }
}
