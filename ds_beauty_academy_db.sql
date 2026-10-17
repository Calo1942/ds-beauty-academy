-- ==========================================
-- DS Beauty Academy - Modelo Fisico - Version 8.0 (Corregida) - 11/10/2026
-- ==========================================

DROP DATABASE IF EXISTS ds_beauty_academy_db;
CREATE DATABASE ds_beauty_academy_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE ds_beauty_academy_db;

-- ==========================================
-- TABLAS
-- ==========================================

CREATE TABLE roles (
    id_rol INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nombre_rol VARCHAR(100) NOT NULL UNIQUE,
    descripcion_rol TEXT,
    estatus_rol BOOLEAN DEFAULT TRUE,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE instructores (
    id_instructor INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    primer_nombre_instructor VARCHAR(100) NOT NULL,
    segundo_nombre_instructor VARCHAR(100),
    primer_apellido_instructor VARCHAR(100) NOT NULL,
    segundo_apellido_instructor VARCHAR(100),
    cedula_instructor VARCHAR(50) NOT NULL UNIQUE,
    correo_instructor VARCHAR(150) NOT NULL UNIQUE,
    telefono_principal_instructor VARCHAR(50) NOT NULL,
    telefono_alternativo_instructor VARCHAR(50),
    usuario_instagram_instructor VARCHAR(100) UNIQUE,
    estatus_instructor BOOLEAN DEFAULT TRUE,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE estudiantes (
    id_estudiante INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    primer_nombre_estudiante VARCHAR(100) NOT NULL,
    segundo_nombre_estudiante VARCHAR(100),
    primer_apellido_estudiante VARCHAR(100) NOT NULL,
    segundo_apellido_estudiante VARCHAR(100),
    cedula_estudiante VARCHAR(50) NOT NULL UNIQUE,
    correo_estudiante VARCHAR(150) NOT NULL UNIQUE,
    telefono_principal_estudiante VARCHAR(50) NOT NULL, -- Corregido: sin UNIQUE para permitir familiares/representantes
    telefono_alternativo_estudiante VARCHAR(50),
    usuario_instagram_estudiante VARCHAR(100) UNIQUE,
    estatus_estudiante BOOLEAN DEFAULT TRUE,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE usuarios (
    id_usuario INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    email_usuario VARCHAR(150) NOT NULL UNIQUE,
    password_usuario VARCHAR(255) NOT NULL,
    id_rol INT NOT NULL,
    id_instructor INT NULL UNIQUE, -- Vinculación opcional con perfil de instructor
    id_estudiante INT NULL UNIQUE, -- Vinculación opcional con perfil de estudiante
    estatus_usuario BOOLEAN DEFAULT TRUE,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_rol) REFERENCES roles(id_rol) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (id_instructor) REFERENCES instructores(id_instructor) ON DELETE SET NULL ON UPDATE CASCADE,
    FOREIGN KEY (id_estudiante) REFERENCES estudiantes(id_estudiante) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE especialidades (
    id_especialidad INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nombre_especialidad VARCHAR(150) NOT NULL UNIQUE,
    estatus_especialidad BOOLEAN DEFAULT TRUE,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE especialidad_instructor (
    id_instructor INT NOT NULL,
    id_especialidad INT NOT NULL,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id_instructor, id_especialidad),
    FOREIGN KEY (id_instructor) REFERENCES instructores(id_instructor) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (id_especialidad) REFERENCES especialidades(id_especialidad) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE diplomas (
    id_diploma INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nombre_diploma VARCHAR(255) NOT NULL,
    descripcion_diploma TEXT,
    categoria_diploma VARCHAR(100) NOT NULL,
    url_pdf_diploma VARCHAR(512),
    id_instructor INT NOT NULL,
    estatus_diploma BOOLEAN DEFAULT TRUE,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_instructor) REFERENCES instructores(id_instructor) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE cursos (
    id_curso INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nombre_curso VARCHAR(150) NOT NULL,
    descripcion_curso TEXT,
    tipo_curso ENUM('Masterclass','Curso completo') NOT NULL,
    precio_preventa DECIMAL(10,2),
    precio_normal DECIMAL(10,2) NOT NULL,
    fecha_fin_preventa DATETIME,
    fecha_inicio_curso DATETIME NOT NULL,
    fecha_fin_curso DATETIME,
    limite_cupos INT NOT NULL,
    estatus_curso ENUM('Borrador','Preventa','Venta Normal','Cerrado') DEFAULT 'Borrador',
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CHECK (precio_normal >= 0),
    CHECK (precio_preventa IS NULL OR (precio_preventa >= 0 AND precio_preventa < precio_normal)),
    CHECK (fecha_fin_preventa IS NULL OR fecha_fin_preventa < fecha_inicio_curso),
    CHECK (fecha_fin_curso IS NULL OR fecha_fin_curso >= fecha_inicio_curso),
    CHECK (limite_cupos > 0)
) ENGINE=InnoDB;

CREATE TABLE instructor_curso (
    id_instructor_curso INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    id_instructor INT NOT NULL,
    id_curso INT NOT NULL,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_instructor_curso (id_instructor, id_curso),
    FOREIGN KEY (id_instructor) REFERENCES instructores(id_instructor) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (id_curso) REFERENCES cursos(id_curso) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE clases (
    id_clase INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    titulo_clase VARCHAR(150) NOT NULL,
    descripcion_clase TEXT,
    url_video_clase VARCHAR(512) NOT NULL,
    orden_clase INT NOT NULL,
    estatus_clase BOOLEAN DEFAULT TRUE,
    id_instructor_curso INT NOT NULL,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_orden_clase (id_instructor_curso, orden_clase),
    FOREIGN KEY (id_instructor_curso) REFERENCES instructor_curso(id_instructor_curso) ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX idx_orden (orden_clase)
) ENGINE=InnoDB;

CREATE TABLE recursos (
    id_recurso INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nombre_recurso VARCHAR(150) NOT NULL, -- Corregido: sin UNIQUE global para permitir nombres comunes en varios cursos
    descripcion_recurso TEXT,
    url_archivo_recurso VARCHAR(512) NOT NULL,
    estatus_recurso BOOLEAN DEFAULT TRUE,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE recursos_curso (
    id_curso INT NOT NULL,
    id_recurso INT NOT NULL,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id_curso, id_recurso),
    FOREIGN KEY (id_curso) REFERENCES cursos(id_curso) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (id_recurso) REFERENCES recursos(id_recurso) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE matriculas (
    id_matricula INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    id_estudiante INT NOT NULL,
    id_instructor_curso INT NOT NULL,
    id_instructor_curso INT NOT NULL,
    fecha_solicitud DATETIME DEFAULT CURRENT_TIMESTAMP,
    precio_acordado DECIMAL(10,2) NOT NULL,
    estatus_pago ENUM('Deudora','Solvente') DEFAULT 'Deudora',
    estatus_cupo ENUM('Reservado','Confirmado','Cancelado') DEFAULT 'Reservado',
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_estudiante_instructor_curso (id_estudiante, id_instructor_curso),
    UNIQUE KEY unique_estudiante_instructor_curso (id_estudiante, id_instructor_curso),
    FOREIGN KEY (id_estudiante) REFERENCES estudiantes(id_estudiante) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (id_instructor_curso) REFERENCES instructor_curso(id_instructor_curso) ON DELETE RESTRICT ON UPDATE CASCADE,
    INDEX idx_instructor_curso (id_instructor_curso),
    INDEX idx_cupo (estatus_cupo),
    CHECK (precio_acordado >= 0)
) ENGINE=InnoDB;

CREATE TABLE banco (
    id_banco INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nombre_banco VARCHAR(150) NOT NULL UNIQUE,
    estatus_banco BOOLEAN DEFAULT TRUE,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE comprobante_pagos (
    id_comprobante_pago INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    id_matricula INT NOT NULL,
    id_banco INT NOT NULL,
    referencia_transaccion VARCHAR(150) NOT NULL UNIQUE,
    monto DECIMAL(10,2) NOT NULL,
    url_img_comprobante VARCHAR(512),
    fecha_pago DATETIME NOT NULL,
    estatus_pago ENUM('Pendiente','Verificado','Rechazado') DEFAULT 'Pendiente',
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_matricula) REFERENCES matriculas(id_matricula) ON DELETE RESTRICT ON UPDATE CASCADE, -- Corregido: RESTRICT protege auditoría contable
    FOREIGN KEY (id_banco) REFERENCES banco(id_banco) ON DELETE RESTRICT ON UPDATE CASCADE,
    INDEX idx_inscripcion (id_matricula),
    INDEX idx_estatus_pago (estatus_pago),
    CHECK (monto > 0)
) ENGINE=InnoDB;

-- Evaluaciones asociadas al curso impartido (alineado con la arquitectura MVC)
CREATE TABLE evaluaciones (
    id_evaluacion INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    id_instructor_curso INT NOT NULL, -- Corregido: Pertenece a la instancia de curso impartido
    titulo_evaluacion VARCHAR(150) NOT NULL,
    descripcion_evaluacion TEXT,
    orden_evaluacion INT NOT NULL,
    tipo_evaluacion ENUM('practica','teorica') DEFAULT 'practica',
    estatus_evaluacion BOOLEAN DEFAULT TRUE,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_orden_evaluacion (id_instructor_curso, orden_evaluacion),
    FOREIGN KEY (id_instructor_curso) REFERENCES instructor_curso(id_instructor_curso) ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX idx_orden (orden_evaluacion)
) ENGINE=InnoDB;

-- Entregas realizadas por cada estudiante matriculado para cada evaluación
CREATE TABLE entregas_evaluaciones (
    id_entrega_evaluacion INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    id_evaluacion INT NOT NULL,
    id_matricula INT NOT NULL, -- Corregido: Vinculación de entrega con la matrícula del alumno
    url_archivo_entrega VARCHAR(512),
    comentarios_estudiante TEXT,
    calificacion_entrega DECIMAL(5,2),
    feedback_instructora_entrega TEXT,
    fecha_entrega DATETIME DEFAULT CURRENT_TIMESTAMP,
    estatus_entrega ENUM('Pendiente','En Revisión','Repetir','Aprobada') DEFAULT 'Pendiente',
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_evaluacion_matricula (id_evaluacion, id_matricula), -- 1 entrega final por alumno/evaluación
    FOREIGN KEY (id_evaluacion) REFERENCES evaluaciones(id_evaluacion) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (id_matricula) REFERENCES matriculas(id_matricula) ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX idx_matricula (id_matricula),
    INDEX idx_estatus_entrega (estatus_entrega)
) ENGINE=InnoDB;

CREATE TABLE certificados (
    id_certificado INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    id_matricula INT NOT NULL UNIQUE,
    titulo_certificado VARCHAR(255) NOT NULL,
    descripcion_certificado TEXT,
    categoria_certificado VARCHAR(100) NOT NULL,
    url_pdf_certificado VARCHAR(512),
    estatus_certificado ENUM('Pendiente de Emisión','Emitido','Anulado') DEFAULT 'Pendiente de Emisión',
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_matricula) REFERENCES matriculas(id_matricula) ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX idx_estatus_cert (estatus_certificado)
) ENGINE=InnoDB;

-- ==========================================
-- TRIGGERS - DISPARADORES ACTUALIZADOS Y BLINDADOS
-- ==========================================

DELIMITER //

-- 1. Control de cupo e impedimento de doble matriculación en INSERT
CREATE TRIGGER trg_matricula_check_cupo
BEFORE INSERT ON matriculas
FOR EACH ROW
BEGIN
    DECLARE cupos_usados INT;
    DECLARE limite INT;
    DECLARE v_id_curso INT;
    DECLARE ya_matriculado INT;
    
    -- Solo verificar aforo y duplicidad si la matrícula se crea como Reservada o Confirmada
    IF NEW.estatus_cupo IN ('Reservado', 'Confirmado') THEN
        
        SELECT id_curso INTO v_id_curso 
        FROM instructor_curso 
        WHERE id_instructor_curso = NEW.id_instructor_curso;
        
        -- Blindaje contra doble matriculación del mismo estudiante en el mismo curso (incluso con otro instructor)
        SELECT COUNT(*) INTO ya_matriculado
        FROM matriculas m
        JOIN instructor_curso ic ON m.id_instructor_curso = ic.id_instructor_curso
        WHERE ic.id_curso = v_id_curso
          AND m.id_estudiante = NEW.id_estudiante
          AND m.estatus_cupo IN ('Reservado', 'Confirmado');
        
        IF ya_matriculado > 0 THEN
            SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'El estudiante ya se encuentra matriculado o con cupo activo en este curso';
        END IF;

        -- Validar aforo límite del curso
        SELECT limite_cupos INTO limite 
        FROM cursos 
        WHERE id_curso = v_id_curso;
        
        SELECT COUNT(*) INTO cupos_usados
        FROM matriculas m
        JOIN instructor_curso ic ON m.id_instructor_curso = ic.id_instructor_curso
        WHERE ic.id_curso = v_id_curso
          AND m.estatus_cupo IN ('Reservado', 'Confirmado');
        
        IF cupos_usados >= limite THEN
            SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'No hay cupos disponibles para este curso';
        END IF;
    END IF;
END//

-- 2. Control de cupo en UPDATE (Blindaje en reactivaciones y confirmaciones)
CREATE TRIGGER trg_matricula_update_cupo
BEFORE UPDATE ON matriculas
FOR EACH ROW
BEGIN
    DECLARE cupos_confirmados INT;
    DECLARE cupos_usados INT;
    DECLARE limite INT;
    DECLARE v_id_curso INT;
    DECLARE ya_matriculado INT;
    
    SELECT id_curso INTO v_id_curso 
    FROM instructor_curso 
    WHERE id_instructor_curso = NEW.id_instructor_curso;
    
    SELECT limite_cupos INTO limite 
    FROM cursos 
    WHERE id_curso = v_id_curso;
    
    -- Escenario A: Si se reactiva una matrícula que estaba 'Cancelado' hacia 'Reservado' o 'Confirmado'
    IF NEW.estatus_cupo IN ('Reservado', 'Confirmado') AND OLD.estatus_cupo = 'Cancelado' THEN
        
        -- Evitar duplicados activos en el mismo curso
        SELECT COUNT(*) INTO ya_matriculado
        FROM matriculas m
        JOIN instructor_curso ic ON m.id_instructor_curso = ic.id_instructor_curso
        WHERE ic.id_curso = v_id_curso
          AND m.id_estudiante = NEW.id_estudiante
          AND m.id_matricula != NEW.id_matricula
          AND m.estatus_cupo IN ('Reservado', 'Confirmado');
        
        IF ya_matriculado > 0 THEN
            SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'El estudiante ya tiene otra matricula activa en este curso';
        END IF;

        -- Validar aforo general
        SELECT COUNT(*) INTO cupos_usados
        FROM matriculas m
        JOIN instructor_curso ic ON m.id_instructor_curso = ic.id_instructor_curso
        WHERE ic.id_curso = v_id_curso
          AND m.estatus_cupo IN ('Reservado', 'Confirmado')
          AND m.id_matricula != NEW.id_matricula;
        
        IF cupos_usados >= limite THEN
            SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'No se puede reactivar la matricula, se ha alcanzado el aforo limite';
        END IF;
    END IF;

    -- Escenario B: Si pasa de 'Reservado' a 'Confirmado'
    IF NEW.estatus_cupo = 'Confirmado' AND OLD.estatus_cupo != 'Confirmado' THEN
        SELECT COUNT(*) INTO cupos_confirmados
        FROM matriculas m
        JOIN instructor_curso ic ON m.id_instructor_curso = ic.id_instructor_curso
        WHERE ic.id_curso = v_id_curso
          AND m.estatus_cupo = 'Confirmado'
          AND m.id_matricula != NEW.id_matricula;
        FROM matriculas m
        JOIN instructor_curso ic ON m.id_instructor_curso = ic.id_instructor_curso
        WHERE ic.id_curso = v_id_curso
          AND m.estatus_cupo = 'Confirmado'
          AND m.id_matricula != NEW.id_matricula;
        
        IF cupos_confirmados >= limite THEN
            SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'No se puede confirmar el cupo, ya se alcanzó el límite de confirmados';
        END IF;
    END IF;
END//

-- 3. Impedir eliminar cursos que registren cualquier matrícula (Histórica o Activa)
CREATE TRIGGER trg_cursos_no_delete_con_matriculas
BEFORE DELETE ON cursos
FOR EACH ROW
BEGIN
    DECLARE hay_matriculas INT;
    
    SELECT COUNT(*) INTO hay_matriculas
    FROM matriculas m
    JOIN instructor_curso ic ON m.id_instructor_curso = ic.id_instructor_curso
    WHERE ic.id_curso = OLD.id_curso;
    
    IF hay_matriculas > 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'No se puede eliminar el curso porque posee matriculas registradas en el historial';
    END IF;
END//

-- 4. Al verificar o rechazar un pago, actualizar estado financiero de la matrícula
CREATE TRIGGER trg_pago_estatus_change
AFTER UPDATE ON comprobante_pagos
FOR EACH ROW
BEGIN
    IF NEW.estatus_pago = 'Verificado' AND OLD.estatus_pago != 'Verificado' THEN
        UPDATE matriculas
        SET estatus_pago = 'Solvente',
            estatus_cupo = 'Confirmado',
            fecha_actualizacion = NOW()
        WHERE id_matricula = NEW.id_matricula;
    END IF;
    
    IF NEW.estatus_pago = 'Rechazado' AND OLD.estatus_pago = 'Verificado' THEN
        UPDATE matriculas
        SET estatus_pago = 'Deudora',
            estatus_cupo = 'Reservado',
            fecha_actualizacion = NOW()
        WHERE id_matricula = NEW.id_matricula;
    END IF;
END//

-- 5. Generar certificado automático al aprobar todas las evaluaciones activas del curso
CREATE TRIGGER trg_entregas_completadas
AFTER UPDATE ON entregas_evaluaciones
FOR EACH ROW
BEGIN
    DECLARE total_eval INT;
    DECLARE aprobadas INT;
    DECLARE existe_cert INT;
    DECLARE nombre_c VARCHAR(150);
    DECLARE v_id_instructor_curso INT;
    
    IF NEW.estatus_entrega = 'Aprobada' AND OLD.estatus_entrega != 'Aprobada' THEN
        
        -- Obtener la relación instructor_curso desde la matrícula del estudiante
        SELECT id_instructor_curso INTO v_id_instructor_curso 
        FROM matriculas 
        WHERE id_matricula = NEW.id_matricula;
        
        -- Total de evaluaciones activas definidas para ese curso/instancia
        SELECT COUNT(*) INTO total_eval
        FROM evaluaciones
        WHERE id_instructor_curso = v_id_instructor_curso
          AND estatus_evaluacion = TRUE;
        
        -- Evaluaciones aprobadas por este estudiante matriculado
        SELECT COUNT(*) INTO aprobadas
        FROM entregas_evaluaciones ee
        JOIN evaluaciones e ON ee.id_evaluacion = e.id_evaluacion
        WHERE ee.id_matricula = NEW.id_matricula
          AND e.id_instructor_curso = v_id_instructor_curso
          AND ee.estatus_entrega = 'Aprobada';
        
        -- Verificar si ya cuenta con certificado emitido o pendiente
        SELECT COUNT(*) INTO existe_cert
        FROM certificados
        WHERE id_matricula = v_id_matricula;
        
        -- Solo emitir si hay evaluaciones requeridas y todas fueron aprobadas
        IF total_eval > 0 AND total_eval = aprobadas AND existe_cert = 0 THEN
            
            SELECT c.nombre_curso INTO nombre_c 
            FROM cursos c
            JOIN instructor_curso ic ON c.id_curso = ic.id_curso
            WHERE ic.id_instructor_curso = v_id_instructor_curso;

            INSERT INTO certificados (
                id_matricula, 
                titulo_certificado, 
                descripcion_certificado, 
                categoria_certificado, 
                url_pdf_certificado, 
                estatus_certificado
            )
            VALUES (
                NEW.id_matricula, 
                CONCAT('Certificado de Aprobación - ', IFNULL(nombre_c, 'Curso')), 
                'Completó satisfactoriamente todas las evaluaciones del curso.', 
                'General', 
                NULL, 
                'Pendiente de Emisión'
            );
        END IF;
    END IF;
END//

DELIMITER ;