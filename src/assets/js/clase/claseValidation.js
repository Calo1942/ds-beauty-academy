// claseValidation.js - Validaciones en tiempo real para formularios de Clases
import {
    validarTexto,
    validarCampo
} from '../helpers/validation.js';
import { regExp } from '../helpers/regexp.js';
import { showWarning } from '../helpers/sweetalert.js';

let erroresCrear = {
    titulo: false,
    url_video: false,
    orden: false,
    id_instructor_curso: false
};

let erroresEditar = {
    titulo: false,
    url_video: false,
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
    // Título de la Clase (VARCHAR(150))
    $form.find('[name="titulo_clase"]').on('input change', function () {
        const valido = validarTexto(this, 'El título debe tener entre 2 y 150 caracteres.');
        objetoErrores.titulo = !valido;
        actualizarBotonEnvio($form, objetoErrores);
    });

    // URL del Video (VARCHAR(512))
    $form.find('[name="url_video_clase"]').on('input change', function () {
        const valor = $(this).val() ? $(this).val().trim() : '';
        const esValido = valor.length >= 5 && valor.length <= 512;
        validarCampo(this, esValido, 'Ingrese una URL o enlace válido para el video (hasta 512 caracteres).');
        objetoErrores.url_video = !esValido;
        actualizarBotonEnvio($form, objetoErrores);
    });

    // Orden
    $form.find('[name="orden_clase"]').on('input change', function () {
        const valor = $(this).val() ? $(this).val().trim() : '';
        const esValido = regExp.enteroPositivo.test(valor) && parseInt(valor) >= 1;
        validarCampo(this, esValido, 'Ingrese un número de orden válido (entero >= 1).');
        objetoErrores.orden = !esValido;
        actualizarBotonEnvio($form, objetoErrores);
    });

    // ID Instructor Curso
    $form.find('[name="id_instructor_curso"]').on('input change', function () {
        const valor = $(this).val() ? $(this).val().trim() : '';
        const esValido = regExp.id.test(valor) && parseInt(valor) >= 1;
        validarCampo(this, esValido, 'Ingrese un ID de asignación válido.');
        objetoErrores.id_instructor_curso = !esValido;
        actualizarBotonEnvio($form, objetoErrores);
    });

    // Verificación final previa al submit
    $form.on('submit', function (e) {
        $form.find('[name="titulo_clase"]').trigger('change');
        $form.find('[name="url_video_clase"]').trigger('change');
        $form.find('[name="orden_clase"]').trigger('change');
        $form.find('[name="id_instructor_curso"]').trigger('change');

        if (hayErrores(objetoErrores) || !$form.find('[name="titulo_clase"]').val().trim() || !$form.find('[name="url_video_clase"]').val().trim() || !$form.find('[name="orden_clase"]').val() || !$form.find('[name="id_instructor_curso"]').val()) {
            e.preventDefault();
            e.stopImmediatePropagation();
            showWarning('Formulario Incompleto', 'Por favor, complete todos los campos obligatorios antes de enviar.');
            return false;
        }
        return true;
    });
}

export function inicializarValidacionesClases() {
    const $formCrear = $('#formCrearClase');
    const $formEditar = $('#formEditarClase');

    if ($formCrear.length) {
        asociarValidaciones($formCrear, erroresCrear);
    }

    if ($formEditar.length) {
        asociarValidaciones($formEditar, erroresEditar);
    }
}

$(document).ready(function () {
    inicializarValidacionesClases();
});
