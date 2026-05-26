-- ==========================================
-- DS Beauty Academy - Modelo Fisico Actualizado
-- ==========================================

DROP DATABASE IF EXISTS ds_beauty_academy;
CREATE DATABASE ds_beauty_academy;
USE ds_beauty_academy;

-- ==========================================
-- TABLAS
-- ==========================================

CREATE TABLE instructores (
    id_instructor INT(36) PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nombre_instructor VARCHAR(100) NOT NULL,
    apellido_instructor VARCHAR(100) NOT NULL,
    cedula_instructor VARCHAR(50) NOT NULL UNIQUE,
    correo_instructor VARCHAR(150) NOT NULL,
    telefono_principal_instructor VARCHAR(50) NOT NULL,
    telefono_alternativo_instructor VARCHAR(50),
    usuario_instagram_instructor VARCHAR(100),
    estatus_instructor ENUM('activo','inactivo') DEFAULT 'activo',
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE especialidades (
    id_especialidad INT(36) PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nombre_especialidad VARCHAR(150) NOT NULL,
    estatus_especialidad BOOLEAN DEFAULT TRUE,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE especialidad_instructor (
    id_instructor INT(36) NOT NULL,
    id_especialidad INT(36) NOT NULL,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id_instructor, id_especialidad),
    FOREIGN KEY (id_instructor) REFERENCES instructores(id_instructor) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (id_especialidad) REFERENCES especialidades(id_especialidad) ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE TABLE certificado_instructor (
    id_certificado_instructor INT(36) PRIMARY KEY AUTO_INCREMENT NOT NULL,
    titulo_certificado_instructor VARCHAR(255) NOT NULL,
    descripcion_certificado_instructor TEXT,
    categoria_certificado_instructor VARCHAR(100) NOT NULL,
    url_pdf_certificado_instructor VARCHAR(512),
    id_instructor INT(36) NOT NULL,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_instructor) REFERENCES instructores(id_instructor) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE estudiantes (
    id_estudiante INT(36) PRIMARY KEY AUTO_INCREMENT NOT NULL,
    primer_nombre_estudiante VARCHAR(100) NOT NULL,
    segundo_nombre_estudiante VARCHAR(100),
    primer_apellido_estudiante VARCHAR(100) NOT NULL,
    segundo_apellido_estudiante VARCHAR(100),
    cedula_estudiante VARCHAR(50) NOT NULL UNIQUE,
    correo_estudiante VARCHAR(150) NOT NULL,
    telefono_principal_estudiante VARCHAR(50) NOT NULL,
    telefono_alternativo_estudiante VARCHAR(50),
    usuario_instagram_estudiante VARCHAR(100),
    estatus_estudiante ENUM('activo','inactivo') DEFAULT 'activo',
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE cursos (
    id_curso INT(36) PRIMARY KEY AUTO_INCREMENT NOT NULL,
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
    CHECK (precio_preventa IS NULL OR precio_preventa < precio_normal),
    CHECK (fecha_fin_preventa IS NULL OR fecha_fin_preventa < fecha_inicio_curso)
);

CREATE TABLE instructor_curso (
    id_instructor_curso INT(36) PRIMARY KEY AUTO_INCREMENT NOT NULL,
    id_instructor INT(36) NOT NULL,
    id_curso INT(36) NOT NULL,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_instructor_curso (id_instructor, id_curso),
    FOREIGN KEY (id_instructor) REFERENCES instructores(id_instructor) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (id_curso) REFERENCES cursos(id_curso) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE clases (
    id_clase INT(36) PRIMARY KEY AUTO_INCREMENT NOT NULL,
    titulo_clase VARCHAR(150) NOT NULL,
    descripcion_clase TEXT,
    url_video_clase VARCHAR(512) NOT NULL,
    orden_clase INT NOT NULL,
    estatus_clase BOOLEAN DEFAULT TRUE,
    id_instructor_curso INT(36) NOT NULL,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_instructor_curso) REFERENCES instructor_curso(id_instructor_curso) ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX idx_orden (orden_clase),
    INDEX idx_instructor_curso (id_instructor_curso)
);

CREATE TABLE etiqueta (
    id_etiqueta INT(36) PRIMARY KEY AUTO_INCREMENT NOT NULL,
    titulo_etiqueta VARCHAR(100) NOT NULL,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE recursos (
    id_recurso INT(36) PRIMARY KEY AUTO_INCREMENT NOT NULL,
    titulo_recurso VARCHAR(150) NOT NULL,
    descripcion_recurso TEXT,
    url_archivo VARCHAR(512) NOT NULL,
    id_etiqueta INT(36) NOT NULL,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_etiqueta) REFERENCES etiqueta(id_etiqueta) ON DELETE RESTRICT ON UPDATE CASCADE,
    INDEX idx_etiqueta (id_etiqueta)
);

CREATE TABLE recursos_curso (
    curso_id INT(36) NOT NULL,
    recurso_id INT(36) NOT NULL,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (curso_id, recurso_id),
    FOREIGN KEY (curso_id) REFERENCES cursos(id_curso) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (recurso_id) REFERENCES recursos(id_recurso) ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE TABLE matriculas (
    id_inscripcion INT(36) PRIMARY KEY AUTO_INCREMENT NOT NULL,
    id_estudiante INT(36) NOT NULL,
    curso_id INT(36) NOT NULL,
    fecha_solicitud DATETIME DEFAULT CURRENT_TIMESTAMP,
    precio_acordado DECIMAL(10,2) NOT NULL,
    estatus_pago ENUM('Deudora','Solvente') DEFAULT 'Deudora',
    estatus_cupo ENUM('Reservado','Confirmado','Cancelado') DEFAULT 'Reservado', 
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_estudiante_curso (id_estudiante, curso_id),
    FOREIGN KEY (id_estudiante) REFERENCES estudiantes(id_estudiante) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (curso_id) REFERENCES cursos(id_curso) ON DELETE RESTRICT ON UPDATE CASCADE,
    INDEX idx_curso (curso_id),
    INDEX idx_cupo (estatus_cupo)
);

CREATE TABLE banco (
    id_banco INT(36) PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nombre VARCHAR(150) NOT NULL UNIQUE,
    estatus_banco BOOLEAN DEFAULT TRUE,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE comprobante_pagos (
    id_pago INT(36) PRIMARY KEY AUTO_INCREMENT NOT NULL,
    id_inscripcion INT(36) NOT NULL,
    id_banco INT(36) NOT NULL,
    referencia_transaccion VARCHAR(150) NOT NULL UNIQUE,
    monto DECIMAL(10,2) NOT NULL,
    url_comprobante VARCHAR(512),
    fecha_pago DATETIME NOT NULL,
    estatus_pago ENUM('Pendiente','Verificado','Rechazado') DEFAULT 'Pendiente',
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_inscripcion) REFERENCES matriculas(id_inscripcion) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (id_banco) REFERENCES banco(id_banco) ON DELETE RESTRICT ON UPDATE CASCADE,
    INDEX idx_inscripcion (id_inscripcion),
    INDEX idx_ref (referencia_transaccion),
    INDEX idx_estatus_pago (estatus_pago)
);

CREATE TABLE evaluaciones (
    id_evaluacion INT(36) PRIMARY KEY AUTO_INCREMENT NOT NULL,
    id_instructor_curso INT(36) NOT NULL,
    titulo_evaluacion VARCHAR(150) NOT NULL,
    descripcion_evaluacion TEXT,
    orden_evaluacion INT NOT NULL,
    tipo_evaluacion ENUM('practica','teorica') DEFAULT 'practica',
    estatus_evaluacion BOOLEAN DEFAULT TRUE,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_instructor_curso) REFERENCES instructor_curso(id_instructor_curso) ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX idx_instructor_curso (id_instructor_curso),
    INDEX idx_orden (orden_evaluacion)
);

CREATE TABLE entregas_evaluaciones (
    id_entrega_evaluacion INT(36) PRIMARY KEY AUTO_INCREMENT NOT NULL,
    inscripcion_id INT(36) NOT NULL,
    evaluacion_id INT(36) NOT NULL,
    url_archivo_entrega VARCHAR(512) NOT NULL,
    estatus_entrega ENUM('Pendiente','En Revisión','Repetir','Aprobada') DEFAULT 'Pendiente',
    feedback_instructora_entrega TEXT,
    fecha_entrega DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_entrega (inscripcion_id, evaluacion_id),
    FOREIGN KEY (inscripcion_id) REFERENCES matriculas(id_inscripcion) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (evaluacion_id) REFERENCES evaluaciones(id_evaluacion) ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX idx_evaluacion (evaluacion_id),
    INDEX idx_estatus_entrega (estatus_entrega)
);

CREATE TABLE certificados (
    id_certificado INT(36) PRIMARY KEY AUTO_INCREMENT NOT NULL,
    id_inscripcion INT(36) NOT NULL UNIQUE,
    titulo_certificado VARCHAR(255) NOT NULL,
    descripcion_certificado TEXT,
    categoria_certificado VARCHAR(100) NOT NULL,
    url_pdf_certificado VARCHAR(512),
    estatus_certificado ENUM('Pendiente de Emisión','Emitido','Anulado') DEFAULT 'Pendiente de Emisión',
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_inscripcion) REFERENCES matriculas(id_inscripcion) ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX idx_estatus_cert (estatus_certificado)
);

-- ==========================================
-- TRIGGERS - DISPARADORES AJUSTADOS
-- ==========================================

DELIMITER //

-- 1. Control de cupo en INSERT (Actualizado sin columna activa)
CREATE TRIGGER trg_matricula_check_cupo
BEFORE INSERT ON matriculas
FOR EACH ROW
BEGIN
    DECLARE cupos_usados INT;
    DECLARE limite INT;
    
    SELECT limite_cupos INTO limite FROM cursos WHERE id_curso = NEW.curso_id;
    
    SELECT COUNT(*) INTO cupos_usados
    FROM matriculas
    WHERE curso_id = NEW.curso_id
      AND estatus_cupo IN ('Reservado', 'Confirmado');
    
    IF cupos_usados >= limite THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'No hay cupos disponibles para este curso';
    END IF;
END//

-- 2. Control de cupo en UPDATE (Actualizado sin columna activa)
CREATE TRIGGER trg_matricula_update_cupo
BEFORE UPDATE ON matriculas
FOR EACH ROW
BEGIN
    DECLARE cupos_confirmados INT;
    DECLARE limite INT;
    
    IF NEW.estatus_cupo = 'Confirmado' 
       AND OLD.estatus_cupo != 'Confirmado' THEN
       
        SELECT limite_cupos INTO limite FROM cursos WHERE id_curso = NEW.curso_id;
        
        SELECT COUNT(*) INTO cupos_confirmados
        FROM matriculas
        WHERE curso_id = NEW.curso_id
          AND estatus_cupo = 'Confirmado'
          AND id_inscripcion != NEW.id_inscripcion;
        
        IF cupos_confirmados >= limite THEN
            SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'No se puede confirmar el cupo, ya se alcanzó el límite';
        END IF;
    END IF;
END//

-- 3. Impedir eliminar cursos con matrículas vigentes (Actualizado sin columna activa)
CREATE TRIGGER trg_cursos_no_delete_con_matriculas
BEFORE DELETE ON cursos
FOR EACH ROW
BEGIN
    DECLARE hay_matriculas INT;
    
    SELECT COUNT(*) INTO hay_matriculas
    FROM matriculas
    WHERE curso_id = OLD.id_curso
      AND estatus_cupo IN ('Reservado', 'Confirmado');
    
    IF hay_matriculas > 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'No se puede eliminar el curso porque tiene matriculas activas o reservadas';
    END IF;
END//

-- 4. Al verificar/rechazar un pago, actualizar la matrícula (Actualizado a tabla matriculas y campos de fecha)
CREATE TRIGGER trg_pago_estatus_change
AFTER UPDATE ON comprobante_pagos
FOR EACH ROW
BEGIN
    IF NEW.estatus_pago = 'Verificado' AND OLD.estatus_pago != 'Verificado' THEN
        UPDATE matriculas
        SET estatus_pago = 'Solvente',
            estatus_cupo = 'Confirmado',
            fecha_actualizacion = NOW()
        WHERE id_inscripcion = NEW.id_inscripcion;
    END IF;
    
    IF NEW.estatus_pago = 'Rechazado' AND OLD.estatus_pago = 'Verificado' THEN
        UPDATE matriculas
        SET estatus_pago = 'Deudora',
            estatus_cupo = 'Reservado',
            fecha_actualizacion = NOW()
        WHERE id_inscripcion = NEW.id_inscripcion;
    END IF;
END//

-- 5. Generar certificado automático (Actualizado con los nuevos campos obligatorios de ModelCertificado)
CREATE TRIGGER trg_entregas_completadas
AFTER UPDATE ON entregas_evaluaciones
FOR EACH ROW
BEGIN
    DECLARE total_eval INT;
    DECLARE aprobadas INT;
    DECLARE existe_cert INT;
    DECLARE nombre_c VARCHAR(150);
    
    IF NEW.estatus_entrega = 'Aprobada' AND OLD.estatus_entrega != 'Aprobada' THEN
        SELECT COUNT(*) INTO total_eval
        FROM evaluaciones e
        JOIN instructor_curso ic ON e.id_instructor_curso = ic.id_instructor_curso
        JOIN matriculas m ON m.curso_id = ic.id_curso
        WHERE m.id_inscripcion = NEW.inscripcion_id;
        
        SELECT COUNT(*) INTO aprobadas
        FROM entregas_evaluaciones ee
        JOIN evaluaciones e ON ee.evaluacion_id = e.id_evaluacion
        JOIN instructor_curso ic ON e.id_instructor_curso = ic.id_instructor_curso
        JOIN matriculas m ON m.curso_id = ic.id_curso
        WHERE m.id_inscripcion = NEW.inscripcion_id
          AND ee.estatus_entrega = 'Aprobada';
        
        SELECT COUNT(*) INTO existe_cert
        FROM certificados
        WHERE id_inscripcion = NEW.inscripcion_id;
        
        IF total_eval = aprobadas AND existe_cert = 0 THEN
            -- Obtenemos el nombre del curso para asignarlo al título del certificado
            SELECT c.nombre_curso INTO nombre_c 
            FROM cursos c
            JOIN matriculas m ON m.curso_id = c.id_curso
            WHERE m.id_inscripcion = NEW.inscripcion_id;

            INSERT INTO certificados (id_inscripcion, titulo_certificado, descripcion_certificado, categoria_certificado, url_pdf_certificado, estatus_certificado)
            VALUES (NEW.inscripcion_id, CONCAT('Certificado de Aprobación - ', nombre_c), 'Completó satisfactoriamente todas las evaluaciones.', 'General', NULL, 'Pendiente de Emisión');
        END IF;
    END IF;
END//

-- 6. Garantizar orden único por instructor_curso en clases
CREATE TRIGGER trg_orden_clase_unico
BEFORE INSERT ON clases
FOR EACH ROW
BEGIN
    DECLARE existe INT;
    SELECT COUNT(*) INTO existe FROM clases
    WHERE id_instructor_curso = NEW.id_instructor_curso AND orden_clase = NEW.orden_clase;
    IF existe > 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Ya existe una clase con ese orden para este instructor/curso';
    END IF;
END//

-- 7. Garantizar orden único por instructor_curso en evaluaciones
CREATE TRIGGER trg_orden_evaluacion_unico
BEFORE INSERT ON evaluaciones
FOR EACH ROW
BEGIN
    DECLARE existe INT;
    SELECT COUNT(*) INTO existe FROM evaluaciones
    WHERE id_instructor_curso = NEW.id_instructor_curso AND orden_evaluacion = NEW.orden_evaluacion;
    IF existe > 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Ya existe una evaluación con ese orden para este instructor/curso';
    END IF;
END//

DELIMITER ;