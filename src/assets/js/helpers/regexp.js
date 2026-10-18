// regexp.js - Expresiones regulares centralizadas para validaciones de formularios

export const regExp = {
    // Nombres y Apellidos (Solo letras, tildes, ñ y espacios, entre 2 y 30 caracteres)
    nombreApellido: /^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]{2,30}$/,

    // Cédula venezolana (7 a 9 dígitos numéricos)
    cedula: /^[0-9]{7,9}$/,

    // Teléfono venezolano (Exactamente 11 dígitos, ej: 04128765432 o 02512345678)
    telefono: /^[0-9]{11}$/,

    // Correo electrónico estándar
    email: /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/,

    // Usuario de Instagram (@opcional seguido de 1 a 30 caracteres alfanuméricos, puntos o guiones bajos)
    instagram: /^@?[A-Za-z0-9._]{1,30}$/,

    // Texto descriptivo o alfanumérico general
    texto: /^[0-9A-Za-zÁÉÍÓÚáéíóúÑñ\s.,#\-_()]{2,120}$/,

    // Cantidad entera positiva
    enteroPositivo: /^[0-9]{1,10}$/,

    // Monto o precio decimal (hasta 2 decimales opcionales)
    monto: /^[0-9]{1,8}(\.[0-9]{1,2})?$/,

    // ID numérico
    id: /^[0-9]{1,10}$/
};
