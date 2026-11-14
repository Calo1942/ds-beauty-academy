// animations.js - Utilidades para micro-interacciones visuales
const animateElement = ($element, duration, properties) => {
    $element.animate(properties, { duration });
};

export const animateIn = (selector, duration = 300, delay = 0) => {
    const $element = $(selector);
    setTimeout(() => {
        $element.css({ opacity: 0 }).show();
        animateElement($element, duration, { opacity: 1 });
    }, delay);
};

export const pulse = (selector) => {
    const $element = $(selector);
    $element.addClass('animate__animated animate__headShake');
    setTimeout(() => {
        $element.removeClass('animate__animated animate__headShake');
    }, 1000);
};
