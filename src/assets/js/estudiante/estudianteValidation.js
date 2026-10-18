// estudianteValidation.js - Validaciones en tiempo real para formularios de Estudiantes
import {
    validarNombre,
    validarNombreOpcional,
    validarApellido,
    validarApellidoOpcional,
    validarCedula,
    validarEmail,
    validarTelefono,
    validarTelefonoOpcional,
    validarInstagram,
    limpiarValidaciones
} from '../helpers/validation.js';
import { showWarning } from '../helpers/sweetalert.js';

let erroresCrear = {
    primer_nombre: false,
    segundo_nombre: false,
    primer_apellido: false,
    segundo_apellido: false,
    cedula: false,
    correo: false,
    telefono_principal: false,
    telefono_alternativo: false,
    instagram: false
};

let erroresEditar = {
    primer_nombre: false,
    segundo_nombre: false,
    primer_apellido: false,
    segundo_apellido: false,
    cedula: false,
    correo: false,
    telefono_principal: false,
    telefono_alternativo: false,
    instagram: false
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
    // Primer Nombre
    $form.find('[name="primer_nombre_estudiante"]').on('input change', function () {
        const valido = validarNombre(this);
        objetoErrores.primer_nombre = !valido;
        actualizarBotonEnvio($form, objetoErrores);
    });

    // Segundo Nombre (opcional)
    $form.find('[name="segundo_nombre_estudiante"]').on('input change', function () {
        const valido = validarNombreOpcional(this);
        objetoErrores.segundo_nombre = !valido;
        actualizarBotonEnvio($form, objetoErrores);
    });

    // Primer Apellido
    $form.find('[name="primer_apellido_estudiante"]').on('input change', function () {
        const valido = validarApellido(this);
        objetoErrores.primer_apellido = !valido;
        actualizarBotonEnvio($form, objetoErrores);
    });

    // Segundo Apellido (opcional)
    $form.find('[name="segundo_apellido_estudiante"]').on('input change', function () {
        const valido = validarApellidoOpcional(this);
        objetoErrores.segundo_apellido = !valido;
        actualizarBotonEnvio($form, objetoErrores);
    });

    // Cédula
    $form.find('[name="cedula_estudiante"]').on('input change', function () {
        const valido = validarCedula(this);
        objetoErrores.cedula = !valido;
        actualizarBotonEnvio($form, objetoErrores);
    });

    // Correo
    $form.find('[name="correo_estudiante"]').on('input change', function () {
        const valido = validarEmail(this);
        objetoErrores.correo = !valido;
        actualizarBotonEnvio($form, objetoErrores);
    });

    // Teléfono Principal
    $form.find('[name="telefono_principal_estudiante"]').on('input change', function () {
        const valido = validarTelefono(this);
        objetoErrores.telefono_principal = !valido;
        actualizarBotonEnvio($form, objetoErrores);
    });

    // Teléfono Alternativo (opcional)
    $form.find('[name="telefono_alternativo_estudiante"]').on('input change', function () {
        const valido = validarTelefonoOpcional(this);
        objetoErrores.telefono_alternativo = !valido;
        actualizarBotonEnvio($form, objetoErrores);
    });

    // Instagram (opcional)
    $form.find('[name="usuario_instagram_estudiante"]').on('input change', function () {
        const valido = validarInstagram(this);
        objetoErrores.instagram = !valido;
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

export function inicializarValidacionesEstudiantes() {
    const $formCrear = $('#formCrearEstudiante');
    const $formEditar = $('#formEditarEstudiante');

    if ($formCrear.length) {
        asociarValidaciones($formCrear, erroresCrear);
    }

    if ($formEditar.length) {
        asociarValidaciones($formEditar, erroresEditar);
    }
}

$(document).ready(function () {
    inicializarValidacionesEstudiantes();
});
