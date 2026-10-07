# Documentación de Cambios y Mejoras - Esquema de Base de Datos
**Proyecto:** DS Beauty Academy  
**Archivo Modificado:** `ds_beauty_academy_db.sql`  
**Versión Resultante:** Versión 7.0 (Corregida y Blindada)  
**Fecha:** 28 de Septiembre de 2026  

---

## 1. Introducción

El presente documento registra de forma pormenorizada cada corrección, ajuste estructural, normalización relacional y optimización aplicada al esquema de base de datos `ds_beauty_academy_db.sql`. 

Cada sección detalla:
1. **Problema:** La vulnerabilidad, incompatibilidad o riesgo detectado.
2. **Cambio Realizado:** La modificación exacta efectuada a nivel de DDL o procedural.
3. **Por Qué / Justificación:** El fundamento técnico y la regla de negocio que respalda la decisión.

---

## 2. Registro Detallado de Cambios

### Cambio 1: Reestructuración Integral de Evaluaciones y Entregas

* **Problema:**  
  En la versión anterior (v6.0), la tabla `evaluaciones` poseía una clave foránea hacia `matriculas(id_matricula)`, y la tabla `entregas_evaluaciones` tenía una restricción `UNIQUE KEY unique_entrega (id_evaluacion)`. 
  - Esto forzaba una desnormalización aberrante: para un curso con 50 estudiantes y 4 tareas, debían crearse 200 filas idénticas de evaluaciones individuales.
  - La restricción de unicidad en entregas impedía que más de un estudiante enviara una evaluación.
  - Rompía frontalmente con los modelos del backend PHP (`EvaluacionController.php`, `EvaluacionModel.php` y `EntregaEvaluacionModel.php`), los cuales esperan que la evaluación pertenezca a la instancia de curso impartido (`id_instructor_curso`) y que la entrega vincule al alumno con dicha evaluación.

* **Cambio Realizado:**  
  1. Se modificó la tabla `evaluaciones` para que apunte a `instructor_curso(id_instructor_curso)` con `ON DELETE CASCADE`.
  2. Se redefinió la clave de orden: `UNIQUE KEY unique_orden_evaluacion (id_instructor_curso, orden_evaluacion)`.
  3. Se reformuló la tabla `entregas_evaluaciones` para incluir `id_matricula INT NOT NULL`, además de campos de control pedagógico (`calificacion_entrega`, `comentarios_estudiante`, `feedback_instructora_entrega`).
  4. Se estableció la restricción de unicidad por alumno y tarea: `UNIQUE KEY unique_evaluacion_matricula (id_evaluacion, id_matricula)`.

* **Por Qué / Justificación:**  
  Restablece la 3ra Forma Normal (3FN), permite que una evaluación se defina una sola vez por curso, habilita que todos los estudiantes inscritos entreguen sus actividades de forma independiente y sincroniza la base de datos con los controladores y modelos de la arquitectura MVC de PHP.

---

### Cambio 2: Blindaje contra Doble Matriculación del Mismo Curso

* **Problema:**  
  La tabla `matriculas` contaba únicamente con `UNIQUE KEY unique_estudiante_instructor_curso (id_estudiante, id_instructor_curso)`. Si un curso tiene asignado a más de un instructor (ayudantes, co-docentes o diferentes secciones), un estudiante podía matricularse dos veces al mismo curso bajo docentes diferentes, duplicando el consumo de cupos y cobros.

* **Cambio Realizado:**  
  Se incorporó en los disparadores `trg_matricula_check_cupo` (al insertar) y `trg_matricula_update_cupo` (al reactivar) una consulta de validación cruzada que une `matriculas` con `instructor_curso` para verificar que el estudiante no cuente ya con otra matrícula con estatus `'Reservado'` o `'Confirmado'` para el mismo `id_curso`.

* **Por Qué / Justificación:**  
  Garantiza la unicidad lógica a nivel de oferta académica (curso) sin necesidad de romper las claves foráneas existentes hacia `instructor_curso` requeridas por el flujo de trabajo del frontend.

---

### Cambio 3: Blindaje Lógico en Disparadores de Aforo y Cupos

* **Problema:**  
  1. En `trg_matricula_check_cupo` (INSERT): Se calculaban cupos y se bloqueaba la inserción sin revisar si la matrícula entrante era `'Cancelado'`. Esto impedía la importación o registro de inscripciones archivadas o canceladas si el curso estaba lleno.
  2. En `trg_matricula_update_cupo` (UPDATE): Solo se validaba cuando pasaba a `'Confirmado'`. Si una matrícula cancelada se reactivaba a `'Reservado'`, no se verificaba el aforo disponible, permitiendo reservas ilimitadas por encima de la capacidad física.

* **Cambio Realizado:**  
  1. En `INSERT`: Se condicionó la comprobación de aforo a: `IF NEW.estatus_cupo IN ('Reservado', 'Confirmado') THEN ...`.
  2. En `UPDATE`: Se dividió la lógica en dos escenarios:
     - **Escenario A:** Reactivación desde `'Cancelado'` a `'Reservado'` o `'Confirmado'`, validando aforo disponible y no duplicidad.
     - **Escenario B:** Transición de `'Reservado'` a `'Confirmado'`, garantizando que no se sobrepase el límite de cupos confirmados.

* **Por Qué / Justificación:**  
  Cierra por completo las brechas de aforo y previene la sobreventa de cupos en todas las rutas posibles del ciclo de vida de una matrícula.

---

### Cambio 4: Eliminación de Restricción `UNIQUE` en Teléfono de Estudiantes

* **Problema:**  
  La columna `telefono_principal_estudiante` tenía una restricción `UNIQUE`. En academias técnicas de estética y belleza es común que miembros de un mismo núcleo familiar (hermanas, madre e hija o menores de edad representados por sus tutores) compartan el mismo número telefónico de contacto. Mantener `UNIQUE` provocaba fallas HTTP 500 y excepciones PDO en registros legítimos.

* **Cambio Realizado:**  
  Se retiró la restricción `UNIQUE` en `telefono_principal_estudiante`, dejándolo únicamente como `VARCHAR(50) NOT NULL`.

* **Por Qué / Justificación:**  
  La unicidad y no repetición de la identidad del estudiante recae en identificadores individuales formales: `cedula_estudiante` y `correo_estudiante`.

---

### Cambio 5: Eliminación de Restricción `UNIQUE` Global en Recursos Didácticos

* **Problema:**  
  La tabla `recursos` imponía `nombre_recurso VARCHAR(150) NOT NULL UNIQUE`. Esto impedía que dos cursos diferentes pudieran disponer de un archivo con el mismo nombre genérico (ej. "Guía Práctica de Higiene", "Lista de Insumos.pdf" o "Cronograma").

* **Cambio Realizado:**  
  Se removió la restricción `UNIQUE` de `nombre_recurso`.

* **Por Qué / Justificación:**  
  Los nombres de materiales educativos son recurrentes y descriptivos. La unicidad técnica se conserva en la clave primaria (`id_recurso`), y la vinculación por curso se gestiona mediante la tabla intermedia `recursos_curso`.

---

### Cambio 6: Protección de Auditoría Financiera en Comprobantes de Pago

* **Problema:**  
  La clave foránea de `comprobante_pagos` hacia `matriculas` estaba configurada con `ON DELETE CASCADE`. Si una matrícula era eliminada físicamente de la base de datos, se destruían en cascada todos los registros de transferencias bancarias, montos ingresados y referencias de pago.

* **Cambio Realizado:**  
  Se cambió la acción referencial a:
  ```sql
  FOREIGN KEY (id_matricula) REFERENCES matriculas(id_matricula) ON DELETE RESTRICT ON UPDATE CASCADE
  ```

* **Por Qué / Justificación:**  
  Cumplimiento de estándares de auditoría fiscal y trazabilidad contable. Ningún registro de movimiento de dinero debe ser susceptible a borrado incidental por cascadas relacionales.

---

### Cambio 7: Incorporación de Restricciones `CHECK` para Validación de Integridad

* **Problema:**  
  El motor de base de datos admitía datos ilógicos como montos de pago negativos o cero, aforos de cursos negativos o iguales a cero, y fechas de culminación de curso previas a la fecha de inicio.

* **Cambio Realizado:**  
  Se agregaron las siguientes cláusulas `CHECK`:
  - **En `cursos`:**
    ```sql
    CHECK (precio_normal >= 0),
    CHECK (precio_preventa IS NULL OR (precio_preventa >= 0 AND precio_preventa < precio_normal)),
    CHECK (fecha_fin_preventa IS NULL OR fecha_fin_preventa < fecha_inicio_curso),
    CHECK (fecha_fin_curso IS NULL OR fecha_fin_curso >= fecha_inicio_curso),
    CHECK (limite_cupos > 0)
    ```
  - **En `matriculas`:**
    ```sql
    CHECK (precio_acordado >= 0)
    ```
  - **En `comprobante_pagos`:**
    ```sql
    CHECK (monto > 0)
    ```

* **Por Qué / Justificación:**  
  Defensa en profundidad: la base de datos no debe confiar ciegamente en que las capas superiores (JavaScript o PHP) filtren todos los valores ilógicos. El motor de almacenamiento debe ser el último garante de la verdad del negocio.

---

### Cambio 8: Depuración de Índices Redundantes

* **Problema:**  
  Se detectaron múltiples índices explícitos que duplicaban índices generados automáticamente por restricciones `UNIQUE` o por claves compuestas:
  - En `clases`: `INDEX idx_instructor_curso` duplicaba el prefijo de `UNIQUE KEY (id_instructor_curso, orden_clase)`.
  - En `comprobante_pagos`: `INDEX idx_ref (referencia_transaccion)` duplicaba el índice B-Tree generado por `UNIQUE KEY (referencia_transaccion)`.
  - En `evaluaciones` y `entregas_evaluaciones`: índices sobre columnas únicas o claves primarias.

* **Cambio Realizado:**  
  Se eliminaron los índices duplicados redundantes, manteniendo únicamente los índices necesarios para claves foráneas y búsquedas por columnas de filtrado frecuente (`estatus_pago`, `estatus_cupo`, `estatus_certificado`).

* **Por Qué / Justificación:**  
  Reduce el espacio de almacenamiento consumido por índices B-Tree en disco y disminuye la latencia de CPU en operaciones de inserción, actualización y borrado.

---

### Cambio 9: Unificación de Eliminación Preventiva en Cursos (`trg_cursos_no_delete_con_matriculas`)

* **Problema:**  
  El trigger original solo comprobaba matrículas con estatus `'Reservado'` o `'Confirmado'`. Si un curso solo tenía matrículas `'Cancelado'`, el trigger no disparaba la excepción, pero el borrado fallaba inmediatamente con un error críptico de InnoDB debido al `ON DELETE RESTRICT` entre `instructor_curso` y `matriculas`.

* **Cambio Realizado:**  
  Se ajustó la consulta del trigger para verificar la existencia de **cualquier matrícula registrada** en el curso (incluso canceladas):
  ```sql
  SELECT COUNT(*) INTO hay_matriculas
  FROM matriculas m
  JOIN instructor_curso ic ON m.id_instructor_curso = ic.id_instructor_curso
  WHERE ic.id_curso = OLD.id_curso;
  ```

* **Por Qué / Justificación:**  
  Proporciona un mensaje de error legible y consistente para el usuario y el backend, respetando la regla educativa de que un curso con historial académico y de inscripciones no debe ser purgado físicamente.

---

### Cambio 10: Vinculación Referencial Opcional en `usuarios`

* **Problema:**  
  La tabla `usuarios` existía como una isla de credenciales y roles, sin relación referencial hacia `instructores` o `estudiantes`.

* **Cambio Realizado:**  
  Se agregaron los campos `id_instructor INT NULL UNIQUE` e `id_estudiante INT NULL UNIQUE` con claves foráneas `ON DELETE SET NULL ON UPDATE CASCADE`.

* **Por Qué / Justificación:**  
  Permite asociar una cuenta de inicio de sesión con su correspondiente perfil de instructor o estudiante, asegurando coherencia y evitando cuentas duplicadas para un mismo perfil, sin impedir la existencia de cuentas puramente administrativas (SuperAdmin).

---

## 3. Resumen Comparativo de Versiones

| Parámetro | Versión 6.0 (Anterior) | Versión 7.0 (Actualizada) | Estado de Mejora |
| :--- | :--- | :--- | :--- |
| **Jerarquía de Evaluaciones** | Acoplada a matrícula individual | Perteneciente a `instructor_curso` | Normalizada (3FN) y compatible con MVC |
| **Entregas de Evaluaciones** | Restringida a 1 por tarea global | Relación 1:1 por Matrícula y Evaluación | Corrige bloqueo para cursos con >1 alumno |
| **Doble Matriculación** | Permitida entre distintos instructores | Bloqueada a nivel de curso en Triggers | Blindaje de aforo y unicidad |
| **Reactivación de Canceladas** | Sin control de cupos en `UPDATE` | Aforo verificado en reactivaciones | Prevención de sobrecupo |
| **Teléfono de Estudiante** | Restricción `UNIQUE` estricta | Sin `UNIQUE` | Amigable con grupos familiares |
| **Nombres de Recursos** | Unicidad global | Sin `UNIQUE` global | Permite nombres de materiales estándar |
| **Borrado de Matrículas y Pagos** | `CASCADE` (Eliminación contable) | `RESTRICT` | Máxima protección fiscal y auditoría |
| **Validaciones CHECK** | Parciales (precios y fechas) | Completas (fechas, cupos > 0, montos > 0) | Integridad a nivel de motor SQL |
| **Índices Duplicados** | 4 índices redundantes | 0 índices redundantes | Optimización de memoria y escritura |
