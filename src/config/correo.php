<?php

/**
 * ---------------------------------------------------------
 * Proyecto: DS Beauty Academy
 * Archivo: src/config/correo.php
 * Descripción:
 * Parámetros de configuración para el envío de correos
 * electrónicos mediante PHPMailer (recuperación de contraseña,
 * alertas del sistema, notificaciones).
 * ---------------------------------------------------------
 */

return [
    // Poner activo en true y completar host/usuario/clave/remitente.
    // Gmail exige una App Password de 16 caracteres si la cuenta tiene
    // verificación en 2 pasos activa (https://myaccount.google.com/apppasswords).
    'activo'    => true,
    'host'      => 'smtp.gmail.com',
    'puerto'    => 587,
    'usuario'   => 'dsbeautyacademy.soporte@gmail.com',
    'clave'     => 'TU_APP_PASSWORD_DE_16',
    'seguro'    => 'tls',
    'remitente' => 'dsbeautyacademy.soporte@gmail.com',
    'nombre'    => 'DS Beauty Academy',
    
    // Ruta base pública para enlaces en los correos
    'urlBase'   => 'http://localhost/proyectos/ds-beauty-academy',
    
    // Tiempos de seguridad (en minutos y segundos)
    'vigencia'  => 10, // Vigencia del código de 6 dígitos (10 min)
    'cooldown'  => 60, // Espera mínima entre reenvíos (60 seg)
];
