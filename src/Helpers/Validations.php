<?php

namespace DsBeautyAcademy\Helpers;

trait Validations
{
    public static function validate_id($data)
    {
        return (preg_match('/^[0-9]{1,}$/', $data)) ? true : false;
    }

    public static function validate_number($data)
    {
        return (preg_match('/^[0-9]{1,}$/', $data)) ? true : false;
    }

    public static function validate_telefono($data)
    {
        return (preg_match('/^[0-9]{11}$/', $data)) ? true : false;
    }

    public static function validate_names($data)
    {
        return (preg_match('/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]{2,}$/', $data)) ? true : false;
    }

    public static function validate_text($data)
    {
        return (preg_match('/^[0-9A-Za-zÁÉÍÓÚáéíóúÑñ\s]{2,15}$/', $data)) ? true : false;
    }

    public static function validate_email($data)
    {
        return (preg_match('/^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/', $data)) ? true : false;
    }

    public static function validate_cedula($data)
    {
        return (preg_match('/^[0-9]{1,10}$/', $data)) ? true : false;
    }

    public static function validate_text_long($data)
    {
        return (preg_match('/^[0-9A-Za-zÁÉÍÓÚáéíóúÑñ\s.,!?\-]{2,100}$/', $data)) ? true : false;
    }

    public static function validate_text_very_long($data)
    {
        return (preg_match('/^[0-9A-Za-zÁÉÍÓÚáéíóúÑñ\s.,!?\-]{2,255}$/', $data)) ? true : false;
    }

    public static function validate_description($data)
    {
        return (preg_match('/^[0-9A-Za-zÁÉÍÓÚáéíóúÑñ\s.,!?\-()]{2,}$/', $data)) ? true : false;
    }

    public static function validate_decimal($data)
    {
        return (preg_match('/^\d+(\.\d{1,2})?$/', $data)) ? true : false;
    }

    public static function validate_boolean($data)
    {
        return in_array($data, [true, false, 1, 0, '1', '0'], true);
    }

    public static function validate_talla($data)
    {
        return (preg_match('/^[A-Za-z0-9]{1,5}$/', $data)) ? true : false;
    }

    public static function validate_color($data)
    {
        return (preg_match('/^[A-Za-zñÑáéíóúÁÉÍÓÚ\s]{2,50}$/', $data)) ? true : false;
    }

    public static function validate_estado($data)
    {
        $estados_validos = ['pendiente', 'aprobado', 'rechazado', 'en_proceso', 'completado', 'cancelado'];
        return in_array(strtolower($data), $estados_validos);
    }

    public static function validate_tipo_venta($data)
    {
        $tipos_validos = ['detalle', 'mayor', 'mixta'];
        return in_array(strtolower($data), $tipos_validos);
    }

    public static function validate_referencia($data)
    {
        return (preg_match('/^[A-Za-z0-9\-_]{5,100}$/', $data)) ? true : false;
    }

    public static function validate_nombre_archivo($data)
    {
        return (preg_match('/^[A-Za-z0-9\-_\.]{1,100}$/', $data)) ? true : false;
    }

    public static function validate_fecha($data)
    {
        return (preg_match('/^\d{4}-\d{2}-\d{2}$/', $data)) ? true : false;
    }

    public static function validate_datetime($data)
    {
        return (preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $data)) ? true : false;
    }

    public static function validate_stock($data)
    {
        return (preg_match('/^[0-9]{1,11}$/', $data) && $data >= 0) ? true : false;
    }

    public static function validate_cantidad($data)
    {
        return (preg_match('/^[0-9]{1,11}$/', $data) && $data > 0) ? true : false;
    }

    public static function validate_precio($data)
    {
        return (preg_match('/^\d+(\.\d{1,2})?$/', $data) && $data > 0) ? true : false;
    }

    public static function validate_cotizacion($data)
    {
        return (preg_match('/^\d+(\.\d{1,2})?$/', $data) && $data > 0) ? true : false;
    }
}
