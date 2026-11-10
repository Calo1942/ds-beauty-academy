// recursoValidation.js - Validaciones en tiempo real para formularios de Recursos
import {
    validarTexto,
    validarCampo
} from '../helpers/validation.js';
import { showWarning } from '../helpers/sweetalert.js';

let erroresCrear = {
    nombre: false,
    url_archivo: false
};

let erroresEditar = {
    nombre: false,
    url_archivo: false
};

function hayErrores(errores) {
    return Object.values(errores).some(error => error === true);
}

function actualizarBotonEnvio($form, errores) {
    const $btn = $form.find('button[type="submit"]');
    const invalid = hayErrores(errores);
    $btn.prop('disabled', invalid);
    if (invalid) {
        $btn.addClass('opacity-50 cursor-not-allowed');
    } else {
        $btn.removeClass('opacity-50 cursor-not-allowed');
    }
}

function asociarValidaciones($form, objetoErrores) {
    // Nombre del Recurso (VARCHAR(150))
    $form.find('[name="nombre_recurso"]').on('input change', function () {
        const valido = validarTexto(this, 'El nombre debe tener entre 2 y 150 caracteres.');
        objetoErrores.nombre = !valido;
        actualizarBotonEnvio($form, objetoErrores);
    });

    // URL del Archivo (VARCHAR(512))
    $form.find('[name="url_archivo_recurso"]').on('input change', function () {
        const valor = $(this).val() ? $(this).val().trim() : '';
        const esValido = valor.length >= 2 && valor.length <= 512;
        validarCampo(this, esValido, 'Ingrese un enlace o ruta válida para el recurso (hasta 512 caracteres).');
        objetoErrores.url_archivo = !esValido;
        actualizarBotonEnvio($form, objetoErrores);
    });

    // Verificación final previa al submit
    $form.on('submit', function (e) {
        $form.find('[name="nombre_recurso"]').trigger('change');
        $form.find('[name="url_archivo_recurso"]').trigger('change');

        if (hayErrores(objetoErrores) || !$form.find('[name="nombre_recurso"]').val().trim() || !$form.find('[name="url_archivo_recurso"]').val().trim()) {
            e.preventDefault();
            e.stopImmediatePropagation();
            showWarning('Formulario Incompleto', 'Por favor, complete todos los campos obligatorios antes de enviar.');
            return false;
        }
        return true;
    });
}

export function inicializarValidacionesRecursos() {
    const $formCrear = $('#formCrearRecurso');
    const $formEditar = $('#formEditarRecurso');

    if ($formCrear.length) {
        asociarValidaciones($formCrear, erroresCrear);
    }

    if ($formEditar.length) {
        asociarValidaciones($formEditar, erroresEditar);
    }
}

$(document).ready(function () {
    inicializarValidacionesRecursos();
});
