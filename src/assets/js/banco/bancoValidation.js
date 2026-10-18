// bancoValidation.js - Validaciones en tiempo real para formularios de Bancos
import {
    validarTexto
} from '../helpers/validation.js';
import { showWarning } from '../helpers/sweetalert.js';

let erroresCrear = {
    nombre_banco: false
};

let erroresEditar = {
    nombre_banco: false
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
    // Nombre del Banco
    $form.find('[name="nombre_banco"]').on('input change', function () {
        const valido = validarTexto(this, 'El nombre del banco debe tener entre 2 y 60 caracteres.');
        objetoErrores.nombre_banco = !valido;
        actualizarBotonEnvio($form, objetoErrores);
    });

    // Verificación final previa al submit
    $form.on('submit', function (e) {
        if (hayErrores(objetoErrores)) {
            e.preventDefault();
            showWarning('Formulario Incompleto', 'Por favor, corrija el nombre del banco antes de enviar.');
            return false;
        }
        return true;
    });
}

export function inicializarValidacionesBancos() {
    const $formCrear = $('#formCrearBanco');
    const $formEditar = $('#formEditarBanco');

    if ($formCrear.length) {
        asociarValidaciones($formCrear, erroresCrear);
    }

    if ($formEditar.length) {
        asociarValidaciones($formEditar, erroresEditar);
    }
}

$(document).ready(function () {
    inicializarValidacionesBancos();
});
