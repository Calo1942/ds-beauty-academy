// evaluacionValidation.js - Validaciones en tiempo real para formularios de Evaluaciones
import {
    validarTexto,
    validarCampo
} from '../helpers/validation.js';
import { regExp } from '../helpers/regexp.js';
import { showWarning } from '../helpers/sweetalert.js';

let erroresCrear = {
    titulo: false,
    orden: false,
    id_instructor_curso: false
};

let erroresEditar = {
    titulo: false,
    orden: false,
    id_instructor_curso: false
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
    // Título de la Evaluación
    $form.find('[name="titulo_evaluacion"]').on('input change', function () {
        const valido = validarTexto(this, 'El título debe tener entre 2 y 100 caracteres.');
        objetoErrores.titulo = !valido;
        actualizarBotonEnvio($form, objetoErrores);
    });

    // Orden Cronológico
    $form.find('[name="orden_evaluacion"]').on('input change', function () {
        const valor = $(this).val() ? $(this).val().trim() : '';
        const esValido = regExp.enteroPositivo.test(valor) && parseInt(valor) >= 1;
        validarCampo(this, esValido, 'Ingrese un orden válido (entero >= 1).');
        objetoErrores.orden = !esValido;
        actualizarBotonEnvio($form, objetoErrores);
    });

    // ID Instructor Curso
    $form.find('[name="id_instructor_curso"]').on('input change', function () {
        const valor = $(this).val() ? $(this).val().trim() : '';
        const esValido = regExp.id.test(valor) && parseInt(valor) >= 1;
        validarCampo(this, esValido, 'Ingrese un ID de asignación docente válido.');
        objetoErrores.id_instructor_curso = !esValido;
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

export function inicializarValidacionesEvaluaciones() {
    const $formCrear = $('#formCrearEvaluacion');
    const $formEditar = $('#formEditarEvaluacion');

    if ($formCrear.length) {
        asociarValidaciones($formCrear, erroresCrear);
    }

    if ($formEditar.length) {
        asociarValidaciones($formEditar, erroresEditar);
    }
}

$(document).ready(function () {
    inicializarValidacionesEvaluaciones();
});
