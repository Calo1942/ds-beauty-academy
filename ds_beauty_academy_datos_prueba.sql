-- ==============================================================================
-- DS BEAUTY ACADEMY - SCRIPT DE DATOS DE PRUEBA REALISTAS (SIMULACIÓN DE PRODUCCIÓN)
-- Fecha de Generación: Octubre 2026
-- Compatible con: MySQL 5.7+, MySQL 8.0+, MariaDB 10.3+
-- Codificación: UTF-8 Unicode (utf8mb4)
-- ==============================================================================
--
-- GUÍA DE ACCESO Y CREDENCIALES DE PRUEBA:
-- ==============================================================================
-- Todos los usuarios registrados tienen contraseñas seguras preconfiguradas con hash BCRYPT:
-- 
-- 1. ADMINISTRADORES (Rol: Administrador, Acceso Total):
--    - admin@beautyacademy.com           | Clave: Admin123*
--    - coordinacion@beautyacademy.com    | Clave: Admin123*
--
-- 2. INSTRUCTORES (Rol: Instructor, Gestión Académica y Evaluaciones):
--    - daniela.sanchez@beautyacademy.com    | Clave: Instructor123*  (Maquillaje / Cejas)
--    - valeria.mendoza@beautyacademy.com    | Clave: Instructor123*  (Lash Artist / Pestañas)
--    - mariana.gomez@beautyacademy.com      | Clave: Instructor123*  (Manicura Rusa / Uñas)
--    - carlos.silva@beautyacademy.com       | Clave: Instructor123*  (Barbería / Cortes)
--    - andreina.castillo@beautyacademy.com  | Clave: Instructor123*  (Cosmetología Facial)
--    - gabriela.fuentes@beautyacademy.com   | Clave: Instructor123*  (Microblading / Cejas)
--    - nelson.ramirez@beautyacademy.com     | Clave: Instructor123*  (Colorimetría / Balayage)
--
-- 3. ESTUDIANTES (Rol: Estudiante, Catálogo, Clases, Entregas y Certificados):
--    - camila.herrera@gmail.com          | Clave: Estudiante123*  (Solvente, múltiples cursos y certificado)
--    - sofia.morales@hotmail.com         | Clave: Estudiante123*  (Solvente, entregas aprobadas)
--    - mariana.pena@gmail.com            | Clave: Estudiante123*  (Solvente, cursos cruzados)
--    - isabella.castro@gmail.com         | Clave: Estudiante123*  (Solvente, graduada)
--    - valentina.ortiz@outlook.com       | Clave: Estudiante123*  (Solvente, entregas activas)
--    - lucia.mendez@gmail.com            | Clave: Estudiante123*  (Deudora / Cupo Reservado sin pago)
--    - valeria.navarro@yahoo.com         | Clave: Estudiante123*  (Deudora / Pago en revisión 'Pendiente')
--    - maria.rivas@gmail.com             | Clave: Estudiante123*  (Deudora / Pago en revisión 'Pendiente')
--    - gabriel.pineda@gmail.com          | Clave: Estudiante123*  (Barbería graduado con Certificado)
--    - kevin.colmenarez@gmail.com        | Clave: Estudiante123*  (Barbería graduado con Certificado)
-- ==============================================================================

USE ds_beauty_academy_db;

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ------------------------------------------------------------------------------
-- LIMPIEZA PREVIA DE TABLAS (Usa DELETE para evitar error #1701 de InnoDB en FKs)
-- ------------------------------------------------------------------------------
DELETE FROM certificados;
DELETE FROM entregas_evaluaciones;
DELETE FROM evaluaciones;
DELETE FROM comprobante_pagos;
DELETE FROM matriculas;
DELETE FROM recursos_curso;
DELETE FROM recursos;
DELETE FROM clases;
DELETE FROM instructor_curso;
DELETE FROM cursos;
DELETE FROM diplomas;
DELETE FROM especialidad_instructor;
DELETE FROM especialidades;
DELETE FROM banco;
DELETE FROM usuarios;
DELETE FROM estudiantes;
DELETE FROM instructores;
DELETE FROM roles;

-- Reinicio de secuencias AUTO_INCREMENT
ALTER TABLE certificados AUTO_INCREMENT = 1;
ALTER TABLE entregas_evaluaciones AUTO_INCREMENT = 1;
ALTER TABLE evaluaciones AUTO_INCREMENT = 1;
ALTER TABLE comprobante_pagos AUTO_INCREMENT = 1;
ALTER TABLE matriculas AUTO_INCREMENT = 1;
ALTER TABLE recursos AUTO_INCREMENT = 1;
ALTER TABLE clases AUTO_INCREMENT = 1;
ALTER TABLE instructor_curso AUTO_INCREMENT = 1;
ALTER TABLE cursos AUTO_INCREMENT = 1;
ALTER TABLE diplomas AUTO_INCREMENT = 1;
ALTER TABLE especialidades AUTO_INCREMENT = 1;
ALTER TABLE banco AUTO_INCREMENT = 1;
ALTER TABLE usuarios AUTO_INCREMENT = 1;
ALTER TABLE estudiantes AUTO_INCREMENT = 1;
ALTER TABLE instructores AUTO_INCREMENT = 1;
ALTER TABLE roles AUTO_INCREMENT = 1;

-- ==============================================================================
-- 1. TABLA: roles
-- ==============================================================================
INSERT INTO roles (id_rol, nombre_rol, descripcion_rol, estatus_rol, fecha_creacion) VALUES
(1, 'Administrador', 'Control total, gestión financiera, académica, auditoría y configuración general del sistema.', 1, '2026-01-01 08:00:00'),
(2, 'Instructor', 'Gestión de clases, recursos didácticos, asignaciones y retroalimentación de evaluaciones.', 1, '2026-01-01 08:00:00'),
(3, 'Estudiante', 'Acceso a catálogo de cursos, aulas virtuales, entregas de prácticas y descarga de certificados.', 1, '2026-01-01 08:00:00');

-- ==============================================================================
-- 2. TABLA: banco
-- ==============================================================================
INSERT INTO banco (id_banco, nombre_banco, estatus_banco, fecha_creacion) VALUES
(1, 'Banco de Venezuela (BDV)', 1, '2026-01-01 08:00:00'),
(2, 'Banesco Banco Universal', 1, '2026-01-01 08:00:00'),
(3, 'Banco Mercantil', 1, '2026-01-01 08:00:00'),
(4, 'BBVA Provincial', 1, '2026-01-01 08:00:00'),
(5, 'Bancamiga Banco Universal', 1, '2026-01-01 08:00:00'),
(6, 'Banco Nacional de Crédito (BNC)', 1, '2026-01-01 08:00:00'),
(7, 'Pago Móvil Interbancario', 1, '2026-01-01 08:00:00'),
(8, 'Zelle / Transferencia Internacional USD', 1, '2026-01-01 08:00:00'),
(9, 'Binance Pay (USDT)', 1, '2026-01-01 08:00:00');

-- ==============================================================================
-- 3. TABLA: especialidades
-- ==============================================================================
INSERT INTO especialidades (id_especialidad, nombre_especialidad, estatus_especialidad, fecha_creacion) VALUES
(1, 'Maquillaje Profesional y Social', 1, '2026-01-05 09:00:00'),
(2, 'Cosmetología y Cuidado Facial', 1, '2026-01-05 09:00:00'),
(3, 'Diseño, Perfilado y Micropigmentación de Cejas', 1, '2026-01-05 09:00:00'),
(4, 'Extensiones y Lifting de Pestañas (Lash Artist)', 1, '2026-01-05 09:00:00'),
(5, 'Manicura Rusa, Esculpido y Polygel', 1, '2026-01-05 09:00:00'),
(6, 'Colorimetría Capilar y Balayage Master', 1, '2026-01-05 09:00:00'),
(7, 'Barbería Clásica y Tendencias Urbanas', 1, '2026-01-05 09:00:00'),
(8, 'Masajes Reductores y Maderoterapia', 1, '2026-01-05 09:00:00'),
(9, 'Depilación Corporal y Facial con Cera e Hilo', 1, '2026-01-05 09:00:00');

-- ==============================================================================
-- 4. TABLA: instructores
-- ==============================================================================
INSERT INTO instructores (id_instructor, primer_nombre_instructor, segundo_nombre_instructor, primer_apellido_instructor, segundo_apellido_instructor, cedula_instructor, correo_instructor, telefono_principal_instructor, telefono_alternativo_instructor, usuario_instagram_instructor, estatus_instructor, fecha_creacion) VALUES
(1, 'Daniela', 'Sofía', 'Sánchez', 'Briceño', 'V-19482103', 'daniela.sanchez@beautyacademy.com', '+58 412 5551020', '+58 251 2348911', '@daniela_makeup_pro', 1, '2026-01-10 10:00:00'),
(2, 'Valeria', 'Alexandra', 'Mendoza', 'Rivas', 'V-20891432', 'valeria.mendoza@beautyacademy.com', '+58 414 7892341', '+58 251 4458922', '@valeria_lashes_art', 1, '2026-01-10 10:30:00'),
(3, 'Mariana', 'Carolina', 'Gómez', 'Torres', 'V-18342910', 'mariana.gomez@beautyacademy.com', '+58 424 5123984', NULL, '@mariana_nails_studio', 1, '2026-01-11 09:15:00'),
(4, 'Carlos', 'Eduardo', 'Silva', 'Hernández', 'V-17892345', 'carlos.silva@beautyacademy.com', '+58 416 9821430', '+58 251 7712390', '@carlos_barber_master', 1, '2026-01-11 11:00:00'),
(5, 'Andreína', 'del Valle', 'Castillo', 'Lugo', 'V-21453890', 'andreina.castillo@beautyacademy.com', '+58 412 3341902', NULL, '@andreina_skincare', 1, '2026-01-12 08:45:00'),
(6, 'Gabriela', 'Elena', 'Fuentes', 'Morales', 'V-16982341', 'gabriela.fuentes@beautyacademy.com', '+58 414 6210984', '+58 251 8891234', '@gabriela_brows_expert', 1, '2026-01-12 14:20:00'),
(7, 'Nelson', 'José', 'Ramírez', 'Peña', 'V-15432890', 'nelson.ramirez@beautyacademy.com', '+58 424 5438901', NULL, '@nelson_hair_artist', 1, '2026-01-13 10:00:00'),
(8, 'Patricia', 'Valentina', 'Rojas', 'Medina', 'V-22341982', 'patricia.rojas@beautyacademy.com', '+58 412 8765432', NULL, '@patricia_spa_wellness', 0, '2026-01-13 16:30:00');

-- ==============================================================================
-- 5. TABLA: especialidad_instructor
-- ==============================================================================
INSERT INTO especialidad_instructor (id_instructor, id_especialidad, fecha_creacion) VALUES
(1, 1, '2026-01-10 10:00:00'), -- Daniela Sánchez: Maquillaje Profesional
(1, 3, '2026-01-10 10:00:00'), -- Daniela Sánchez: Cejas y Visagismo
(2, 4, '2026-01-10 10:30:00'), -- Valeria Mendoza: Pestañas
(2, 3, '2026-01-10 10:30:00'), -- Valeria Mendoza: Cejas
(3, 5, '2026-01-11 09:15:00'), -- Mariana Gómez: Uñas y Polygel
(4, 7, '2026-01-11 11:00:00'), -- Carlos Silva: Barbería
(5, 2, '2026-01-12 08:45:00'), -- Andreína Castillo: Cosmetología
(5, 8, '2026-01-12 08:45:00'), -- Andreína Castillo: Masajes
(6, 3, '2026-01-12 14:20:00'), -- Gabriela Fuentes: Microblading
(6, 1, '2026-01-12 14:20:00'), -- Gabriela Fuentes: Maquillaje
(7, 6, '2026-01-13 10:00:00'), -- Nelson Ramírez: Colorimetría Capilar
(8, 8, '2026-01-13 16:30:00'), -- Patricia Rojas: Masajes
(8, 9, '2026-01-13 16:30:00'); -- Patricia Rojas: Depilación

-- ==============================================================================
-- 6. TABLA: diplomas (Acreditaciones profesionales de los instructores)
-- ==============================================================================
INSERT INTO diplomas (id_diploma, nombre_diploma, descripcion_diploma, categoria_diploma, url_pdf_diploma, id_instructor, estatus_diploma, fecha_creacion) VALUES
(1, 'Máster Internacional en Maquillaje HD y Novias', 'Certificación avanzada avalada por Kryolan Pro Argentina en técnicas de piel blindada y fotografía HD.', 'Maquillaje', 'assets/uploads/diplomas/diploma_daniela_kryolan.pdf', 1, 1, '2026-01-15 10:00:00'),
(2, 'Especialista en Visagismo y Morfología Facial', 'Diplomado en diseño armónico de rostro y cejas según proporción áurea y calibrador estético.', 'Estética', 'assets/uploads/diplomas/diploma_daniela_visagismo.pdf', 1, 1, '2026-01-15 10:15:00'),
(3, 'Grand Master Lash Artist en Mega Volumen Ruso', 'Acreditación técnica internacional PhiLashes Viena en aislamiento milimétrico y salud folicular.', 'Pestañas', 'assets/uploads/diplomas/diploma_valeria_philashes.pdf', 2, 1, '2026-01-15 11:00:00'),
(4, 'Lash Lifting & Keratin Infusion Certification', 'Certificación técnica por Elleebana Latinoamérica en ondulación química y nutrición con keratina.', 'Pestañas', 'assets/uploads/diplomas/diploma_valeria_elleebana.pdf', 2, 1, '2026-01-15 11:30:00'),
(5, 'Máster Educador en Manicura Rusa y Estructuras Extremas', 'Graduación de honor en Kinetics Nails Academy en corte de cutícula con fresa esférica.', 'Uñas', 'assets/uploads/diplomas/diploma_mariana_kinetics.pdf', 3, 1, '2026-01-16 09:00:00'),
(6, 'Técnico Especialista en Nail Art 3D y Acrílico Avanzado', 'Capacitación presencial intensiva en Organic Nails Colombia con técnicas de bajo relieve.', 'Uñas', 'assets/uploads/diplomas/diploma_mariana_organic.pdf', 3, 1, '2026-01-16 09:30:00'),
(7, 'Barber Master Pro - Fade & Blade Certification', 'Certificación internacional en cortes modernos de navaja libre, degradados skin fade y perfilado.', 'Barbería', 'assets/uploads/diplomas/diploma_carlos_barber.pdf', 4, 1, '2026-01-16 14:00:00'),
(8, 'Cosmiatra Clínica y Protocolos de Rejuvenecimiento Facial', 'Diplomado de la Sociedad Panamericana de Cosmiatría en principios activos y bioseguridad.', 'Cosmetología', 'assets/uploads/diplomas/diploma_andreina_cosmiatria.pdf', 5, 1, '2026-01-17 10:00:00'),
(9, 'Técnicas Avanzadas en Dermapen y Peelings Químicos', 'Especialización estética no invasiva en inducción de colágeno y fotoprotección clínica.', 'Cosmetología', 'assets/uploads/diplomas/diploma_andreina_peelings.pdf', 5, 1, '2026-01-17 10:45:00'),
(10, 'Phibrows Microblading Royal Artist Certificate', 'Máxima distinción PhiAcademy Europa en micropigmentación pelo a pelo de aspecto hiperrealista.', 'Micropigmentación', 'assets/uploads/diplomas/diploma_gabriela_phibrows.pdf', 6, 1, '2026-01-18 12:00:00'),
(11, 'Colorista Master en Técnicas de Iluminación y Balayage', 'Acreditación Schwarzkopf Professional Academy en diagramación y decoloración sin daño estructural.', 'Peluquería', 'assets/uploads/diplomas/diploma_nelson_schwarzkopf.pdf', 7, 1, '2026-01-18 15:30:00'),
(12, 'Terapeuta Holística en Maderoterapia y Drenaje Linfático', 'Certificación en aparatología reductiva, moldeamiento con copas suecas y maniobras manuales.', 'Corporal', 'assets/uploads/diplomas/diploma_patricia_maderoterapia.pdf', 8, 1, '2026-01-19 09:00:00');

-- ==============================================================================
-- 7. TABLA: cursos
-- ==============================================================================
INSERT INTO cursos (id_curso, nombre_curso, descripcion_curso, tipo_curso, precio_preventa, precio_normal, fecha_fin_preventa, fecha_inicio_curso, fecha_fin_curso, limite_cupos, estatus_curso, fecha_creacion) VALUES
(1, 'Diplomado Integral de Maquillaje Profesional y Social', 'Formación intensiva de 3 meses que abarca desde la preparación del lienzo facial, corrección cromática y visagismo, hasta looks nupciales de alta gama y maquillaje HD para fotografía y video.', 'Curso completo', 140.00, 180.00, '2026-08-30 23:59:59', '2026-09-15 09:00:00', '2026-12-15 13:00:00', 15, 'Venta Normal', '2026-08-01 10:00:00'),
(2, 'Masterclass: Extensiones de Pestañas Volumen Ruso y Efecto Whispy', 'Entrenamiento especializado en técnicas avanzadas de armado de abanicos perfectos de 2D a 6D, mapeos de mirada en tendencia, química del adhesivo y retención prolongada.', 'Masterclass', 60.00, 85.00, '2026-10-05 23:59:59', '2026-10-25 09:00:00', '2026-10-26 18:00:00', 12, 'Venta Normal', '2026-09-01 11:30:00'),
(3, 'Curso Intensivo de Manicura Rusa Combinada y Nivelación con Polygel', 'Aprende el uso técnico del torno micromotor con fresas diamantadas para manicura de precisión, sellado de cutícula, nivelación con bases rubber y extensión escultural con polygel.', 'Curso completo', 95.00, 120.00, '2026-09-01 23:59:59', '2026-09-20 14:00:00', '2026-11-20 18:00:00', 10, 'Venta Normal', '2026-08-15 09:00:00'),
(4, 'Especialización en Microblading y Microshading Pelo a Pelo', 'Diseño arquitectónico de cejas mediante el calibre áureo, pigmentología avanzada para evitar virajes indeseados, patrones de espina naturales e implantación manual segura.', 'Curso completo', 160.00, 210.00, '2026-11-10 23:59:59', '2026-11-25 08:30:00', '2027-02-25 12:30:00', 8, 'Preventa', '2026-10-01 14:00:00'),
(5, 'Masterclass: Colorimetría Avanzada y Balayage Freehand', 'Taller teórico-práctico de decoloración controlada, neutralización matemática de reflejos rojizos/naranjas, diagramaciones modernas de luces y matizaciones frías de larga duración.', 'Masterclass', 50.00, 75.00, '2026-11-01 23:59:59', '2026-11-18 10:00:00', '2026-11-19 16:00:00', 15, 'Preventa', '2026-10-05 16:00:00'),
(6, 'Diplomado en Cosmetología Facial y Tratamientos Antiage', 'Estudio integral de la fisiología cutánea, biotipos y fototipos, aparatología estética moderna (alta frecuencia, ultrasonido, espátula galvánica) y peelings químicos orgánicos.', 'Curso completo', 120.00, 150.00, '2026-02-01 23:59:59', '2026-02-15 08:00:00', '2026-06-15 12:00:00', 12, 'Cerrado', '2026-01-10 09:00:00'),
(7, 'Curso de Barbería Integral y Técnicas de Degradado (Fades)', 'Dominio de tijeras, máquinas clipper y navajín clásico. Protocolos de higiene, desvanecidos limpios (Skin Fade, Drop Fade, Taper), afeitado tradicional con toallas calientes y perfilado.', 'Curso completo', 80.00, 110.00, '2026-03-01 23:59:59', '2026-03-15 14:00:00', '2026-07-15 18:00:00', 10, 'Cerrado', '2026-02-01 10:00:00'),
(8, 'Taller Especializado: Maderoterapia Estética y Drenaje Linfático Manual', 'Protocolos reductores, anticelulíticos y reafirmantes corporales empleando instrumentos de madera noble y técnicas de maniobra linfática de Vodder.', 'Curso completo', NULL, 90.00, NULL, '2027-01-20 09:00:00', '2027-03-20 13:00:00', 10, 'Borrador', '2026-10-15 15:00:00');

-- ==============================================================================
-- 8. TABLA: instructor_curso
-- ==============================================================================
INSERT INTO instructor_curso (id_instructor_curso, id_instructor, id_curso, fecha_creacion) VALUES
(1, 1, 1, '2026-08-01 10:30:00'), -- Daniela Sánchez -> Diplomado Maquillaje (Activo)
(2, 2, 2, '2026-09-01 12:00:00'), -- Valeria Mendoza -> Masterclass Pestañas (Activo)
(3, 3, 3, '2026-08-15 09:30:00'), -- Mariana Gómez -> Manicura Rusa (Activo)
(4, 6, 4, '2026-10-01 14:30:00'), -- Gabriela Fuentes -> Microblading (Preventa)
(5, 7, 5, '2026-10-05 16:30:00'), -- Nelson Ramírez -> Colorimetría (Preventa)
(6, 5, 6, '2026-01-10 09:30:00'), -- Andreína Castillo -> Cosmetología (Cerrado - Histórico)
(7, 4, 7, '2026-02-01 10:30:00'), -- Carlos Silva -> Barbería (Cerrado - Histórico)
(8, 8, 8, '2026-10-15 15:30:00'); -- Patricia Rojas -> Maderoterapia (Borrador)

-- ==============================================================================
-- 9. TABLA: clases (Lecciones estructuradas por instancia de curso)
-- ==============================================================================
INSERT INTO clases (id_clase, titulo_clase, descripcion_clase, url_video_clase, orden_clase, estatus_clase, id_instructor_curso, fecha_creacion) VALUES
-- Curso 1: Diplomado de Maquillaje Profesional (id_instructor_curso = 1)
(1, 'Introducción a la Piel: Biotipos y Preparación Facial Pre-Maquillaje', 'Análisis de piel grasa, seca, mixta y sensible. Protocolos de limpieza, tónicos hidratantes, sueros y primers correctores.', 'https://player.vimeo.com/video/892019201', 1, 1, 1, '2026-08-10 09:00:00'),
(2, 'Teoría del Color Aplicada: Neutralización de Ojeras y Manchas', 'Manejo del círculo cromático de Itten, correctores complementarios (naranja, verde, lila) y aplicación de bases fluidas.', 'https://player.vimeo.com/video/892019202', 2, 1, 1, '2026-08-10 09:30:00'),
(3, 'Morfología Facial: Técnica de Contorno e Iluminación (Baking & Strobing)', 'Visagismo para rostro ovalado, redondo, cuadrado y corazón. Creación de luces y sombras en crema y sellado con polvos traslúcidos.', 'https://player.vimeo.com/video/892019203', 3, 1, 1, '2026-08-10 10:00:00'),
(4, 'Maquillaje de Ojos: Ahumado Clásico (Smokey Eyes) y Delineado Infinito', 'Degradado perfecto de sombras oscuras a tonos de transición, técnica de delineado esfumado y difuminado sin cortes.', 'https://player.vimeo.com/video/892019204', 4, 1, 1, '2026-08-10 10:30:00'),
(5, 'Maquillaje Social Glam: Cut Crease Abierto con Aplicación de Pigmentos', 'Corte de cuenca con corrector de alta cobertura, fijación de glitter prensado y pestañas postizas en tira 3D.', 'https://player.vimeo.com/video/892019205', 5, 1, 1, '2026-08-10 11:00:00'),
(6, 'Look Nupcial de Larga Duración y Sellado Blindado a Prueba de Agua', 'Fórmulas blindadas para resistir lágrimas, sudor y alta humedad. Sellado con bruma fijadora hidrófoba profesional.', 'https://player.vimeo.com/video/892019206', 6, 1, 1, '2026-08-10 11:30:00'),

-- Curso 2: Masterclass de Pestañas (id_instructor_curso = 2)
(7, 'Tricología Ocular, Ciclo de la Pestaña Natural y Contraindicaciones', 'Fases anágena, catágena y telógena. Alergias comunes, prueba de parche y afecciones como blefaritis.', 'https://player.vimeo.com/video/893112201', 1, 1, 2, '2026-09-10 14:00:00'),
(8, 'Química del Cianoacrilato: Manejo de Temperatura y Humedad', 'Polimerización del pegamento, higrómetro digital, tiempos de secado de 0.5 a 2 segundos y uso correcto del nano mister.', 'https://player.vimeo.com/video/893112202', 2, 1, 2, '2026-09-10 14:30:00'),
(9, 'Geometría del Abanico: Técnicas de Pellizco y Ramo Perfecto 3D a 6D', 'Creación de abanicos simétricos con base fina y cerrada. Calibres 0.05 y 0.07 para cuidar el peso en la pestaña natural.', 'https://player.vimeo.com/video/893112203', 3, 1, 2, '2026-09-10 15:00:00'),
(10, 'Mapeos de Mirada en Tendencia: Efecto Kim, Whispy, Foxy y Ojo de Gato', 'Direccionamiento, capas superiores e inferiores (bridging) y picos de longitud estratégicos para estilizar el ojo.', 'https://player.vimeo.com/video/893112204', 4, 1, 2, '2026-09-10 15:30:00'),

-- Curso 3: Manicura Rusa y Polygel (id_instructor_curso = 3)
(11, 'Anatomía del Aparato Ungueal y Protocolo de Esterilización en Autoclave', 'Eponiquio, cutícula real, hiponiquio y lecho ungueal. Manejo de sustancias desinfectantes y calor seco vs húmedo.', 'https://player.vimeo.com/video/894223301', 1, 1, 3, '2026-08-25 10:00:00'),
(12, 'Técnica de Manicura Rusa con Fresa Diamantada Flama y Esfera', 'Velocidades del micromotor (RPM), ángulo de 45 grados, levantamiento del bolsillo de cutícula y pulido de rodetes.', 'https://player.vimeo.com/video/894223302', 2, 1, 3, '2026-08-25 10:30:00'),
(13, 'Nivelación Perfecta con Base Rubber y Creación del Ápice', 'Control de la gota de gel rubber, reflejo del aro de luz perfecto (blique) y refuerzo anti-roturas en uñas frágiles.', 'https://player.vimeo.com/video/894223303', 3, 1, 3, '2026-08-25 11:00:00'),
(14, 'Construcción y Encapsulado con Polygel sobre Moldes Dual System', 'Colocación de tips duales, manipulación de la perla con alcohol isopropílico y curado en lámpara UV/LED.', 'https://player.vimeo.com/video/894223304', 4, 1, 3, '2026-08-25 11:30:00'),
(15, 'Esmaltado Semipermanente Bajo Cutícula y Finalización Glossy', 'Técnica de pincel fino liner para pintar 1mm bajo el pliegue proximal, garantizando un crecimiento limpio de 3 semanas.', 'https://player.vimeo.com/video/894223305', 5, 1, 3, '2026-08-25 12:00:00'),

-- Curso 4: Microblading y Cejas (id_instructor_curso = 4)
(16, 'Calibre Áureo y Visagismo de Cejas: Mapeo con Hilo Entintado', 'Localización del punto de inicio, arco y cola de la ceja respetando la simetría ósea y proporción de Fibonacci.', 'https://player.vimeo.com/video/895334401', 1, 1, 4, '2026-10-10 09:00:00'),
(17, 'Teoría del Pigmento: Subtonos Cálidos, Fríos y Prevención del Viraje', 'Fórmula de óxidos de hierro vs pigmentos orgánicos. Modificadores naranja y amarillo para evitar cejas azules o grisáceas.', 'https://player.vimeo.com/video/895334402', 2, 1, 4, '2026-10-10 09:30:00'),
(18, 'Patronaje de Espina Pelo a Pelo en Piel Sintética de Silicona', 'Dirección de trazos con inductor manual tebori y plumillas nano. Manejo de profundidad en la capa basal epidérmica.', 'https://player.vimeo.com/video/895334403', 3, 1, 4, '2026-10-10 10:00:00'),
(19, 'Bioseguridad, Anestesia Tópica y Cuidados Posteriores (Aftercare)', 'Desinfección de cabina, anestesias primarias y secundarias, y proceso de cicatrización durante los primeros 30 días.', 'https://player.vimeo.com/video/895334404', 4, 1, 4, '2026-10-10 10:30:00'),

-- Curso 5: Colorimetría y Balayage (id_instructor_curso = 5)
(20, 'Escala del pH Capilar, Fondo de Aclaración y Alturas de Tono', 'Desde el tono 1 (negro) al 10 (rubio extra claro). Fondos residuales de aclaración rojo, cobrizo y amarillo pálido.', 'https://player.vimeo.com/video/896445501', 1, 1, 5, '2026-10-12 11:00:00'),
(21, 'Formulación Matemática de Matices y Neutralizaciones', 'Fórmula de mezcla de peróxidos de 10, 20, 30 volúmenes y regla del 11 para neutralizar reflejos indeseados.', 'https://player.vimeo.com/video/896445502', 2, 1, 5, '2026-10-12 11:30:00'),
(22, 'Técnica de Cardado y Esfumado de Raíces (Smoky Root & Teasylights)', 'Porcentaje de cardado al 30%, 50% y 70% para transiciones ultra suaves sin líneas de demarcación.', 'https://player.vimeo.com/video/896445503', 3, 1, 5, '2026-10-12 12:00:00'),
(23, 'Aplicación de Balayage Freehand al Aire Libre y Protección Plex', 'Uso de paletinas especiales, arcillas decolorantes compactas y aditivos de aminoácidos para preservar la fibra.', 'https://player.vimeo.com/video/896445504', 4, 1, 5, '2026-10-12 12:30:00'),

-- Curso 6: Cosmetología Facial (id_instructor_curso = 6 - Histórico)
(24, 'Diagnóstico Cutáneo con Lámpara de Wood y Ficha Cosmiátrica', 'Fluorescencias según hidratación, hiperqueratosis o presencia de bacterias propionibacterium acnes.', 'https://player.vimeo.com/video/890111101', 1, 1, 6, '2026-01-20 08:30:00'),
(25, 'Protocolo de Limpieza Facial Profunda y Extracción con Sacacomedón', 'Ablandadores químicos con saponinas, dilatación por vapor de ozono y extracción sin marcar el tejido cutáneo.', 'https://player.vimeo.com/video/890111102', 2, 1, 6, '2026-01-20 09:00:00'),
(26, 'Aparatología Estética: Alta Frecuencia, Ultrasonido y Máscara LED', 'Efecto bactericida del gas ozono, penetración de ampollas liposomadas y fototerapia cromática rejuvenecedora.', 'https://player.vimeo.com/video/890111103', 3, 1, 6, '2026-01-20 09:30:00'),
(27, 'Peelings Químicos con Ácido Glicólico, Salicílico y Mandélico', 'Porcentajes de concentración, escala de pH, tiempos de exposición y neutralización con solución bicarbonatada.', 'https://player.vimeo.com/video/890111104', 4, 1, 6, '2026-01-20 10:00:00'),

-- Curso 7: Barbería Integral (id_instructor_curso = 7 - Histórico)
(28, 'Manejo, Desinfección y Calibración de Máquinas Clipper y Shaver', 'Ajuste de cuchillas a cero (Zero Gap), lubricación, peines guía del 0.5 al 4 y pulverización bactericida con refrigerante.', 'https://player.vimeo.com/video/891222201', 1, 1, 7, '2026-02-10 14:00:00'),
(29, 'El Arte del Degradado: Low Fade, Mid Fade y High Fade Paso a Paso', 'Línea de guía con trimmer, borrado con shaver, palanca abierta/cerrada y desvanecido limpio sin sombras oscuras.', 'https://player.vimeo.com/video/891222202', 2, 1, 7, '2026-02-10 14:30:00'),
(30, 'Técnica de Tijera sobre Peine (Scissor Over Comb) y Texturizado', 'Control del volumen superior, tijeras de entresacar al 25% y peinados pompadour y crop francés.', 'https://player.vimeo.com/video/891222203', 3, 1, 7, '2026-02-10 15:00:00'),
(31, 'Perfilado de Barba Tradicional y Ritual de Toallas Calientes', 'Aplicación de aceites pre-afeitado, vaporización de aceites esenciales de eucalipto y afeitado a favor del grano.', 'https://player.vimeo.com/video/891222204', 4, 1, 7, '2026-02-10 15:30:00');

-- ==============================================================================
-- 10. TABLA: recursos (Guías, manuales en PDF y material descargable)
-- ==============================================================================
INSERT INTO recursos (id_recurso, nombre_recurso, descripcion_recurso, url_archivo_recurso, estatus_recurso, fecha_creacion) VALUES
(1, 'Manual Guía de Colorimetría y Círculo Cromático de Mezclas', 'Compendio digital completo sobre la teoría del color, neutralizaciones y reglas de armonía para maquillaje profesional.', 'assets/uploads/recursos/manual_colorimetria_guia.pdf', 1, '2026-08-05 10:00:00'),
(2, 'Plantilla Imprimible de Face Chart para Diseño de Maquillaje', 'Hojas de práctica técnica con rostros ilustrados de proporciones simétricas para diagramar looks antes del cliente.', 'assets/uploads/recursos/plantilla_facecharts_ds.pdf', 1, '2026-08-05 10:30:00'),
(3, 'Protocolo de Bioseguridad y Esterilización en Cabina de Belleza', 'Normativas sanitarias obligatorias, preparación de soluciones antisépticas y rotación de material descartable.', 'assets/uploads/recursos/protocolo_bioseguridad_2026.pdf', 1, '2026-08-05 11:00:00'),
(4, 'Guía de Mapeos Oculares para Extensiones de Pestañas', 'Esquema gráfico de curvaturas (C, CC, D, L) y longitudes óptimas para cada tipo de ojo: almendrado, encapotado y hundido.', 'assets/uploads/recursos/guia_mapeos_lashes.pdf', 1, '2026-09-02 12:00:00'),
(5, 'Ficha Técnica de Consentimiento Informado para Lashistas', 'Modelo legal de descargo de responsabilidad y registro de historial alergénico de la clienta.', 'assets/uploads/recursos/consentimiento_informado_pestanas.pdf', 1, '2026-09-02 12:30:00'),
(6, 'Catálogo de Fresas Diamantadas y sus Usos en Manicura Rusa', 'Guía de código de colores en brocas (rojo, azul, verde) y selección según la sensibilidad de la piel del cliente.', 'assets/uploads/recursos/catalogo_fresas_manicura.pdf', 1, '2026-08-20 14:00:00'),
(7, 'Paso a Paso: Preparadores Químicos (Nail Prep, Primer & Bonder)', 'Explicación del desengrasado con alcohol deshidratador y adhesión química para evitar desprendimientos prematuros.', 'assets/uploads/recursos/guia_preparadores_quimicos.pdf', 1, '2026-08-20 14:30:00'),
(8, 'Regla de Tres y Fórmulas Magistrales para Micropigmentación', 'Cálculo de tonos y neutralización de pigmentos para cejas según el fototipo de Fitzpatrick.', 'assets/uploads/recursos/formulas_pigmentos_cejas.pdf', 1, '2026-10-02 09:00:00'),
(9, 'Plantilla de Práctica de Trazos de Espina Microblading', 'Patrón imprimible de espinas 3, 5 y 6 para practicar fluidez y presión uniforme de muñeca.', 'assets/uploads/recursos/ejercicios_trazos_espinas.pdf', 1, '2026-10-02 09:30:00'),
(10, 'Rueda de Decoloración y Fondos de Aclaración Capilar', 'Tabla de fondos residuales del 1 al 10 y fórmulas exactas de matización pastel para rubios platinados.', 'assets/uploads/recursos/rueda_fondos_aclaracion.pdf', 1, '2026-10-06 15:00:00'),
(11, 'Ficha Clínica Cosmiátrica y Evaluación de Biotipos Cutáneos', 'Formato clínico para registro de hidratación, sensibilidad, pigmentaciones y registro fotográfico.', 'assets/uploads/recursos/ficha_evaluacion_facial.pdf', 1, '2026-01-15 11:00:00'),
(12, 'Guía de Tipos de Ácidos y pH para Peelings Químicos Seguros', 'Resumen dosimétrico de alfahidroxiácidos (AHA) y betahidroxiácidos (BHA) para cabina cosmética.', 'assets/uploads/recursos/guia_peelings_esteticos.pdf', 1, '2026-01-15 11:30:00'),
(13, 'Póster de Ángulos de Elevación y Corte de Cabello Masculino', 'Diagrama ilustrativo de grados de corte (0°, 45°, 90°, 180°) y líneas de peso en barbería.', 'assets/uploads/recursos/poster_angulos_corte.pdf', 1, '2026-02-05 16:00:00'),
(14, 'Guía de Mantenimiento y Calibración de Máquinas Clipper', 'Procedimiento de desmontaje, desinfección ultravioleta y cambio de resortes y levas oscilantes.', 'assets/uploads/recursos/mantenimiento_maquinas_barber.pdf', 1, '2026-02-05 16:30:00');

-- ==============================================================================
-- 11. TABLA: recursos_curso (Vinculación de material con cada curso)
-- ==============================================================================
INSERT INTO recursos_curso (id_curso, id_recurso, fecha_creacion) VALUES
(1, 1, '2026-08-05 10:00:00'), -- Curso 1: Manual Colorimetría
(1, 2, '2026-08-05 10:30:00'), -- Curso 1: Facecharts
(1, 3, '2026-08-05 11:00:00'), -- Curso 1: Bioseguridad
(2, 3, '2026-09-02 12:00:00'), -- Curso 2: Bioseguridad
(2, 4, '2026-09-02 12:00:00'), -- Curso 2: Mapeos Oculares
(2, 5, '2026-09-02 12:30:00'), -- Curso 2: Consentimiento Pestañas
(3, 3, '2026-08-20 14:00:00'), -- Curso 3: Bioseguridad
(3, 6, '2026-08-20 14:00:00'), -- Curso 3: Catálogo Fresas
(3, 7, '2026-08-20 14:30:00'), -- Curso 3: Preparadores Químicos
(4, 3, '2026-10-02 09:00:00'), -- Curso 4: Bioseguridad
(4, 8, '2026-10-02 09:00:00'), -- Curso 4: Fórmulas Pigmentos
(4, 9, '2026-10-02 09:30:00'), -- Curso 4: Plantilla Trazos
(5, 10, '2026-10-06 15:00:00'), -- Curso 5: Rueda Decoloración
(6, 3, '2026-01-15 11:00:00'), -- Curso 6: Bioseguridad
(6, 11, '2026-01-15 11:00:00'), -- Curso 6: Ficha Clínica Facial
(6, 12, '2026-01-15 11:30:00'), -- Curso 6: Guía Peelings
(7, 3, '2026-02-05 16:00:00'), -- Curso 7: Bioseguridad
(7, 13, '2026-02-05 16:00:00'), -- Curso 7: Ángulos de Corte
(7, 14, '2026-02-05 16:30:00'); -- Curso 7: Mantenimiento Clippers

-- ==============================================================================
-- 12. TABLA: estudiantes (Población estudiantil diversa y realista)
-- ==============================================================================
INSERT INTO estudiantes (id_estudiante, primer_nombre_estudiante, segundo_nombre_estudiante, primer_apellido_estudiante, segundo_apellido_estudiante, cedula_estudiante, correo_estudiante, telefono_principal_estudiante, telefono_alternativo_estudiante, usuario_instagram_estudiante, estatus_estudiante, fecha_creacion) VALUES
(1, 'Camila', 'Valentina', 'Herrera', 'Rojas', 'V-26182901', 'camila.herrera@gmail.com', '+58 412 1029384', '+58 251 2618290', '@camila_makeuplover', 1, '2026-01-20 09:00:00'),
(2, 'Sofía', 'Isabella', 'Morales', 'Delgado', 'V-27453182', 'sofia.morales@hotmail.com', '+58 414 2938471', NULL, '@sofia_beauty_vzla', 1, '2026-01-20 09:30:00'),
(3, 'Mariana', 'Andrea', 'Peña', 'Colmenarez', 'V-28192039', 'mariana.pena@gmail.com', '+58 424 3847291', '+58 251 3847291', '@mariana_andrea_mua', 1, '2026-01-21 10:15:00'),
(4, 'Isabella', 'Nicole', 'Castro', 'Vargas', 'V-26983410', 'isabella.castro@gmail.com', '+58 416 4738291', NULL, '@isabella_castro_art', 1, '2026-01-21 11:00:00'),
(5, 'Valentina', 'Estefanía', 'Ortiz', 'Silva', 'V-27891234', 'valentina.ortiz@outlook.com', '+58 412 5829103', '+58 251 5829103', '@valen_makeup_studio', 1, '2026-01-22 08:30:00'),
(6, 'Lucía', 'Gabriela', 'Méndez', 'Paredes', 'V-28472910', 'lucia.mendez@gmail.com', '+58 414 6918234', NULL, '@lucia_g_mendez', 1, '2026-01-22 14:00:00'),
(7, 'Valeria', 'Coromoto', 'Navarro', 'Gil', 'V-26341890', 'valeria.navarro@yahoo.com', '+58 424 7182930', '+58 251 7182930', '@valeria_navarro_nails', 1, '2026-01-23 09:45:00'),
(8, 'María', 'Alejandra', 'Rivas', 'Zambrano', 'V-27123894', 'maria.rivas@gmail.com', '+58 412 8291047', NULL, '@mariale_rivas_estilismo', 1, '2026-01-23 11:20:00'),
(9, 'Andrea', 'Carolina', 'Torres', 'Medina', 'V-28901234', 'andrea.torres@gmail.com', '+58 414 9382019', '+58 251 9382019', '@andrea_torres_vzla', 1, '2026-01-24 10:00:00'),
(10, 'Natalia', 'Fabiana', 'Salazar', 'Pinto', 'V-26781290', 'natalia.salazar@hotmail.com', '+58 424 1029485', NULL, '@natalia_skincare_pro', 1, '2026-01-24 15:30:00'),
(11, 'Oriana', 'Beatriz', 'Romero', 'Crespo', 'V-27543891', 'oriana.romero@gmail.com', '+58 412 2193840', '+58 251 2193840', '@oriana_lashes_barquisimeto', 1, '2026-02-01 09:10:00'),
(12, 'Stephany', 'Paola', 'Márquez', 'Ramos', 'V-28341902', 'stephany.marquez@gmail.com', '+58 416 3284910', NULL, '@stephany_lashes_art', 1, '2026-02-01 11:45:00'),
(13, 'Daniela', 'Virginia', 'Quintero', 'Rangel', 'V-26451982', 'daniela.quintero@gmail.com', '+58 414 4392018', '+58 251 4392018', '@daniela_quintero_lashes', 1, '2026-02-02 08:50:00'),
(14, 'Paola', 'Chiquinquirá', 'Benítez', 'Lugo', 'V-27982341', 'paola.benitez@outlook.com', '+58 424 5483920', NULL, '@paola_chiqui_beauty', 1, '2026-02-02 14:15:00'),
(15, 'Génesis', 'Nazareth', 'Blanco', 'Cordero', 'V-28654321', 'genesis.blanco@gmail.com', '+58 412 6574839', '+58 251 6574839', '@genesis_blanco_eyes', 1, '2026-02-03 10:20:00'),
(16, 'Adriana', 'Carolina', 'Suárez', 'Vielma', 'V-26892341', 'adriana.suarez@gmail.com', '+58 414 7685940', NULL, '@adriana_lashes_pro', 1, '2026-02-03 16:00:00'),
(17, 'Claudia', 'Patricia', 'Altuve', 'Rincón', 'V-27341892', 'claudia.altuve@gmail.com', '+58 424 8796051', '+58 251 8796051', '@claudia_altuve_brows', 1, '2026-02-04 09:30:00'),
(18, 'Michelle', 'Victoria', 'Chacón', 'Soto', 'V-28129034', 'michelle.chacon@gmail.com', '+58 412 9807162', NULL, '@michelle_nails_art', 1, '2026-02-05 11:00:00'),
(19, 'Fabiana', 'Antonella', 'Villegas', 'Pérez', 'V-26543189', 'fabiana.villegas@hotmail.com', '+58 416 1928374', '+58 251 1928374', '@fabiana_nails_vzla', 1, '2026-02-05 15:40:00'),
(20, 'Yoselin', 'Coromoto', 'Guanipa', 'Díaz', 'V-27819203', 'yoselin.guanipa@gmail.com', '+58 414 2039485', NULL, '@yoselin_nail_studio', 1, '2026-02-06 08:20:00'),
(21, 'Vanessa', 'Alejandra', 'León', 'Serrano', 'V-28492019', 'vanessa.leon@gmail.com', '+58 424 3140596', '+58 251 3140596', '@vanessa_leon_beauty', 1, '2026-02-06 13:50:00'),
(22, 'Kimberly', 'Dayana', 'Montilla', 'Ruiz', 'V-26239481', 'kimberly.montilla@gmail.com', '+58 412 4251607', NULL, '@kimberly_nails_pro', 1, '2026-02-07 10:10:00'),
(23, 'Carmen', 'Alicia', 'Briceño', 'Cardozo', 'V-27671829', 'carmen.briceno@yahoo.com', '+58 414 5362718', '+58 251 5362718', '@carmen_alicia_estilismo', 1, '2026-02-07 16:30:00'),
(24, 'Laura', 'Elena', 'Maldonado', 'Vivas', 'V-28019283', 'laura.maldonado@gmail.com', '+58 424 6473829', NULL, '@laura_brows_micro', 1, '2026-02-08 09:00:00'),
(25, 'Anabella', 'Sofía', 'Carrizo', 'Barreto', 'V-26918273', 'anabella.carrizo@gmail.com', '+58 412 7584930', '+58 251 7584930', '@anabella_microblading', 1, '2026-02-08 14:40:00'),
(26, 'Victoria', 'Eugenia', 'Palacios', 'Mora', 'V-27281930', 'victoria.palacios@outlook.com', '+58 416 8695041', NULL, '@victoria_brows_art', 1, '2026-02-09 10:50:00'),
(27, 'Gabriel', 'Alexander', 'Pineda', 'Gómez', 'V-26391029', 'gabriel.pineda@gmail.com', '+58 414 9706152', '+58 251 9706152', '@gabriel_barber_fade', 1, '2026-02-10 11:30:00'),
(28, 'Kevin', 'Eduardo', 'Colmenarez', 'Rivas', 'V-27481920', 'kevin.colmenarez@gmail.com', '+58 424 1817263', NULL, '@kevin_barber_style', 1, '2026-02-10 16:00:00'),
(29, 'José', 'Daniel', 'Morales', 'Bastidas', 'V-28190283', 'jose.morales@gmail.com', '+58 412 2928374', '+58 251 2928374', '@josedaniel_barber', 1, '2026-02-11 08:40:00'),
(30, 'Anthony', 'Miguel', 'Mendoza', 'Peña', 'V-26819203', 'anthony.mendoza@gmail.com', '+58 414 3039485', NULL, '@anthony_fades_master', 1, '2026-02-11 15:10:00'),
(31, 'Bárbara', 'Cristina', 'Aguilar', 'Flores', 'V-27591823', 'barbara.aguilar@gmail.com', '+58 424 4140596', '+58 251 4140596', '@barbara_aguilar_cejas', 1, '2026-02-12 09:25:00'),
(32, 'Nayelis', 'Milagros', 'Terán', 'Silva', 'V-28381920', 'nayelis.teran@gmail.com', '+58 412 5251607', NULL, '@nayelis_color_hair', 0, '2026-02-12 14:00:00');

-- ==============================================================================
-- 13. TABLA: usuarios (Cuentas de autenticación verificadas con password_verify)
-- ==============================================================================
-- Contraseñas hasheadas en BCRYPT:
-- Admin123*       -> $2y$10$o.Mx9gj2OqzWZvHWHkjBeOuDNIQFbzQnHOpkaeNy9KG1BTd73XOYq
-- Instructor123*  -> $2y$10$ZCxxpto.t0TIwxqz/37RYuvN3d9ZBGdjhDIxhDB/6WK4s.K/TYE3.
-- Estudiante123*  -> $2y$10$68e7MkYKF15PhaCPgu.mTOjVvosaPAQluuUDnK1HoTcaKnGxLwYdS

INSERT INTO usuarios (id_usuario, email_usuario, password_usuario, id_rol, id_instructor, id_estudiante, estatus_usuario, fecha_creacion) VALUES
-- Administradores del Sistema
(1, 'admin@beautyacademy.com', '$2y$10$o.Mx9gj2OqzWZvHWHkjBeOuDNIQFbzQnHOpkaeNy9KG1BTd73XOYq', 1, NULL, NULL, 1, '2026-01-01 08:00:00'),
(2, 'coordinacion@beautyacademy.com', '$2y$10$o.Mx9gj2OqzWZvHWHkjBeOuDNIQFbzQnHOpkaeNy9KG1BTd73XOYq', 1, NULL, NULL, 1, '2026-01-02 08:30:00'),

-- Cuentas de Instructores Vinculados
(3, 'daniela.sanchez@beautyacademy.com', '$2y$10$ZCxxpto.t0TIwxqz/37RYuvN3d9ZBGdjhDIxhDB/6WK4s.K/TYE3.', 2, 1, NULL, 1, '2026-01-10 10:00:00'),
(4, 'valeria.mendoza@beautyacademy.com', '$2y$10$ZCxxpto.t0TIwxqz/37RYuvN3d9ZBGdjhDIxhDB/6WK4s.K/TYE3.', 2, 2, NULL, 1, '2026-01-10 10:30:00'),
(5, 'mariana.gomez@beautyacademy.com', '$2y$10$ZCxxpto.t0TIwxqz/37RYuvN3d9ZBGdjhDIxhDB/6WK4s.K/TYE3.', 2, 3, NULL, 1, '2026-01-11 09:15:00'),
(6, 'carlos.silva@beautyacademy.com', '$2y$10$ZCxxpto.t0TIwxqz/37RYuvN3d9ZBGdjhDIxhDB/6WK4s.K/TYE3.', 2, 4, NULL, 1, '2026-01-11 11:00:00'),
(7, 'andreina.castillo@beautyacademy.com', '$2y$10$ZCxxpto.t0TIwxqz/37RYuvN3d9ZBGdjhDIxhDB/6WK4s.K/TYE3.', 2, 5, NULL, 1, '2026-01-12 08:45:00'),
(8, 'gabriela.fuentes@beautyacademy.com', '$2y$10$ZCxxpto.t0TIwxqz/37RYuvN3d9ZBGdjhDIxhDB/6WK4s.K/TYE3.', 2, 6, NULL, 1, '2026-01-12 14:20:00'),
(9, 'nelson.ramirez@beautyacademy.com', '$2y$10$ZCxxpto.t0TIwxqz/37RYuvN3d9ZBGdjhDIxhDB/6WK4s.K/TYE3.', 2, 7, NULL, 1, '2026-01-13 10:00:00'),
(10, 'patricia.rojas@beautyacademy.com', '$2y$10$ZCxxpto.t0TIwxqz/37RYuvN3d9ZBGdjhDIxhDB/6WK4s.K/TYE3.', 2, 8, NULL, 0, '2026-01-13 16:30:00'),

-- Cuentas de Estudiantes para Pruebas del Portal del Alumno
(11, 'camila.herrera@gmail.com', '$2y$10$68e7MkYKF15PhaCPgu.mTOjVvosaPAQluuUDnK1HoTcaKnGxLwYdS', 3, NULL, 1, 1, '2026-01-20 09:00:00'),
(12, 'sofia.morales@hotmail.com', '$2y$10$68e7MkYKF15PhaCPgu.mTOjVvosaPAQluuUDnK1HoTcaKnGxLwYdS', 3, NULL, 2, 1, '2026-01-20 09:30:00'),
(13, 'mariana.pena@gmail.com', '$2y$10$68e7MkYKF15PhaCPgu.mTOjVvosaPAQluuUDnK1HoTcaKnGxLwYdS', 3, NULL, 3, 1, '2026-01-21 10:15:00'),
(14, 'isabella.castro@gmail.com', '$2y$10$68e7MkYKF15PhaCPgu.mTOjVvosaPAQluuUDnK1HoTcaKnGxLwYdS', 3, NULL, 4, 1, '2026-01-21 11:00:00'),
(15, 'valentina.ortiz@outlook.com', '$2y$10$68e7MkYKF15PhaCPgu.mTOjVvosaPAQluuUDnK1HoTcaKnGxLwYdS', 3, NULL, 5, 1, '2026-01-22 08:30:00'),
(16, 'lucia.mendez@gmail.com', '$2y$10$68e7MkYKF15PhaCPgu.mTOjVvosaPAQluuUDnK1HoTcaKnGxLwYdS', 3, NULL, 6, 1, '2026-01-22 14:00:00'),
(17, 'valeria.navarro@yahoo.com', '$2y$10$68e7MkYKF15PhaCPgu.mTOjVvosaPAQluuUDnK1HoTcaKnGxLwYdS', 3, NULL, 7, 1, '2026-01-23 09:45:00'),
(18, 'maria.rivas@gmail.com', '$2y$10$68e7MkYKF15PhaCPgu.mTOjVvosaPAQluuUDnK1HoTcaKnGxLwYdS', 3, NULL, 8, 1, '2026-01-23 11:20:00'),
(19, 'andrea.torres@gmail.com', '$2y$10$68e7MkYKF15PhaCPgu.mTOjVvosaPAQluuUDnK1HoTcaKnGxLwYdS', 3, NULL, 9, 1, '2026-01-24 10:00:00'),
(20, 'natalia.salazar@hotmail.com', '$2y$10$68e7MkYKF15PhaCPgu.mTOjVvosaPAQluuUDnK1HoTcaKnGxLwYdS', 3, NULL, 10, 1, '2026-01-24 15:30:00'),
(21, 'gabriel.pineda@gmail.com', '$2y$10$68e7MkYKF15PhaCPgu.mTOjVvosaPAQluuUDnK1HoTcaKnGxLwYdS', 3, NULL, 27, 1, '2026-02-10 11:30:00'),
(22, 'kevin.colmenarez@gmail.com', '$2y$10$68e7MkYKF15PhaCPgu.mTOjVvosaPAQluuUDnK1HoTcaKnGxLwYdS', 3, NULL, 28, 1, '2026-02-10 16:00:00');

-- ==============================================================================
-- 14. TABLA: matriculas
-- (Cumple estrictamente los límites de aforo por curso del trigger trg_matricula_check_cupo)
-- ==============================================================================
INSERT INTO matriculas (id_matricula, id_estudiante, id_instructor_curso, fecha_solicitud, precio_acordado, estatus_pago, estatus_cupo, fecha_creacion) VALUES
-- Curso 1: Maquillaje Profesional (id_instructor_curso: 1, cupos: 15) -> 8 activos + 1 cancelado
(1, 1, 1, '2026-08-15 10:00:00', 140.00, 'Solvente', 'Confirmado', '2026-08-15 10:00:00'),
(2, 2, 1, '2026-08-16 11:30:00', 140.00, 'Solvente', 'Confirmado', '2026-08-16 11:30:00'),
(3, 3, 1, '2026-09-02 09:15:00', 180.00, 'Solvente', 'Confirmado', '2026-09-02 09:15:00'),
(4, 4, 1, '2026-09-03 14:20:00', 180.00, 'Solvente', 'Confirmado', '2026-09-03 14:20:00'),
(5, 5, 1, '2026-09-05 16:40:00', 180.00, 'Solvente', 'Confirmado', '2026-09-05 16:40:00'),
(6, 6, 1, '2026-09-10 08:30:00', 180.00, 'Deudora', 'Reservado', '2026-09-10 08:30:00'),
(7, 7, 1, '2026-09-11 15:00:00', 180.00, 'Deudora', 'Reservado', '2026-09-11 15:00:00'),
(8, 8, 1, '2026-09-12 11:10:00', 180.00, 'Deudora', 'Reservado', '2026-09-12 11:10:00'),
(9, 9, 1, '2026-09-13 17:00:00', 180.00, 'Deudora', 'Cancelado', '2026-09-13 17:00:00'),
(10, 10, 1, '2026-08-25 12:00:00', 140.00, 'Solvente', 'Confirmado', '2026-08-25 12:00:00'),

-- Curso 2: Extensiones de Pestañas (id_instructor_curso: 2, cupos: 12) -> 6 activos + 1 cancelado
(11, 11, 2, '2026-09-15 09:00:00', 60.00, 'Solvente', 'Confirmado', '2026-09-15 09:00:00'),
(12, 12, 2, '2026-09-16 10:30:00', 60.00, 'Solvente', 'Confirmado', '2026-09-16 10:30:00'),
(13, 13, 2, '2026-10-06 14:00:00', 85.00, 'Solvente', 'Confirmado', '2026-10-06 14:00:00'),
(14, 14, 2, '2026-10-07 16:20:00', 85.00, 'Solvente', 'Confirmado', '2026-10-07 16:20:00'),
(15, 15, 2, '2026-10-08 11:00:00', 85.00, 'Deudora', 'Reservado', '2026-10-08 11:00:00'),
(16, 16, 2, '2026-10-09 13:45:00', 85.00, 'Deudora', 'Reservado', '2026-10-09 13:45:00'),
(17, 17, 2, '2026-10-10 17:00:00', 85.00, 'Deudora', 'Cancelado', '2026-10-10 17:00:00'),

-- Curso 3: Manicura Rusa y Polygel (id_instructor_curso: 3, cupos: 10) -> 6 activos
(18, 18, 3, '2026-08-28 09:30:00', 95.00, 'Solvente', 'Confirmado', '2026-08-28 09:30:00'),
(19, 19, 3, '2026-08-29 15:20:00', 95.00, 'Solvente', 'Confirmado', '2026-08-29 15:20:00'),
(20, 20, 3, '2026-09-05 10:00:00', 120.00, 'Solvente', 'Confirmado', '2026-09-05 10:00:00'),
(21, 21, 3, '2026-09-06 14:40:00', 120.00, 'Solvente', 'Confirmado', '2026-09-06 14:40:00'),
(22, 22, 3, '2026-09-07 11:15:00', 120.00, 'Deudora', 'Reservado', '2026-09-07 11:15:00'),
(23, 23, 3, '2026-09-08 16:30:00', 120.00, 'Deudora', 'Reservado', '2026-09-08 16:30:00'),

-- Curso 4: Microblading (id_instructor_curso: 4, Preventa, cupos: 8) -> 4 activos
(24, 24, 4, '2026-10-03 10:00:00', 160.00, 'Solvente', 'Confirmado', '2026-10-03 10:00:00'),
(25, 25, 4, '2026-10-04 11:45:00', 160.00, 'Solvente', 'Confirmado', '2026-10-04 11:45:00'),
(26, 26, 4, '2026-10-05 14:30:00', 160.00, 'Deudora', 'Reservado', '2026-10-05 14:30:00'),
(27, 31, 4, '2026-10-06 16:10:00', 160.00, 'Deudora', 'Reservado', '2026-10-06 16:10:00'),

-- Curso 5: Colorimetría (id_instructor_curso: 5, Preventa, cupos: 15) -> 5 activos
(28, 32, 5, '2026-10-07 09:00:00', 50.00, 'Solvente', 'Confirmado', '2026-10-07 09:00:00'),
(29, 3, 5, '2026-10-08 10:30:00', 50.00, 'Solvente', 'Confirmado', '2026-10-08 10:30:00'),
(30, 8, 5, '2026-10-09 14:00:00', 50.00, 'Deudora', 'Reservado', '2026-10-09 14:00:00'),
(31, 13, 5, '2026-10-10 15:45:00', 50.00, 'Solvente', 'Confirmado', '2026-10-10 15:45:00'),
(32, 19, 5, '2026-10-11 11:20:00', 50.00, 'Deudora', 'Reservado', '2026-10-11 11:20:00'),

-- Curso 6: Cosmetología Facial (id_instructor_curso: 6, Cerrado - Graduados) -> 6 solventes
(33, 1, 6, '2026-01-25 09:00:00', 120.00, 'Solvente', 'Confirmado', '2026-01-25 09:00:00'),
(34, 2, 6, '2026-01-26 10:30:00', 120.00, 'Solvente', 'Confirmado', '2026-01-26 10:30:00'),
(35, 4, 6, '2026-02-02 11:15:00', 150.00, 'Solvente', 'Confirmado', '2026-02-02 11:15:00'),
(36, 10, 6, '2026-01-28 14:00:00', 120.00, 'Solvente', 'Confirmado', '2026-01-28 14:00:00'),
(37, 11, 6, '2026-02-03 15:30:00', 150.00, 'Solvente', 'Confirmado', '2026-02-03 15:30:00'),
(38, 14, 6, '2026-02-04 16:45:00', 150.00, 'Solvente', 'Confirmado', '2026-02-04 16:45:00'),

-- Curso 7: Barbería Integral (id_instructor_curso: 7, Cerrado - Graduados) -> 4 solventes
(39, 27, 7, '2026-02-20 10:00:00', 80.00, 'Solvente', 'Confirmado', '2026-02-20 10:00:00'),
(40, 28, 7, '2026-02-21 11:30:00', 80.00, 'Solvente', 'Confirmado', '2026-02-21 11:30:00'),
(41, 29, 7, '2026-03-02 14:00:00', 110.00, 'Solvente', 'Confirmado', '2026-03-02 14:00:00'),
(42, 30, 7, '2026-03-03 16:15:00', 110.00, 'Solvente', 'Confirmado', '2026-03-03 16:15:00');

-- ==============================================================================
-- 15. TABLA: comprobante_pagos
-- (Comprobantes Verificados, Pendientes de auditoría y Rechazados)
-- ==============================================================================
INSERT INTO comprobante_pagos (id_comprobante_pago, id_matricula, id_banco, referencia_transaccion, monto, url_img_comprobante, fecha_pago, estatus_pago, fecha_creacion) VALUES
-- Pagos Verificados para Curso 1 (Maquillaje)
(1, 1, 1, 'REF-BDV-8947291', 140.00, 'assets/uploads/comprobantes/pago_ref_8947291.jpg', '2026-08-15 10:30:00', 'Verificado', '2026-08-15 10:35:00'),
(2, 2, 2, 'REF-BAN-3847192', 140.00, 'assets/uploads/comprobantes/pago_ref_3847192.jpg', '2026-08-16 12:00:00', 'Verificado', '2026-08-16 12:05:00'),
(3, 3, 7, 'PAGOMOVIL-9102834', 180.00, 'assets/uploads/comprobantes/pago_ref_9102834.jpg', '2026-09-02 09:45:00', 'Verificado', '2026-09-02 09:50:00'),
(4, 4, 3, 'REF-MER-4920192', 180.00, 'assets/uploads/comprobantes/pago_ref_4920192.jpg', '2026-09-03 15:00:00', 'Verificado', '2026-09-03 15:05:00'),
(5, 5, 8, 'ZELLE-CONF-819203', 180.00, 'assets/uploads/comprobantes/pago_ref_819203.jpg', '2026-09-05 17:10:00', 'Verificado', '2026-09-05 17:15:00'),
(6, 10, 5, 'REF-BAM-1029384', 140.00, 'assets/uploads/comprobantes/pago_ref_1029384.jpg', '2026-08-25 12:30:00', 'Verificado', '2026-08-25 12:35:00'),

-- Pagos en Revisión (Pendientes) y Rechazados para Curso 1
(7, 7, 7, 'PAGOMOVIL-7748291', 180.00, 'assets/uploads/comprobantes/pago_ref_7748291.jpg', '2026-09-11 15:30:00', 'Pendiente', '2026-09-11 15:35:00'),
(8, 8, 1, 'REF-BDV-6619284', 180.00, 'assets/uploads/comprobantes/pago_ref_6619284.jpg', '2026-09-12 11:30:00', 'Pendiente', '2026-09-12 11:35:00'),
(9, 6, 2, 'REF-BAN-ERROR-01', 50.00, 'assets/uploads/comprobantes/pago_ref_error01.jpg', '2026-09-10 09:00:00', 'Rechazado', '2026-09-10 09:05:00'),

-- Pagos Verificados y Pendientes para Curso 2 (Pestañas)
(10, 11, 7, 'PAGOMOVIL-3329104', 60.00, 'assets/uploads/comprobantes/pago_ref_3329104.jpg', '2026-09-15 09:30:00', 'Verificado', '2026-09-15 09:35:00'),
(11, 12, 1, 'REF-BDV-5548291', 60.00, 'assets/uploads/comprobantes/pago_ref_5548291.jpg', '2026-09-16 11:00:00', 'Verificado', '2026-09-16 11:05:00'),
(12, 13, 8, 'ZELLE-CONF-920194', 85.00, 'assets/uploads/comprobantes/pago_ref_920194.jpg', '2026-10-06 14:30:00', 'Verificado', '2026-10-06 14:35:00'),
(13, 14, 9, 'BINANCE-TXID-88391', 85.00, 'assets/uploads/comprobantes/pago_ref_88391.jpg', '2026-10-07 16:50:00', 'Verificado', '2026-10-07 16:55:00'),
(14, 15, 2, 'REF-BAN-2948192', 85.00, 'assets/uploads/comprobantes/pago_ref_2948192.jpg', '2026-10-08 11:30:00', 'Pendiente', '2026-10-08 11:35:00'),
(15, 16, 7, 'PAGOMOVIL-8849102', 85.00, 'assets/uploads/comprobantes/pago_ref_8849102.jpg', '2026-10-09 14:00:00', 'Pendiente', '2026-10-09 14:05:00'),

-- Pagos Curso 3 (Uñas y Polygel)
(16, 18, 1, 'REF-BDV-1102934', 95.00, 'assets/uploads/comprobantes/pago_ref_1102934.jpg', '2026-08-28 10:00:00', 'Verificado', '2026-08-28 10:05:00'),
(17, 19, 7, 'PAGOMOVIL-4492019', 95.00, 'assets/uploads/comprobantes/pago_ref_4492019.jpg', '2026-08-29 16:00:00', 'Verificado', '2026-08-29 16:05:00'),
(18, 20, 2, 'REF-BAN-7738291', 120.00, 'assets/uploads/comprobantes/pago_ref_7738291.jpg', '2026-09-05 10:30:00', 'Verificado', '2026-09-05 10:35:00'),
(19, 21, 6, 'REF-BNC-5592810', 120.00, 'assets/uploads/comprobantes/pago_ref_5592810.jpg', '2026-09-06 15:00:00', 'Verificado', '2026-09-06 15:05:00'),
(20, 22, 7, 'PAGOMOVIL-9948271', 120.00, 'assets/uploads/comprobantes/pago_ref_9948271.jpg', '2026-09-07 11:45:00', 'Pendiente', '2026-09-07 11:50:00'),

-- Pagos Curso 4 (Microblading - Preventa)
(21, 24, 8, 'ZELLE-CONF-334910', 160.00, 'assets/uploads/comprobantes/pago_ref_334910.jpg', '2026-10-03 10:30:00', 'Verificado', '2026-10-03 10:35:00'),
(22, 25, 9, 'BINANCE-TXID-77291', 160.00, 'assets/uploads/comprobantes/pago_ref_77291.jpg', '2026-10-04 12:15:00', 'Verificado', '2026-10-04 12:20:00'),
(23, 26, 7, 'PAGOMOVIL-1182940', 160.00, 'assets/uploads/comprobantes/pago_ref_1182940.jpg', '2026-10-05 15:00:00', 'Pendiente', '2026-10-05 15:05:00'),
(24, 27, 1, 'REF-BDV-9938192', 160.00, 'assets/uploads/comprobantes/pago_ref_9938192.jpg', '2026-10-06 16:30:00', 'Pendiente', '2026-10-06 16:35:00'),

-- Pagos Curso 5 (Colorimetría - Preventa)
(25, 28, 7, 'PAGOMOVIL-6629103', 50.00, 'assets/uploads/comprobantes/pago_ref_6629103.jpg', '2026-10-07 09:30:00', 'Verificado', '2026-10-07 09:35:00'),
(26, 29, 2, 'REF-BAN-8849201', 50.00, 'assets/uploads/comprobantes/pago_ref_8849201.jpg', '2026-10-08 11:00:00', 'Verificado', '2026-10-08 11:05:00'),
(27, 30, 7, 'PAGOMOVIL-2293841', 50.00, 'assets/uploads/comprobantes/pago_ref_2293841.jpg', '2026-10-09 14:30:00', 'Pendiente', '2026-10-09 14:35:00'),
(28, 31, 8, 'ZELLE-CONF-662910', 50.00, 'assets/uploads/comprobantes/pago_ref_662910.jpg', '2026-10-10 16:15:00', 'Verificado', '2026-10-10 16:20:00'),

-- Pagos Históricos Verificados para Curso 6 (Cosmetología - Cerrado)
(29, 33, 1, 'REF-BDV-HIST-001', 120.00, 'assets/uploads/comprobantes/hist_001.jpg', '2026-01-25 09:30:00', 'Verificado', '2026-01-25 09:35:00'),
(30, 34, 2, 'REF-BAN-HIST-002', 120.00, 'assets/uploads/comprobantes/hist_002.jpg', '2026-01-26 11:00:00', 'Verificado', '2026-01-26 11:05:00'),
(31, 35, 7, 'PAGOMOVIL-HIST-003', 150.00, 'assets/uploads/comprobantes/hist_003.jpg', '2026-02-02 11:45:00', 'Verificado', '2026-02-02 11:50:00'),
(32, 36, 3, 'REF-MER-HIST-004', 120.00, 'assets/uploads/comprobantes/hist_004.jpg', '2026-01-28 14:30:00', 'Verificado', '2026-01-28 14:35:00'),
(33, 37, 5, 'REF-BAM-HIST-005', 150.00, 'assets/uploads/comprobantes/hist_005.jpg', '2026-02-03 16:00:00', 'Verificado', '2026-02-03 16:05:00'),
(34, 38, 8, 'ZELLE-HIST-006', 150.00, 'assets/uploads/comprobantes/hist_006.jpg', '2026-02-04 17:15:00', 'Verificado', '2026-02-04 17:20:00'),

-- Pagos Históricos Verificados para Curso 7 (Barbería - Cerrado)
(35, 39, 7, 'PAGOMOVIL-HIST-007', 80.00, 'assets/uploads/comprobantes/hist_007.jpg', '2026-02-20 10:30:00', 'Verificado', '2026-02-20 10:35:00'),
(36, 40, 1, 'REF-BDV-HIST-008', 80.00, 'assets/uploads/comprobantes/hist_008.jpg', '2026-02-21 12:00:00', 'Verificado', '2026-02-21 12:05:00'),
(37, 41, 2, 'REF-BAN-HIST-009', 110.00, 'assets/uploads/comprobantes/hist_009.jpg', '2026-03-02 14:30:00', 'Verificado', '2026-03-02 14:35:00'),
(38, 42, 6, 'REF-BNC-HIST-010', 110.00, 'assets/uploads/comprobantes/hist_010.jpg', '2026-03-03 16:45:00', 'Verificado', '2026-03-03 16:50:00');

-- ==============================================================================
-- 16. TABLA: evaluaciones
-- (Asignaciones teóricas y prácticas por instancia académica)
-- ==============================================================================
INSERT INTO evaluaciones (id_evaluacion, id_instructor_curso, titulo_evaluacion, descripcion_evaluacion, orden_evaluacion, tipo_evaluacion, estatus_evaluacion, fecha_creacion) VALUES
-- Evaluaciones para Curso 1: Maquillaje Profesional (id_instructor_curso = 1)
(1, 1, 'Práctica 1: Preparación del Lienzo Facial y Neutralización de Ojeras', 'Subir registro fotográfico en alta resolución del antes y después de la corrección cromática según el biotipo cutáneo del modelo.', 1, 'practica', 1, '2026-08-15 10:00:00'),
(2, 1, 'Evaluación Teórica: Armonía del Color, Visagismo y Círculo Cromático', 'Cuestionario teórico sobre mezclas de pigmentos, alturas de tono y compensación morfológica del rostro.', 2, 'teorica', 1, '2026-08-15 10:30:00'),
(3, 1, 'Práctica 2: Maquillaje Social Glam con Técnica de Cut Crease', 'Demostración de cuenca marcada, aplicación limpia de glitter prensado y pestañas postizas integradas.', 3, 'practica', 1, '2026-08-15 11:00:00'),
(4, 1, 'Evaluación Final: Look Nupcial Completo de Larga Duración', 'Proyecto integrador final en modelo real: visagismo, cejas, piel blindada resistente al agua y fijación profesional.', 4, 'practica', 1, '2026-08-15 11:30:00'),

-- Evaluaciones para Curso 2: Pestañas (id_instructor_curso = 2)
(5, 2, 'Práctica: Creación de Abanicos Simétricos 3D a 5D en Esponja', 'Envío de fotografía macro de 10 abanicos con base cerrada y apertura uniforme sin excesos de adhesivo.', 1, 'practica', 1, '2026-09-05 14:00:00'),
(6, 2, 'Evaluación Práctica en Modelo: Mapeo Whispy con Retención Óptima', 'Set completo de extensiones en modelo vivo aplicando el mapa ocular acordado según la morfología.', 2, 'practica', 1, '2026-09-05 14:30:00'),

-- Evaluaciones para Curso 3: Manicura Rusa y Polygel (id_instructor_curso = 3)
(7, 3, 'Práctica: Limpieza de Cutícula con Fresa Flama y Nivelación Rubber', 'Fotografía en primer plano del bolsillo de cutícula pulido y el reflejo perfecto del aro de luz circular.', 1, 'practica', 1, '2026-08-25 15:00:00'),
(8, 3, 'Proyecto Final: Estructura Escultural en Polygel con Tips Duales', 'Construcción de un set de 5 dedos en forma almendrada clásica con ápice estructural y esmaltado bajo cutícula.', 2, 'practica', 1, '2026-08-25 15:30:00'),

-- Evaluaciones para Curso 6: Cosmetología Facial (id_instructor_curso = 6 - Histórico)
(9, 6, 'Evaluación Teórica: Biotipos, Fototipos y Lesiones Cutáneas', 'Examen sobre la clasificación de Fitzpatrick, glándulas sebáceas, acné y patologías tratables en cabina cosmética.', 1, 'teorica', 1, '2026-01-20 09:00:00'),
(10, 6, 'Práctica Clínica: Protocolo de Limpieza Facial Profunda y Alta Frecuencia', 'Demostración presencial de asepsia, extracción segura con sacacomedón y aplicación descongestiva de ozono.', 2, 'practica', 1, '2026-01-20 09:30:00'),
(11, 6, 'Práctica Final: Aplicación Controlada de Peeling Químico', 'Ejecución del protocolo de exfoliación química con ácido mandélico, control de tiempos y neutralización perfecta.', 3, 'practica', 1, '2026-01-20 10:00:00'),

-- Evaluaciones para Curso 7: Barbería Integral (id_instructor_curso = 7 - Histórico)
(12, 7, 'Práctica 1: Desvanecido Skin Fade con Borrado de Línea Guía', 'Corte en modelo real ejecutando un desvanecido desde la cero limpia hasta la cúspide con máquina clipper.', 1, 'practica', 1, '2026-02-15 14:00:00'),
(13, 7, 'Evaluación Final: Perfilado de Barba y Ritual con Toallas Calientes', 'Afeitado tradicional a navaja, simetría de mejillas y cuello con vaporización aromaterapéutica.', 2, 'practica', 1, '2026-02-15 14:30:00');

-- ==============================================================================
-- 17. TABLA: entregas_evaluaciones
-- (Entregas aprobadas con feedback cálido, en revisión, para repetir y pendientes)
-- ==============================================================================
INSERT INTO entregas_evaluaciones (id_entrega_evaluacion, id_evaluacion, id_matricula, url_archivo_entrega, comentarios_estudiante, calificacion_entrega, feedback_instructora_entrega, fecha_entrega, estatus_entrega, fecha_creacion) VALUES
-- Curso 1: Entregas de Práctica 1 (Evaluación 1)
(1, 1, 1, 'assets/uploads/entregas/entrega_camila_eval1.pdf', 'Instructora Daniela, adjunto el paso a paso de la preparación con suero de ácido hialurónico y corrección naranja para las ojeras pigmentadas de mi modelo.', 95.00, '¡Excelente trabajo Camila! La piel luce luminosa, hidratada y sin textura acartonada. El difuminado del corrector complementario fue impecable.', '2026-09-25 14:30:00', 'Aprobada', '2026-09-25 14:30:00'),
(2, 1, 2, 'assets/uploads/entregas/entrega_sofia_eval1.pdf', 'Profe, apliqué primer matificante en la zona T y base ligera. Adjunto fotos con luz natural y con aro de luz.', 90.00, 'Muy buen manejo de la piel mixta Sofía. Cuida sellar muy suavemente con la borla en las aletas nasales para evitar que se acumule producto.', '2026-09-26 11:15:00', 'Aprobada', '2026-09-26 11:15:00'),
(3, 1, 3, 'assets/uploads/entregas/entrega_mariana_eval1.pdf', 'Adjunto mi registro de corrección de manchas de sol y rojeces con corrector verde.', 88.00, 'Buen trabajo neutralizando las rojeces Mariana. Para la próxima sesión, aplica una capa más fina de base para que el acabado sea aún más natural.', '2026-09-27 16:40:00', 'Aprobada', '2026-09-27 16:40:00'),
(4, 1, 4, 'assets/uploads/entregas/entrega_isabella_eval1.pdf', 'Subiendo mi entrega de preparación de piel seca madura.', 92.00, 'Hermosa jugosidad lograda en piel madura Isabella. La elección de bases satinadas fue muy acertada.', '2026-09-28 10:20:00', 'Aprobada', '2026-09-28 10:20:00'),
(5, 1, 5, 'assets/uploads/entregas/entrega_valentina_eval1.pdf', 'Buenas tardes, adjunto fotos del antes, corrección y resultado final.', NULL, NULL, '2026-10-14 18:00:00', 'En Revisión', '2026-10-14 18:00:00'),
(6, 1, 10, 'assets/uploads/entregas/entrega_natalia_eval1.pdf', 'Envío de práctica 1 con piel con tendencia acnéica.', 55.00, 'Natalia, el tono del corrector naranja quedó muy visible por debajo de la base. Debes difuminar mejor los bordes antes de asentar el pigmento. Por favor realiza una nueva prueba.', '2026-09-29 15:00:00', 'Repetir', '2026-09-29 15:00:00'),

-- Curso 1: Entregas de Quiz Teórico (Evaluación 2)
(7, 2, 1, 'assets/uploads/entregas/quiz_camila.pdf', 'Respuestas al examen de círculo cromático y proporciones.', 98.00, '¡Puntaje casi perfecto Camila! Dominas muy bien los conceptos de armonía análoga y complementaria.', '2026-10-02 09:30:00', 'Aprobada', '2026-10-02 09:30:00'),
(8, 2, 2, 'assets/uploads/entregas/quiz_sofia.pdf', 'Adjunto cuestionario resuelto.', 85.00, 'Respuestas muy acertadas. Repasa únicamente la diferencia entre tinte, tono y matiz.', '2026-10-03 14:00:00', 'Aprobada', '2026-10-03 14:00:00'),

-- Curso 2: Entregas de Pestañas (Evaluación 5)
(9, 5, 11, 'assets/uploads/entregas/abanicos_oriana.jpg', 'Buenas tardes Instructora Valeria, envío foto macro de mis abanicos 4D con fibra 0.07.', 92.00, '¡Bases súper compactas Oriana! La apertura del ramo es muy equilibrada. Estás lista para modelo real.', '2026-10-12 11:00:00', 'Aprobada', '2026-10-12 11:00:00'),
(10, 5, 12, 'assets/uploads/entregas/abanicos_stephany.jpg', 'Adjunto mi práctica de abanicos en esponja.', NULL, NULL, '2026-10-15 16:30:00', 'En Revisión', '2026-10-15 16:30:00'),

-- Curso 3: Entregas de Uñas (Evaluación 7)
(11, 7, 18, 'assets/uploads/entregas/cuticula_michelle.jpg', 'Profe Mariana, foto del corte de cutícula y reflejo con base rubber.', 94.00, '¡Limpieza impecable Michelle! El eponiquio no presenta rojeces ni cortes y el blique de luz es completamente redondo.', '2026-09-30 15:00:00', 'Aprobada', '2026-09-30 15:00:00'),
(12, 7, 19, 'assets/uploads/entregas/cuticula_fabiana.jpg', 'Adjunto práctica de manicura rusa combinada.', NULL, NULL, '2026-10-16 10:20:00', 'Pendiente', '2026-10-16 10:20:00'),

-- Curso 6: Entregas Completas y Aprobadas para Alumnas Graduadas (id_matricula 33 a 38)
-- Alumna 1: Camila Herrera (Matrícula 33)
(13, 9, 33, 'assets/uploads/entregas/hist_camila_eval9.pdf', 'Examen teórico entregado.', 96.00, 'Sobresaliente dominio de la histología cutánea.', '2026-03-01 10:00:00', 'Aprobada', '2026-03-01 10:00:00'),
(14, 10, 33, 'assets/uploads/entregas/hist_camila_eval10.pdf', 'Demostración de limpieza facial y ozono.', 98.00, 'Maniobras y esterilización de diez.', '2026-04-15 11:00:00', 'Aprobada', '2026-04-15 11:00:00'),
(15, 11, 33, 'assets/uploads/entregas/hist_camila_eval11.pdf', 'Caso clínico de peeling mandélico.', 95.00, 'Monitoreo de eritema impecable.', '2026-05-30 12:00:00', 'Aprobada', '2026-05-30 12:00:00'),

-- Alumna 2: Sofía Morales (Matrícula 34)
(16, 9, 34, 'assets/uploads/entregas/hist_sofia_eval9.pdf', 'Examen teórico.', 90.00, 'Respuestas muy fundamentadas.', '2026-03-01 10:30:00', 'Aprobada', '2026-03-01 10:30:00'),
(17, 10, 34, 'assets/uploads/entregas/hist_sofia_eval10.pdf', 'Práctica de alta frecuencia.', 92.00, 'Excelente manipulación de electrodos de vidrio.', '2026-04-15 11:30:00', 'Aprobada', '2026-04-15 11:30:00'),
(18, 11, 34, 'assets/uploads/entregas/hist_sofia_eval11.pdf', 'Práctica de peeling químico.', 91.00, 'Muy buen protocolo y cuidados post.', '2026-05-30 12:30:00', 'Aprobada', '2026-05-30 12:30:00'),

-- Alumna 3: Isabella Castro (Matrícula 35)
(19, 9, 35, 'assets/uploads/entregas/hist_isabella_eval9.pdf', 'Examen de biotipos.', 94.00, 'Excelente análisis diagnóstico.', '2026-03-02 09:00:00', 'Aprobada', '2026-03-02 09:00:00'),
(20, 10, 35, 'assets/uploads/entregas/hist_isabella_eval10.pdf', 'Práctica clínica de extracción.', 93.00, 'Técnica suave y no invasiva.', '2026-04-16 10:00:00', 'Aprobada', '2026-04-16 10:00:00'),
(21, 11, 35, 'assets/uploads/entregas/hist_isabella_eval11.pdf', 'Práctica de peeling glicólico.', 95.00, 'Precisión en los tiempos de exposición.', '2026-05-31 11:00:00', 'Aprobada', '2026-05-31 11:00:00'),

-- Alumna 4: Natalia Salazar (Matrícula 36)
(22, 9, 36, 'assets/uploads/entregas/hist_natalia_eval9.pdf', 'Examen teórico entregado.', 88.00, 'Aprobada con solvencia técnica.', '2026-03-02 11:00:00', 'Aprobada', '2026-03-02 11:00:00'),
(23, 10, 36, 'assets/uploads/entregas/hist_natalia_eval10.pdf', 'Práctica clínica de extracción.', 90.00, 'Buen uso del ablandador de comedones.', '2026-04-16 12:00:00', 'Aprobada', '2026-04-16 12:00:00'),
(24, 11, 36, 'assets/uploads/entregas/hist_natalia_eval11.pdf', 'Práctica de peeling mandélico.', 89.00, 'Neutralización rápida y segura.', '2026-05-31 13:00:00', 'Aprobada', '2026-05-31 13:00:00'),

-- Alumna 5: Oriana Romero (Matrícula 37)
(25, 9, 37, 'assets/uploads/entregas/hist_oriana_eval9.pdf', 'Teórico entregado.', 92.00, 'Muy buenos conceptos claros.', '2026-03-03 09:30:00', 'Aprobada', '2026-03-03 09:30:00'),
(26, 10, 37, 'assets/uploads/entregas/hist_oriana_eval10.pdf', 'Práctica de limpieza con espátula.', 94.00, 'Excelente técnica de peeling ultrasónico.', '2026-04-17 10:30:00', 'Aprobada', '2026-04-17 10:30:00'),
(27, 11, 37, 'assets/uploads/entregas/hist_oriana_eval11.pdf', 'Práctica de peeling químico.', 90.00, 'Buen criterio clínico.', '2026-06-01 11:30:00', 'Aprobada', '2026-06-01 11:30:00'),

-- Alumna 6: Paola Benítez (Matrícula 38)
(28, 9, 38, 'assets/uploads/entregas/hist_paola_eval9.pdf', 'Examen teórico entregado.', 95.00, 'Respuestas impecables.', '2026-03-03 11:30:00', 'Aprobada', '2026-03-03 11:30:00'),
(29, 10, 38, 'assets/uploads/entregas/hist_paola_eval10.pdf', 'Práctica de aparatología facial.', 97.00, 'Dominio de la máscara LED y activos.', '2026-04-17 12:30:00', 'Aprobada', '2026-04-17 12:30:00'),
(30, 11, 38, 'assets/uploads/entregas/hist_paola_eval11.pdf', 'Práctica de peeling químico.', 96.00, 'Excelente manejo de la paciente.', '2026-06-01 14:00:00', 'Aprobada', '2026-06-01 14:00:00'),

-- Curso 7: Entregas Completas y Aprobadas para Alumnos de Barbería (id_matricula 39 a 42)
-- Gabriel Pineda (Matrícula 39)
(31, 12, 39, 'assets/uploads/entregas/barber_gabriel_eval12.pdf', 'Skin fade con navaja en modelo.', 95.00, 'Línea de la cero completamente borrada. Gran precisión con la trimmer.', '2026-05-10 15:00:00', 'Aprobada', '2026-05-10 15:00:00'),
(32, 13, 39, 'assets/uploads/entregas/barber_gabriel_eval13.pdf', 'Perfilado de barba y toallas calientes.', 98.00, 'Líneas rectas y simetría facial de alta barbería.', '2026-06-25 16:00:00', 'Aprobada', '2026-06-25 16:00:00'),

-- Kevin Colmenarez (Matrícula 40)
(33, 12, 40, 'assets/uploads/entregas/barber_kevin_eval12.pdf', 'Mid fade con peines 1 y 2.', 90.00, 'Buena sombra y transición uniforme.', '2026-05-10 15:30:00', 'Aprobada', '2026-05-10 15:30:00'),
(34, 13, 40, 'assets/uploads/entregas/barber_kevin_eval13.pdf', 'Afeitado y toalla caliente.', 92.00, 'Buen estiramiento de piel para evitar cortes.', '2026-06-25 16:30:00', 'Aprobada', '2026-06-25 16:30:00'),

-- José Daniel Morales (Matrícula 41)
(35, 12, 41, 'assets/uploads/entregas/barber_jose_eval12.pdf', 'High fade limpio.', 88.00, 'Corte muy limpio, cuida no subir demasiado la guía.', '2026-05-11 10:00:00', 'Aprobada', '2026-05-11 10:00:00'),
(36, 13, 41, 'assets/uploads/entregas/barber_jose_eval13.pdf', 'Diseño de barba y bigote.', 89.00, 'Excelente simetría en mejillas.', '2026-06-26 11:00:00', 'Aprobada', '2026-06-26 11:00:00'),

-- Anthony Mendoza (Matrícula 42)
(37, 12, 42, 'assets/uploads/entregas/barber_anthony_eval12.pdf', 'Taper fade contemporáneo.', 94.00, 'Excelente caída en patilla y nuca.', '2026-05-11 11:30:00', 'Aprobada', '2026-05-11 11:30:00'),
(38, 13, 42, 'assets/uploads/entregas/barber_anthony_eval13.pdf', 'Ritual de barba completo.', 96.00, 'Manejo impecable del navajín.', '2026-06-26 12:30:00', 'Aprobada', '2026-06-26 12:30:00');

-- ==============================================================================
-- 18. TABLA: certificados
-- (Certificados emitidos para egresados y pendientes de emisión protocolar)
-- ==============================================================================
INSERT INTO certificados (id_certificado, id_matricula, titulo_certificado, descripcion_certificado, categoria_certificado, url_pdf_certificado, estatus_certificado, fecha_creacion) VALUES
-- Certificados de Cosmetología Facial (Curso 6)
(1, 33, 'Certificado de Aprobación - Diplomado en Cosmetología Facial y Tratamientos Antiage', 'Por haber cursado y aprobado satisfactoriamente todas las exigencias teóricas y prácticas clínicas con un promedio sobresaliente de 96.33 pts.', 'Cosmetología', 'assets/uploads/certificados/cert_cosmetologia_camila_herrera.pdf', 'Emitido', '2026-06-16 10:00:00'),
(2, 34, 'Certificado de Aprobación - Diplomado en Cosmetología Facial y Tratamientos Antiage', 'Por haber completado con éxito la carga académica y práctica de aparatología y peelings con promedio de 91.00 pts.', 'Cosmetología', 'assets/uploads/certificados/cert_cosmetologia_sofia_morales.pdf', 'Emitido', '2026-06-16 10:15:00'),
(3, 35, 'Certificado de Aprobación - Diplomado en Cosmetología Facial y Tratamientos Antiage', 'Por haber demostrado excelencia técnica y criterio cosmiátrico superior con promedio de 94.00 pts.', 'Cosmetología', 'assets/uploads/certificados/cert_cosmetologia_isabella_castro.pdf', 'Emitido', '2026-06-16 10:30:00'),
(4, 36, 'Certificado de Aprobación - Diplomado en Cosmetología Facial y Tratamientos Antiage', 'Por su dedicación y cumplimiento cabal del programa de estética facial con promedio de 89.00 pts.', 'Cosmetología', 'assets/uploads/certificados/cert_cosmetologia_natalia_salazar.pdf', 'Emitido', '2026-06-16 10:45:00'),
(5, 37, 'Certificado de Aprobación - Diplomado en Cosmetología Facial y Tratamientos Antiage', 'Por aprobar satisfactoriamente el ciclo académico y las evaluaciones clínicas con promedio de 92.00 pts.', 'Cosmetología', 'assets/uploads/certificados/cert_cosmetologia_oriana_romero.pdf', 'Emitido', '2026-06-16 11:00:00'),
(6, 38, 'Certificado de Aprobación - Diplomado en Cosmetología Facial y Tratamientos Antiage', 'Por su destacada labor práctica en cabina estética y formulación química con promedio de 96.00 pts.', 'Cosmetología', 'assets/uploads/certificados/cert_cosmetologia_paola_benitez.pdf', 'Pendiente de Emisión', '2026-06-16 11:15:00'),

-- Certificados de Barbería Integral (Curso 7)
(7, 39, 'Certificado de Aprobación - Curso de Barbería Integral y Técnicas de Degradado', 'Por demostrar maestría en desvanecidos a navaja limpia, visagismo masculino y afeitado tradicional con promedio de 96.50 pts.', 'Barbería', 'assets/uploads/certificados/cert_barberia_gabriel_pineda.pdf', 'Emitido', '2026-07-16 14:00:00'),
(8, 40, 'Certificado de Aprobación - Curso de Barbería Integral y Técnicas de Degradado', 'Por haber superado con solvencia las prácticas de corte clásico y moderno con promedio de 91.00 pts.', 'Barbería', 'assets/uploads/certificados/cert_barberia_kevin_colmenarez.pdf', 'Emitido', '2026-07-16 14:30:00'),
(9, 41, 'Certificado de Aprobación - Curso de Barbería Integral y Técnicas de Degradado', 'Por culminar satisfactoriamente el programa técnico de degradados y arreglo de barba con promedio de 88.50 pts.', 'Barbería', 'assets/uploads/certificados/cert_barberia_jose_morales.pdf', 'Emitido', '2026-07-16 15:00:00'),
(10, 42, 'Certificado de Aprobación - Curso de Barbería Integral y Técnicas de Degradado', 'Por su pulcritud, higiene y técnica destacada en cortes contemporáneos con promedio de 95.00 pts.', 'Barbería', 'assets/uploads/certificados/cert_barberia_anthony_mendoza.pdf', 'Pendiente de Emisión', '2026-07-16 15:30:00');

SET FOREIGN_KEY_CHECKS = 1;

-- ==============================================================================
-- FIN DEL SCRIPT DE DATOS DE PRUEBA
-- ==============================================================================
