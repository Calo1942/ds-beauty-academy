<?php

namespace DsBeautyAcademy\Helpers;

trait Validations
{
    // =========================================================================
    // REGLAS GENERALES Y TIPOS BÁSICOS (Alineados con ds_beauty_academy_db.sql)
    // =========================================================================

    /** Identificadores numéricos enteros positivos (IDs de llaves primarias y foráneas) */
    public $validate_id = '/^[0-9]{1,10}$/';

    /** Números enteros positivos generales (orden, cupos, etc.) */
    public $validate_number = '/^[0-9]{1,10}$/';

    /** Cédula de identidad (Acepta entre 6 y 10 dígitos o con prefijo nacional/extranjero: V-, E-, J-, P-) */
    public $validate_cedula = '/^(?:[VEJPGvejpg]-?)?[0-9]{6,10}$/';

    /** Teléfonos (Acepta formato nacional de 11 dígitos ej: 04121234567, con guiones o formato internacional con +58) */
    public $validate_telefono = '/^(?:\+?[0-9\s\-]{10,20}|[0-9]{11})$/';

    /** Nombres y Apellidos de personas (Hasta 100 caracteres según VARCHAR(100) en instructores y estudiantes) */
    public $validate_names = '/^[a-zA-ZñÑáéíóúÁÉÍÓÚüÜ\s]{2,100}$/u';

    /** Correo electrónico estándar (Hasta 150 caracteres según VARCHAR(150) en instructores, estudiantes, usuarios) */
    public $validate_email = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';

    /** Usuario de Instagram (Hasta 100 caracteres según VARCHAR(100) en instructores y estudiantes) */
    public $validate_usuario_instagram = '/^@?[A-Za-z0-9_.-]{1,100}$/';

    /** Textos cortos generales (hasta 100 caracteres) */
    public $validate_text = '/^[0-9A-Za-zÁÉÍÓÚáéíóúÑñüÜ\s.,#\-_()&:\/]{2,100}$/u';

    /** Textos largos para títulos y nombres (Hasta 150 caracteres según VARCHAR(150) en cursos, clases, bancos, etc.) */
    public $validate_text_long = '/^[0-9A-Za-zÁÉÍÓÚáéíóúÑñüÜ\s.,!?#\-_()&:\/]{2,150}$/u';

    /** Textos muy largos (Hasta 255 caracteres según VARCHAR(255) en diplomas y certificados) */
    public $validate_text_very_long = '/^[0-9A-Za-zÁÉÍÓÚáéíóúÑñüÜ\s.,!?#\-_()&:\/]{2,255}$/u';

    /** Campos de descripción y texto enriquecido (TEXT en MySQL: soporte para saltos de línea y puntuación variada) */
    public $validate_description = '/^[0-9A-Za-zÁÉÍÓÚáéíóúÑñüÜ\s.,!?#\-_()&:\/"\'%$;\r\n]{2,}$/u';

    /** Montos y precios decimales (Hasta 2 decimales opcionales, acorde a DECIMAL(10,2)) */
    public $validate_decimal = '/^\d+(\.\d{1,2})?$/';
    public $validate_precio = '/^\d+(\.\d{1,2})?$/';

    /** Calificación de evaluaciones (Entre 0.00 y 100.00 según CHECK (calificacion_entrega >= 0 AND <= 100)) */
    public $validate_calificacion = '/^(100(\.0{1,2})?|[0-9]{1,2}(\.[0-9]{1,2})?)$/';

    /** Referencia bancaria (Alfanumérico con guiones y puntos, hasta 150 caracteres según VARCHAR(150)) */
    public $validate_referencia = '/^[A-Za-z0-9\-_.]{4,150}$/';

    /** Nombre de entidad bancaria (Hasta 150 caracteres según VARCHAR(150) en banco) */
    public $validate_banco = '/^[0-9A-Za-zÁÉÍÓÚáéíóúÑñüÜ\s.,\-_()\/&]{2,150}$/u';

    /** Nombre de especialidad (Hasta 150 caracteres según VARCHAR(150) en especialidades) */
    public $validate_especialidad = '/^[0-9A-Za-zÁÉÍÓÚáéíóúÑñüÜ\s.,\-_()\/&]{2,150}$/u';

    /** URLs de archivos, videos o comprobantes (Hasta 512 caracteres según VARCHAR(512)) */
    public $validate_url = '/^(https?:\/\/[^\s]+|[\w\-\.\/]+(\.[a-zA-Z]{2,4})?)$/';

    /** Fechas (YYYY-MM-DD) */
    public $validate_fecha = '/^\d{4}-\d{2}-\d{2}$/';

    /** Datetime (Soporta YYYY-MM-DD HH:MM:SS, con T o sin segundos para compatibilidad con input datetime-local) */
    public $validate_datetime = '/^\d{4}-\d{2}-\d{2}[ T]\d{2}:\d{2}(:\d{2})?$/';

    /** Valores booleanos estándar */
    public $validate_boolean = [true, false, 1, 0, '1', '0'];

    // =========================================================================
    // ENUMS Y WHITELISTS DE BASE DE DATOS (ds_beauty_academy_db.sql)
    // =========================================================================

    /** tipo_curso ENUM('Masterclass','Curso completo') */
    public $validate_tipo_curso = ['Masterclass', 'Curso completo'];

    /** estatus_curso ENUM('Borrador','Preventa','Venta Normal','Cerrado') */
    public $validate_estatus_curso = ['Borrador', 'Preventa', 'Venta Normal', 'Cerrado'];

    /** estatus_pago en matriculas ENUM('Deudora','Solvente') */
    public $validate_estatus_pago_matricula = ['Deudora', 'Solvente'];

    /** estatus_cupo en matriculas ENUM('Reservado','Confirmado','Cancelado') */
    public $validate_estatus_cupo = ['Reservado', 'Confirmado', 'Cancelado'];

    /** estatus_pago en comprobante_pagos ENUM('Pendiente','Verificado','Rechazado') */
    public $validate_estatus_pago_comprobante = ['Pendiente', 'Verificado', 'Rechazado'];

    /** tipo_evaluacion en evaluaciones ENUM('practica','teorica') */
    public $validate_tipo_evaluacion = ['practica', 'teorica'];

    /** estatus_entrega en entregas_evaluaciones ENUM('Pendiente','En Revisión','Repetir','Aprobada') */
    public $validate_estatus_entrega = ['Pendiente', 'En Revisión', 'Repetir', 'Aprobada'];

    /** estatus_certificado en certificados ENUM('Pendiente de Emisión','Emitido','Anulado') */
    public $validate_estatus_certificado = ['Pendiente de Emisión', 'Emitido', 'Anulado'];

    // =========================================================================
    // PROPIEDADES HEREDADAS (Mantenidas por retrocompatibilidad)
    // =========================================================================
    public $validate_talla = '/^[A-Za-z0-9]{1,5}$/';
    public $validate_color = '/^[A-Za-zñÑáéíóúÁÉÍÓÚ\s]{2,50}$/';
    public $validate_nombre_archivo = '/^[A-Za-z0-9\-_\.]{1,100}$/';
    public $validate_stock = '/^[0-9]{1,11}$/';
    public $validate_cantidad = '/^[0-9]{1,11}$/';
    public $validate_cotizacion = '/^\d+(\.\d{1,2})?$/';
    public $validate_estado = ['pendiente', 'aprobado', 'rechazado', 'en_proceso', 'completado', 'cancelado'];

    // =========================================================================
    // MÉTODOS DE VALIDACIÓN
    // =========================================================================

    public function validator($data, $rule)
    {
        if (is_array($rule)) {
            // Si la regla es un array, verificamos si el dato está dentro de las opciones
            return in_array($data, $rule, true); // true para validación estricta de tipos
        }

        if (is_string($rule)) {
            // Si es un string, asumimos que es una expresión regular
            return preg_match($rule, (string)$data) === 1;
        }
        return false;
    }

    public function parseBoolean($value, $default = 1)
    {
        if ($value === '' || $value === null) {
            return $default;
        }
        if (is_string($value)) {
            $val = strtolower(trim($value));
            if (in_array($val, ['inactivo', 'false', '0', 'off', 'no'], true)) {
                return 0;
            }
            if (in_array($val, ['activo', 'true', '1', 'on', 'yes'], true)) {
                return 1;
            }
        }
        return filter_var($value, FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
    }
}

