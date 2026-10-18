// diplomaValidation.js - Validaciones en tiempo real para formularios de Diplomas
import {
    validarTexto,
    validarCampo
} from '../helpers/validation.js';
import { showWarning } from '../helpers/sweetalert.js';

let erroresCrear = {
    nombre: false,
    categoria: false,
    instructor: false
};

let erroresEditar = {
    nombre: false,
    categoria: false,
    instructor: false
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
    // Título del Diploma
    $form.find('[name="nombre_diploma"]').on('input change', function () {
        const valido = validarTexto(this, 'El título debe tener entre 2 y 100 caracteres.');
        objetoErrores.nombre = !valido;
        actualizarBotonEnvio($form, objetoErrores);
    });

    // Categoría
    $form.find('[name="categoria_diploma"]').on('input change', function () {
        const valido = validarTexto(this, 'La categoría debe tener entre 2 y 60 caracteres.');
        objetoErrores.categoria = !valido;
        actualizarBotonEnvio($form, objetoErrores);
    });

    // Instructor
    $form.find('[name="id_instructor"]').on('change', function () {
        const valor = $(this).val();
        const esValido = valor && valor !== '' && valor !== '0';
        validarCampo(this, esValido, 'Debe seleccionar un instructor responsable.');
        objetoErrores.instructor = !esValido;
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

export function inicializarValidacionesDiplomas() {
    const $formCrear = $('#formCrearDiploma');
    const $formEditar = $('#formEditarDiploma');

    if ($formCrear.length) {
        asociarValidaciones($formCrear, erroresCrear);
    }

    if ($formEditar.length) {
        asociarValidaciones($formEditar, erroresEditar);
    }
}

$(document).ready(function () {
    inicializarValidacionesDiplomas();
});
