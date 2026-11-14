// sweetalert.js - Helper centralizado de alertas con tema Dark Luxury / Glow Pink
const colors = {
    accent: '#E83382',
    success: '#22c55e',
    danger: '#ef4444',
    warning: '#f59e0b',
    info: '#3b82f6',
    secondary: '#333339',
    background: '#18181c',
    text: '#f3f3f5'
};

const commonConfig = {
    background: colors.background,
    color: colors.text,
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

function createAlert(icon, title, text, confirmButtonText = 'Aceptar', color = colors.accent, options = {}) {
    if (typeof Swal === 'undefined') {
        alert(`${title}: ${text}`);
        return Promise.resolve();
    }

    return Swal.fire({
        ...commonConfig,
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
    createAlert('success', title, text, confirmButtonText, colors.success, options);

export const showError = (title = '¡Error!', text = 'Ha ocurrido un error', confirmButtonText = 'Aceptar', options = {}) =>
    createAlert('error', title, text, confirmButtonText, colors.danger, options);

export const showWarning = (title = '¡Atención!', text = 'Verifique la información ingresada', confirmButtonText = 'Entendido', options = {}) =>
    createAlert('warning', title, text, confirmButtonText, colors.warning, options);

export const showInfo = (title = 'Información', text = '', confirmButtonText = 'Aceptar', options = {}) =>
    createAlert('info', title, text, confirmButtonText, colors.info, options);

// Diálogo de confirmación asíncrono (retorna booleano)
export const showConfirm = (title = '¿Está seguro(a)?', text = 'Esta acción no se puede deshacer', confirmButtonText = 'Sí, continuar', cancelButtonText = 'Cancelar') => {
    if (typeof Swal === 'undefined') {
        return Promise.resolve(confirm(`${title}\n${text}`));
    }

    return Swal.fire({
        ...commonConfig,
        title,
        text,
        icon: 'warning',
        iconColor: colors.warning,
        showCancelButton: true,
        confirmButtonColor: colors.accent,
        cancelButtonColor: colors.secondary,
        confirmButtonText,
        cancelButtonText,
        reverseButtons: true
    }).then(result => result.isConfirmed);
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
        ...commonConfig,
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
