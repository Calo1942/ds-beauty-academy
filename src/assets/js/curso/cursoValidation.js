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
    // Nombre del Curso (VARCHAR(150))
    $form.find('[name="nombre_curso"]').on('input change', function () {
        const valido = validarTexto(this, 'El nombre debe tener entre 2 y 150 caracteres.');
        objetoErrores.nombre = !valido;
        actualizarBotonEnvio($form, objetoErrores);
    });

    // Precio Normal (DECIMAL(10,2) >= 0)
    $form.find('[name="precio_normal"]').on('input change', function () {
        const valido = validarMonto(this);
        objetoErrores.precio_normal = !valido;
        
        // Revalidar precio preventa si existe
        const $preventa = $form.find('[name="precio_preventa"]');
        if ($preventa.val() && $preventa.val().trim() !== '') {
            $preventa.trigger('change');
        }
        
        actualizarBotonEnvio($form, objetoErrores);
    });

    // Precio Preventa (opcional, debe ser menor al precio normal)
    $form.find('[name="precio_preventa"]').on('input change', function () {
        const valor = $(this).val() ? $(this).val().trim() : '';
        if (valor === '') {
            $(this).removeClass('is-invalid is-valid');
            $(this).siblings('.invalid-feedback').hide();
            objetoErrores.precio_preventa = false;
        } else {
            let valido = validarMonto(this);
            const precioNormalVal = $form.find('[name="precio_normal"]').val();
            if (valido && precioNormalVal && precioNormalVal.trim() !== '') {
                const normal = parseFloat(precioNormalVal);
                const preventa = parseFloat(valor);
                if (preventa >= normal) {
                    valido = false;
                    validarCampo(this, false, 'El precio de preventa debe ser menor al precio normal.');
                }
            }
            objetoErrores.precio_preventa = !valido;
        }
        actualizarBotonEnvio($form, objetoErrores);
    });

    // Límite de cupos (CHECK limite_cupos > 0)
    $form.find('[name="limite_cupos"]').on('input change', function () {
        const valor = $(this).val() ? $(this).val().trim() : '';
        const esValido = regExp.enteroPositivo.test(valor) && parseInt(valor, 10) >= 1;
        validarCampo(this, esValido, 'Ingrese una cantidad válida de cupos (número entero >= 1).');
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

        // Validación de coherencia de fechas acorde a los CHECKs de la BD
        const fInicio = $form.find('[name="fecha_inicio_curso"]').val();
        const fFinPrev = $form.find('[name="fecha_fin_preventa"]').val();
        const fFinCurso = $form.find('[name="fecha_fin_curso"]').val();

        if (fFinPrev && fInicio && new Date(fFinPrev) > new Date(fInicio)) {
            e.preventDefault();
            showWarning('Fechas Inválidas', 'La fecha de fin de preventa no puede ser posterior al inicio del curso.');
            return false;
        }

        if (fFinCurso && fInicio && new Date(fFinCurso) < new Date(fInicio)) {
            e.preventDefault();
            showWarning('Fechas Inválidas', 'La fecha de finalización del curso debe ser posterior o igual a la de inicio.');
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
