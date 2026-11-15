// sweetalert.js - Helper centralizado de alertas con tema Dark Luxury / Glow Pink
const isLightTheme = () => document.documentElement.getAttribute('data-theme') === 'light';

const getColors = () => {
    const light = isLightTheme();
    return {
        accent: '#E83382',
        success: '#10b981',
        danger: '#ef4444',
        warning: '#f59e0b',
        info: '#3b82f6',
        secondary: light ? '#e5e7eb' : '#272730',
        background: light ? '#ffffff' : '#18181c',
        text: light ? '#111827' : '#f3f3f5'
    };
};

const getCommonConfig = () => {
    const c = getColors();
    return {
        background: c.background,
        color: c.text,
        allowOutsideClick: false,
        allowEscapeKey: true,
        showClass: { popup: 'animate__animated animate__fadeInDown animate__faster' },
        hideClass: { popup: 'animate__animated animate__fadeOutUp animate__faster' },
        buttonsStyling: true,
        customClass: {
            popup: 'border-luxury shadow-lg',
            confirmButton: 'btn-luxury-confirm',
            cancelButton: 'btn-luxury-cancel'
        }
    };
};

function createAlert(icon, title, text, confirmButtonText = 'Aceptar', color = getColors().accent, options = {}) {
    if (typeof Swal === 'undefined') {
        alert(`${title}: ${text}`);
        return Promise.resolve();
    }

    return Swal.fire({
        ...getCommonConfig(),
        title,
        text,
        icon,
        iconColor: color,
        confirmButtonText,
        confirmButtonColor: color,
        ...options
    });
}

// Alertas individuales
export const showSuccess = (title = '¡Éxito!', text = 'Operación realizada correctamente', confirmButtonText = 'Aceptar', options = {}) =>
    createAlert('success', title, text, confirmButtonText, getColors().success, {
        timer: 4500,
        timerProgressBar: true,
        ...options
    });

export const showError = (title = '¡Error!', text = 'Ha ocurrido un error', confirmButtonText = 'Aceptar', options = {}) =>
    createAlert('error', title, text, confirmButtonText, getColors().danger, options);

export const showWarning = (title = '¡Atención!', text = 'Verifique la información ingresada', confirmButtonText = 'Entendido', options = {}) =>
    createAlert('warning', title, text, confirmButtonText, getColors().warning, options);

export const showInfo = (title = 'Información', text = '', confirmButtonText = 'Aceptar', options = {}) =>
    createAlert('info', title, text, confirmButtonText, getColors().info, options);

// Diálogo de confirmación asíncrono (retorna booleano)
export const showConfirm = (title = '¿Está seguro(a)?', text = 'Esta acción no se puede deshacer', confirmButtonText = 'Sí, continuar', cancelButtonText = 'Cancelar') => {
    if (typeof Swal === 'undefined') {
        return Promise.resolve(confirm(`${title}\n${text}`));
    }

    return Swal.fire({
        ...getCommonConfig(),
        title,
        text,
        icon: 'warning',
        iconColor: getColors().warning,
        showCancelButton: true,
        confirmButtonColor: getColors().accent,
        cancelButtonColor: getColors().secondary,
        confirmButtonText,
        cancelButtonText,
        reverseButtons: true
    }).then(result => result.isConfirmed);
};

/**
 * Diálogo de doble confirmación exclusivo para eliminar registros
 * Paso 1: Pregunta si desea eliminar el registro (ej: "¿Eliminar estudiante?").
 * Paso 2: Si el usuario presiona "Sí, eliminar", se despliega la SEGUNDA confirmación de seguridad:
 *         "¿Seguro que deseas eliminar?" con botón "Sí, seguro".
 * Retorna true ÚNICAMENTE si el usuario confirma ambos pasos.
 */
export const showDeleteConfirm = async (
    title1 = '¿Deseas eliminar este registro?',
    text1 = 'Esta acción afectará los registros del sistema.',
    confirmButtonText1 = 'Sí, eliminar',
    cancelButtonText1 = 'Cancelar',
    title2 = '¿Seguro que deseas eliminar?',
    text2 = 'Esta acción no se puede deshacer. Por favor confirma si deseas proceder.'
) => {
    // 1. Primer diálogo de confirmación
    const primerPaso = await showConfirm(title1, text1, confirmButtonText1, cancelButtonText1);
    if (!primerPaso) return false;

    // 2. Segundo diálogo de confirmación (Doble confirmación de seguridad para eliminar)
    if (typeof Swal === 'undefined') {
        return confirm(`${title2}\n${text2}`);
    }

    const segundoPaso = await Swal.fire({
        ...getCommonConfig(),
        title: title2,
        text: text2,
        icon: 'warning',
        iconColor: getColors().danger,
        showCancelButton: true,
        confirmButtonColor: getColors().danger,
        cancelButtonColor: getColors().secondary,
        confirmButtonText: 'Sí, seguro',
        cancelButtonText: 'Cancelar',
        reverseButtons: true
    });

    return segundoPaso.isConfirmed;
};

// Mensajes directos simples
export const showErrorMessage = (message, title = '¡Error!') =>
    showError(title, message);

export const showSuccessMessage = (message, title = '¡Éxito!') =>
    showSuccess(title, message);

export const showConnectionError = () =>
    showError('Error de conexión', 'No se pudo conectar con el servidor. Verifique su red e intente nuevamente.', 'Reintentar');

// Toast no invasivo en esquina superior derecha
export const showToast = (message, icon = 'success') => {
    if (typeof Swal === 'undefined') return;
    
    return Swal.fire({
        toast: true,
        position: 'top-end',
        icon,
        title: message,
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        background: colors.background,
        color: colors.text
    });
};

// Spinner de carga
export const showLoading = (title = 'Cargando...', text = 'Por favor espere un momento') => {
    if (typeof Swal === 'undefined') return;

    Swal.fire({
        ...getCommonConfig(),
        title,
        text,
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        didOpen: () => Swal.showLoading()
    });
};

export const closeLoading = () => {
    if (typeof Swal !== 'undefined') {
        Swal.close();
    }
};
