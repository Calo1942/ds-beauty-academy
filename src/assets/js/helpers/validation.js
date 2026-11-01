// validation.js - Lógica y helpers para validación reactiva en tiempo real
import { regExp } from './regexp.js';

/**
 * Actualiza el estado visual de un campo y muestra/oculta el mensaje de error
 */
export const validarCampo = (campo, esValido, mensajeError = '') => {
    const $campo = $(campo);
    $campo.toggleClass('is-valid', esValido).toggleClass('is-invalid', !esValido);

    // Buscar o crear contenedor de feedback
    let $feedback = $campo.siblings('.invalid-feedback');
    if (!$feedback.length) {
        $feedback = $('<div class="invalid-feedback"></div>');
        $campo.after($feedback);
    }

    if (!esValido && mensajeError) {
        $feedback.text(mensajeError).show();
    } else {
        $feedback.text('').hide();
    }

    return esValido;
};

const crearValidador = (regex, campo, mensajeError) => {
    const $campo = $(campo);
    const valor = $campo.val() ? $campo.val().trim() : '';
    const esValido = regex.test(valor);
    return validarCampo($campo, esValido, mensajeError);
};

// Validadores específicos
export const validarNombre = (campo) => 
    crearValidador(regExp.nombreApellido, campo, 'Ingrese un nombre válido (2 a 100 letras, sin números).');

export const validarNombreOpcional = (campo) => {
    const $campo = $(campo);
    const valor = $campo.val() ? $campo.val().trim() : '';
    if (valor === '') {
        $campo.removeClass('is-invalid is-valid');
        $campo.siblings('.invalid-feedback').hide();
        return true;
    }
    return crearValidador(regExp.nombreApellido, campo, 'Ingrese un nombre válido (2 a 100 letras).');
};

export const validarApellido = (campo) => 
    crearValidador(regExp.nombreApellido, campo, 'Ingrese un apellido válido (2 a 100 letras, sin números).');

export const validarApellidoOpcional = (campo) => {
    const $campo = $(campo);
    const valor = $campo.val() ? $campo.val().trim() : '';
    if (valor === '') {
        $campo.removeClass('is-invalid is-valid');
        $campo.siblings('.invalid-feedback').hide();
        return true;
    }
    return crearValidador(regExp.nombreApellido, campo, 'Ingrese un apellido válido (2 a 100 letras).');
};

export const validarCedula = (campo) => 
    crearValidador(regExp.cedula, campo, 'Ingrese una cédula válida (ej: 12345678 o V-12345678).');

export const validarEmail = (campo) => 
    crearValidador(regExp.email, campo, 'Ingrese un correo electrónico válido (ej: usuario@gmail.com).');

export const validarTelefono = (campo) => 
    crearValidador(regExp.telefono, campo, 'Ingrese un teléfono válido (ej: 04121234567 o +58 412 1234567).');

export const validarTelefonoOpcional = (campo) => {
    const $campo = $(campo);
    const valor = $campo.val() ? $campo.val().trim() : '';
    if (valor === '') {
        $campo.removeClass('is-invalid is-valid');
        $campo.siblings('.invalid-feedback').hide();
        return true;
    }
    return crearValidador(regExp.telefono, campo, 'Ingrese un teléfono alternativo válido (ej: 04141234567 o +58 414 1234567).');
};

export const validarInstagram = (campo) => {
    const $campo = $(campo);
    const valor = $campo.val() ? $campo.val().trim() : '';
    if (valor === '') {
        $campo.removeClass('is-invalid is-valid');
        $campo.siblings('.invalid-feedback').hide();
        return true;
    }
    return crearValidador(regExp.instagram, campo, 'Formato inválido. Ej: @usuario o usuario (hasta 100 caracteres).');
};

export const validarTexto = (campo, mensaje = 'Este campo no cumple con el formato requerido.') => 
    crearValidador(regExp.texto, campo, mensaje);

export const validarTextoMuyLargo = (campo, mensaje = 'Este campo debe contener entre 2 y 255 caracteres.') => 
    crearValidador(regExp.textoMuyLargo, campo, mensaje);

export const validarDescripcion = (campo, mensaje = 'Ingrese una descripción válida.') => {
    const $campo = $(campo);
    const valor = $campo.val() ? $campo.val().trim() : '';
    if (valor === '') {
        $campo.removeClass('is-invalid is-valid');
        $campo.siblings('.invalid-feedback').hide();
        return true;
    }
    return crearValidador(regExp.descripcion, campo, mensaje);
};

export const validarMonto = (campo) => 
    crearValidador(regExp.monto, campo, 'Ingrese un monto numérico válido mayor o igual a 0.');

export const validarReferencia = (campo, mensaje = 'La referencia debe tener entre 4 y 150 caracteres.') => 
    crearValidador(regExp.referencia, campo, mensaje);

export const validarCalificacion = (campo) => 
    crearValidador(regExp.calificacion, campo, 'La calificación debe estar comprendida entre 0 y 100.');

export const validarUrl = (campo, mensaje = 'Ingrese una URL o enlace válido (hasta 512 caracteres).') => {
    const $campo = $(campo);
    const valor = $campo.val() ? $campo.val().trim() : '';
    const esValido = valor.length >= 2 && valor.length <= 512 && regExp.url.test(valor);
    return validarCampo($campo, esValido, mensaje);
};

/**
 * Limpia todas las clases y mensajes de validación de un formulario
 */
export const limpiarValidaciones = (form) => {
    const $form = $(form);
    $form.find('.is-valid, .is-invalid').removeClass('is-valid is-invalid');
    $form.find('.invalid-feedback').text('').hide();
};
