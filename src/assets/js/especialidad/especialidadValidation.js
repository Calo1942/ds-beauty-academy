// especialidadValidation.js - Validaciones en tiempo real para formularios de Especialidades
import {
    validarTexto
} from '../helpers/validation.js';
import { showWarning } from '../helpers/sweetalert.js';

let erroresCrear = {
    nombre_especialidad: false
};

let erroresEditar = {
    nombre_especialidad: false
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
    // Nombre de la Especialidad
    $form.find('[name="nombre_especialidad"]').on('input change', function () {
        const valido = validarTexto(this, 'El nombre debe tener entre 2 y 60 caracteres.');
        objetoErrores.nombre_especialidad = !valido;
        actualizarBotonEnvio($form, objetoErrores);
    });

    // Verificación final previa al submit
    $form.on('submit', function (e) {
        if (hayErrores(objetoErrores)) {
            e.preventDefault();
            showWarning('Formulario Incompleto', 'Por favor, corrija el nombre de la especialidad antes de enviar.');
            return false;
        }
        return true;
    });
}

export function inicializarValidacionesEspecialidades() {
    const $formCrear = $('#formCrearEspecialidad');
    const $formEditar = $('#formEditarEspecialidad');

    if ($formCrear.length) {
        asociarValidaciones($formCrear, erroresCrear);
    }

    if ($formEditar.length) {
        asociarValidaciones($formEditar, erroresEditar);
    }
}

$(document).ready(function () {
    inicializarValidacionesEspecialidades();
});
