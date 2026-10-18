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
    // Nombre del Recurso
    $form.find('[name="nombre_recurso"]').on('input change', function () {
        const valido = validarTexto(this, 'El nombre debe tener entre 2 y 100 caracteres.');
        objetoErrores.nombre = !valido;
        actualizarBotonEnvio($form, objetoErrores);
    });

    // URL del Archivo
    $form.find('[name="url_archivo_recurso"]').on('input change', function () {
        const valor = $(this).val() ? $(this).val().trim() : '';
        const esValido = valor.length >= 2 && valor.length <= 255;
        validarCampo(this, esValido, 'Ingrese un enlace o ruta válida para el recurso.');
        objetoErrores.url_archivo = !esValido;
        actualizarBotonEnvio($form, objetoErrores);
    });

    // Verificación final previa al submit
    $form.on('submit', function (e) {
        if (hayErrores(objetoErrores)) {
            e.preventDefault();
            showWarning('Formulario Incompleto', 'Por favor, corrija los campos marcados en rojo antes de enviar.');
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
