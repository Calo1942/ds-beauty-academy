// regexp.js - Expresiones regulares centralizadas para validaciones de formularios
// Alineadas con el modelo físico de base de datos ds_beauty_academy_db.sql

export const regExp = {
    // Nombres y Apellidos (Solo letras, tildes, ñ, ü y espacios, hasta 100 caracteres según VARCHAR(100))
    nombreApellido: /^[A-Za-zÁÉÍÓÚáéíóúÑñüÜ\s]{2,100}$/,

    // Cédula de identidad (6 a 10 dígitos, acepta números solos o prefijo V-, E-, J-, P-)
    cedula: /^(?:[VEJPGvejpg]-?)?[0-9]{6,10}$/,

    // Teléfono (11 dígitos numéricos ej: 04121234567, con guiones o formato internacional con +58)
    telefono: /^(?:\+?[0-9\s\-]{10,20}|[0-9]{11})$/,

    // Correo electrónico estándar (Hasta 150 caracteres según VARCHAR(150))
    email: /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/,

    // Usuario de Instagram (@opcional seguido de 1 a 100 caracteres alfanuméricos, puntos o guiones bajos)
    instagram: /^@?[A-Za-z0-9_.-]{1,100}$/,

    // Texto estándar para nombres y títulos (Hasta 150 caracteres según VARCHAR(150) en cursos, clases, bancos, etc.)
    texto: /^[0-9A-Za-zÁÉÍÓÚáéíóúÑñüÜ\s.,!?#\-_()&:\/]{2,150}$/,

    // Texto muy largo para diplomas y certificados (Hasta 255 caracteres según VARCHAR(255))
    textoMuyLargo: /^[0-9A-Za-zÁÉÍÓÚáéíóúÑñüÜ\s.,!?#\-_()&:\/]{2,255}$/,

    // Texto descriptivo amplio con soporte multilínea (Campos TEXT en MySQL)
    descripcion: /^[0-9A-Za-zÁÉÍÓÚáéíóúÑñüÜ\s.,!?#\-_()&:\/"'%$;\n\r]{2,}$/,

    // Referencia de transacción bancaria (Hasta 150 caracteres según VARCHAR(150))
    referencia: /^[A-Za-z0-9\-_.]{4,150}$/,

    // Cantidad entera positiva
    enteroPositivo: /^[0-9]{1,10}$/,

    // Monto o precio decimal (Hasta 2 decimales opcionales, acorde a DECIMAL(10,2))
    monto: /^[0-9]{1,8}(\.[0-9]{1,2})?$/,

    // Calificación de entrega (Entre 0.00 y 100.00 acorde a DECIMAL(5,2) y CHECK constraint)
    calificacion: /^(100(\.0{1,2})?|[0-9]{1,2}(\.[0-9]{1,2})?)$/,

    // URL de archivos, recursos o videos (Hasta 512 caracteres según VARCHAR(512))
    url: /^(https?:\/\/[^\s]+|[\w\-\.\/]+(\.[a-zA-Z]{2,4})?)$/,

    // ID numérico
    id: /^[0-9]{1,10}$/
};

