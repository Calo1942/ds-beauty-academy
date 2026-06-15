<?php

namespace DsBeautyAcademy\Helpers;

trait Validations
{
    public $validate_id = '/^[0-9]{1,}$/';
    public $validate_number = '/^[0-9]{1,}$/';
    public $validate_telefono = '/^[0-9]{11}$/';
    public $validate_names = '/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]{2,}$/';
    public $validate_text = '/^[0-9A-Za-zÁÉÍÓÚáéíóúÑñ\s]{2,15}$/';
    public $validate_email = '/^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/';
    public $validate_cedula = '/^[0-9]{1,10}$/';
    public $validate_text_long = '/^[0-9A-Za-zÁÉÍÓÚáéíóúÑñ\s.,!?\-]{2,100}$/';
    public $validate_text_very_long = '/^[0-9A-Za-zÁÉÍÓÚáéíóúÑñ\s.,!?\-]{2,255}$/';
    public $validate_description = '/^[0-9A-Za-zÁÉÍÓÚáéíóúÑñ\s.,!?\-()]{2,}$/';
    public $validate_decimal = '/^\d+(\.\d{1,2})?$/';
    public $validate_boolean = [true, false, 1, 0, '1', '0'];
    public $validate_talla = '/^[A-Za-z0-9]{1,5}$/';
    public $validate_color = '/^[A-Za-zñÑáéíóúÁÉÍÓÚ\s]{2,50}$/';
    public $validate_referencia = '/^[A-Za-z0-9\-_]{5,100}$/';
    public $validate_nombre_archivo = '/^[A-Za-z0-9\-_\.]{1,100}$/';
    public $validate_fecha = '/^\d{4}-\d{2}-\d{2}$/';
    public $validate_datetime = '/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/';
    public $validate_stock = '/^[0-9]{1,11}$/';
    public $validate_cantidad = '/^[0-9]{1,11}$/';
    public $validate_precio = '/^\d+(\.\d{1,2})?$/';
    public $validate_cotizacion = '/^\d+(\.\d{1,2})?$/';
    public $validate_estado = ['pendiente', 'aprobado', 'rechazado', 'en_proceso', 'completado', 'cancelado'];
    public $validate_tipo_venta = ['detalle', 'mayor', 'mixta'];

    public function validator($data, $rule)
    {
        if (is_array($rule)) {
            // Si la regla es un array, verificamos si el dato está dentro de las opciones
            return in_array($data, $rule, true); // true para validación estricta de tipos
        }

        if (is_string($rule)) {
            // Si es un string, asumimos que es una expresión regular
            return preg_match($rule, $data) === 1;
        }
        return false;
    }
}
