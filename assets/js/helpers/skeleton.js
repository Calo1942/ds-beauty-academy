// skeleton.js - Control de visualización de skeletons / loaders
export const showSkeleton = (skeletonSelector, hideSelector = null) => {
    if (hideSelector) $(hideSelector).hide();
    $(skeletonSelector).fadeIn(200);
};

export const hideSkeleton = (skeletonSelector, showSelector = null) => {
    $(skeletonSelector).fadeOut(200, () => {
        if (showSelector) $(showSelector).fadeIn(200);
    });
};

// Variable para registrar el timestamp de apertura del modal
const modalOpenTimestamps = new Map();

/**
 * Muestra el skeleton tipo tabla dentro de un modal y oculta su contenido real.
 * Abre el modal inmediatamente para una experiencia fluida e interactiva.
 */
export const showModalSkeleton = (modalSelector) => {
    const $modal = $(modalSelector);
    if ($modal.length) {
        modalOpenTimestamps.set(modalSelector, Date.now());
        $modal.find('.skeleton-modal-table').show();
        $modal.find('.modal-body-content').hide();
        $modal.addClass('show');
    }
};

/**
 * Oculta el skeleton del modal y revela el contenido ya poblado con animación fade.
 * Garantiza un tiempo mínimo visible (minDuration ms) para que el brillo (shimmer)
 * sea perceptible y apreciado por el usuario antes de revelar los datos.
 */
export const hideModalSkeleton = (modalSelector, minDuration = 500) => {
    const $modal = $(modalSelector);
    if (!$modal.length) return;

    const startTime = modalOpenTimestamps.get(modalSelector) || 0;
    const elapsed = Date.now() - startTime;
    const remainingDelay = Math.max(0, minDuration - elapsed);

    setTimeout(() => {
        $modal.find('.skeleton-modal-table').fadeOut(220, () => {
            $modal.find('.modal-body-content').fadeIn(250);
        });
    }, remainingDelay);
};
