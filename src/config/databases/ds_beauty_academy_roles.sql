-- 1. Asegurar existencia de roles básicos
INSERT INTO roles (id_rol, nombre_rol, descripcion_rol, estatus_rol, fecha_creacion)
VALUES 
(1, 'Administrador', 'Control total del sistema', 1, NOW()),
(2, 'Instructor', 'Gestión académica y evaluaciones', 1, NOW()),
(3, 'Estudiante', 'Consulta de cursos y entregas', 1, NOW())
ON DUPLICATE KEY UPDATE nombre_rol = VALUES(nombre_rol);

-- 2. Crear o actualizar usuario Administrador inicial (clave: Admin123*)
-- Hash generado con password_hash('Admin123*', PASSWORD_BCRYPT):
-- $2y$10$o.Mx9gj2OqzWZvHWHkjBeOuDNIQFbzQnHOpkaeNy9KG1BTd73XOYq
INSERT INTO usuarios (
    id_rol, 
    email_usuario, 
    password_usuario, 
    estatus_usuario, 
    fecha_creacion
) VALUES (
    1, 
    'admin@beautyacademy.com', 
    '$2y$10$o.Mx9gj2OqzWZvHWHkjBeOuDNIQFbzQnHOpkaeNy9KG1BTd73XOYq',
    1, 
    NOW()
)
ON DUPLICATE KEY UPDATE 
    password_usuario = VALUES(password_usuario),
    estatus_usuario = 1;