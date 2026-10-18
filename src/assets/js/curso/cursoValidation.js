// cursoValidation.js - Validaciones en tiempo real para formularios de Cursos
import {
    validarTexto,
    validarMonto,
    validarCampo
} from '../helpers/validation.js';
import { regExp } from '../helpers/regexp.js';
import { showWarning } from '../helpers/sweetalert.js';

let erroresCrear = {
    nombre: false,
    precio_normal: false,
    precio_preventa: false,
    limite_cupos: false
};

let erroresEditar = {
    nombre: false,
    precio_normal: false,
    precio_preventa: false,
    limite_cupos: false
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
    // Nombre del Curso
    $form.find('[name="nombre_curso"]').on('input change', function () {
        const valido = validarTexto(this, 'El nombre debe tener entre 2 y 100 caracteres.');
        objetoErrores.nombre = !valido;
        actualizarBotonEnvio($form, objetoErrores);
    });

    // Precio Normal
    $form.find('[name="precio_normal"]').on('input change', function () {
        const valido = validarMonto(this);
        objetoErrores.precio_normal = !valido;
        actualizarBotonEnvio($form, objetoErrores);
    });

    // Precio Preventa (opcional)
    $form.find('[name="precio_preventa"]').on('input change', function () {
        const valor = $(this).val() ? $(this).val().trim() : '';
        if (valor === '') {
            $(this).removeClass('is-invalid is-valid');
            $(this).siblings('.invalid-feedback').hide();
            objetoErrores.precio_preventa = false;
        } else {
            const valido = validarMonto(this);
            objetoErrores.precio_preventa = !valido;
        }
        actualizarBotonEnvio($form, objetoErrores);
    });

    // Límite de cupos
    $form.find('[name="limite_cupos"]').on('input change', function () {
        const valor = $(this).val() ? $(this).val().trim() : '';
        const esValido = regExp.enteroPositivo.test(valor) && parseInt(valor) >= 0;
        validarCampo(this, esValido, 'Ingrese una cantidad válida de cupos (número entero >= 0).');
        objetoErrores.limite_cupos = !esValido;
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

export function inicializarValidacionesCursos() {
    const $formCrear = $('#formCrearCurso');
    const $formEditar = $('#formEditarCurso');

    if ($formCrear.length) {
        asociarValidaciones($formCrear, erroresCrear);
    }

    if ($formEditar.length) {
        asociarValidaciones($formEditar, erroresEditar);
    }
}

$(document).ready(function () {
    inicializarValidacionesCursos();
});
