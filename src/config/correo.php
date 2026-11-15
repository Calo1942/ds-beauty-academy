<?php

/**
 * ---------------------------------------------------------
 * Proyecto: DS Beauty Academy
 * Archivo: src/config/correo.php
 * Descripción:
 * Parámetros de configuración para el envío de correos electrónicos
 * mediante PHPMailer (recuperación de contraseña, notificaciones).
 *
 * NOTA: Las credenciales reales se cargan desde el archivo '.env'
 * en la raíz del proyecto para no exponerlas en repositorios públicos.
 * ---------------------------------------------------------
 */

return [
    'activo' => (bool) env('MAIL_ACTIVO', true),
    'host' => (string) env('MAIL_HOST', 'smtp.gmail.com'),
    'puerto' => (int) env('MAIL_PUERTO', 587),
    'usuario' => (string) env('MAIL_USUARIO', ''),
    'clave' => (string) env('MAIL_CLAVE', ''),
    'seguro' => (string) env('MAIL_SEGURO', 'tls'),
    'remitente' => (string) env('MAIL_REMITENTE', env('MAIL_USUARIO', '')),
    'nombre' => (string) env('MAIL_NOMBRE', 'DS Beauty Academy'),

    // Ruta base pública para enlaces en los correos
    'urlBase' => (string) env('MAIL_URL_BASE', 'http://localhost/proyectos/ds-beauty-academy'),

    // Tiempos de seguridad (en minutos y segundos)
    'vigencia' => (int) env('MAIL_VIGENCIA', 10), // Vigencia del código de 6 dígitos (10 min)
    'cooldown' => (int) env('MAIL_COOLDOWN', 60), // Espera mínima entre reenvíos (60 seg)
];
