# Auditoría de Integridad y Restricciones de Base de Datos
**Proyecto:** DS Beauty Academy  
**Archivo Evaluado:** `ds_beauty_academy_db.sql` (Versión 6.0)  
**Fecha:** 28 de Septiembre de 2026  
**Motor Objetivo:** MySQL 8.0+ / MariaDB 10.4+ (InnoDB Engine)

---

## 1. Resumen Ejecutivo de la Auditoría

Se realizó una inspección minuciosa del esquema físico de la base de datos `ds_beauty_academy_db.sql`. La auditoría abarcó la totalidad de las tablas, relaciones de clave foránea (FK), claves primarias (PK), restricciones de unicidad (`UNIQUE`), restricciones de comprobación (`CHECK`), índices de rendimiento y disparadores procedimentales (`TRIGGERS`).

A pesar de contar con una estructura relacional base avanzada con disparadores para aforos y transiciones de estado, se detectaron **fallas críticas de integridad relacional, discrepancias severas con la arquitectura del backend en PHP, índices redundantes que degradan el rendimiento de escritura y brechas lógicas en los disparadores**.

---

## 2. Hallazgos Críticos de Integridad Lógica y Relacional

### 2.1. Vulnerabilidad de Doble Matriculación por Curso
* **Tabla afectada:** `matriculas`
* **Definición actual:**
  ```sql
  UNIQUE KEY unique_estudiante_instructor_curso (id_estudiante, id_instructor_curso)
  ```
* **Diagnóstico del problema:**  
  La restricción de unicidad está definida sobre la tupla `(id_estudiante, id_instructor_curso)`. Si un curso tiene asignado a más de un instructor (por ejemplo, co-instructores, ayudantes o secciones en `instructor_curso`), un mismo estudiante tiene la posibilidad de matricularse dos veces al mismo curso bajo instructores distintos.
* **Impacto en el sistema:**  
  El aforo y cupos se calculan a nivel de curso global (`cursos.id_curso`), por lo que un estudiante duplicado ocupa dos cupos de aforo y genera registros de cobro duplicados para una misma materia didáctica.

---

### 2.2. Desfase Estructural entre el Esquema SQL y el Backend (Evaluaciones y Entregas)
* **Tablas afectadas:** `evaluaciones`, `entregas_evaluaciones`
* **Definición actual en SQL v6.0:**
  ```sql
  CREATE TABLE evaluaciones (
      id_evaluacion INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
      id_matricula INT NOT NULL, -- << Problema crítico
      titulo_evaluacion VARCHAR(150) NOT NULL,
      ...
      FOREIGN KEY (id_matricula) REFERENCES matriculas(id_matricula) ON DELETE CASCADE
  );

  CREATE TABLE entregas_evaluaciones (
      id_entrega_evaluacion INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
      id_evaluacion INT NOT NULL,
      ...
      UNIQUE KEY unique_entrega (id_evaluacion) -- << Impide más de una entrega
  );
  ```
* **Diagnóstico del problema:**  
  1. En la versión 6.0 del SQL se acopló la tabla `evaluaciones` a `id_matricula`. Esto significa que la evaluación pertenece a una matrícula individual y no al curso.
  2. Si un curso tiene 50 alumnos matriculados y 4 evaluaciones, el sistema tendría que clonar e insertar 200 filas idénticas en `evaluaciones`. Si el instructor corrige el enunciado, tendría que actualizar 50 filas.
  3. `entregas_evaluaciones` tiene `UNIQUE KEY (id_evaluacion)`. Si una evaluación perteneciera al curso, **solo un único estudiante en todo el mundo podría entregar la tarea**, bloqueando a todos los demás con error de clave duplicada.
  4. **Incompatibilidad directa con el código PHP:** El controlador `src/Controllers/EvaluacionController.php` y los modelos `EvaluacionModel.php` y `EntregaEvaluacionModel.php` esperan que `evaluaciones` dependa de `id_instructor_curso`, y que `entregas_evaluaciones` vincule `id_evaluacion` e `id_matricula`. Al ejecutar la aplicación, todas las operaciones de creación de evaluaciones fallan con error de columna inexistente.

---

### 2.3. Brechas en los Triggers de Control de Aforo y Cupo
* **Disparadores afectados:** `trg_matricula_check_cupo` (INSERT) y `trg_matricula_update_cupo` (UPDATE).
* **Diagnóstico del problema:**
  1. **En `INSERT` (`trg_matricula_check_cupo`):**  
     El trigger cuenta los cupos usados y lanza error si `cupos_usados >= limite`, sin evaluar el estatus de la fila entrante (`NEW.estatus_cupo`). Si un administrador o script importa una matrícula ya `'Cancelada'`, el trigger la rechaza arbitrariamente si el curso estaba lleno.
  2. **En `UPDATE` (`trg_matricula_update_cupo`):**  
     El trigger únicamente intercepta transiciones hacia `'Confirmado'`:
     ```sql
     IF NEW.estatus_cupo = 'Confirmado' AND OLD.estatus_cupo != 'Confirmado' THEN ...
     ```
     Si una matrícula pasa del estado `'Cancelado'` a `'Reservado'`, **no existe ninguna validación de límite de cupos**, permitiendo sobreventa o sobrecupo en reservaciones.
  3. **Concurrencia (Race Conditions):**  
     Las consultas `SELECT COUNT(*)` dentro de los triggers carecen de bloqueo a nivel de fila (`FOR UPDATE`), lo que bajo peticiones HTTP simultáneas concurrentes permite que dos transacciones paralelas reserven el último cupo simultáneamente.

---

### 2.4. Conflicto Lógico en Eliminación de Cursos (`ON DELETE RESTRICT` vs Trigger)
* **Tablas y triggers afectados:** `instructor_curso`, `matriculas`, `trg_cursos_no_delete_con_matriculas`.
* **Diagnóstico del problema:**
  - `cursos` tiene `ON DELETE CASCADE` hacia `instructor_curso`.
  - `instructor_curso` tiene `ON DELETE RESTRICT` hacia `matriculas`.
  - El disparador `trg_cursos_no_delete_con_matriculas` bloquea la eliminación solo si existen matrículas con estatus `'Reservado'` o `'Confirmado'`.
  - Si un curso solo tiene matrículas en estado `'Cancelado'`, el trigger permite el `DELETE`, pero de inmediato la clave foránea de InnoDB aborta la transacción con una violación de restricción referencial externa. El trigger crea una falsa expectativa de éxito.

---

## 3. Riesgos en Restricciones DDL y Reglas de Negocio

| Objeto / Columna | Restricción Evaluada | Riesgo Operativo y de Negocio Detectado |
| :--- | :--- | :--- |
| **`estudiantes.telefono_principal_estudiante`** | `VARCHAR(50) NOT NULL UNIQUE` | **Bloqueo en registros familiares:** En academias de belleza es sumamente común que familiares (hermanas, madres e hijas) compartan un teléfono de contacto fijo o móvil familiar. Imponer unicidad causa fallos de inserción irrecuperables. |
| **`recursos.nombre_recurso`** | `VARCHAR(150) NOT NULL UNIQUE` | **Colisión global de nombres de archivos:** Impide que dos cursos distintos compartan nombres comunes de recursos didácticos como `"Guía Teórica.pdf"`, `"Materiales.pdf"` o `"Lista de Insumos.pdf"`. |
| **`comprobante_pagos` (FK)** | `ON DELETE CASCADE` en `id_matricula` | **Destrucción de trazabilidad financiera:** Si un usuario elimina una matrícula, se borran automáticamente los registros de transacciones bancarias, montos y referencias, violando principios contables y de auditoría fiscal. |
| **`cursos.precio_normal` y `precio_preventa`** | `DECIMAL(10,2)` sin `CHECK` de signo | Permite valores negativos, abriendo la puerta a errores de tipeo o inconsistencias de facturación. |
| **`cursos.limite_cupos`** | `INT` sin `CHECK (limite_cupos > 0)` | Permite definir cursos con cupo 0 o números negativos, corrompiendo la lógica aritmética de los triggers de aforo. |
| **`cursos.fechas`** | Sin restricción temporal | No valida que `fecha_fin_curso >= fecha_inicio_curso`. |
| **`comprobante_pagos.monto`** | `DECIMAL(10,2)` sin `CHECK` | Permite el registro de comprobantes de pago con monto 0.00 o cifras negativas. |

---

## 4. Redundancia de Índices y Degradación de Desempeño

En MySQL InnoDB, las restricciones `UNIQUE` y las claves primarias ya implementan estructuras B-Tree balanceadas. Asimismo, un índice compuesto `(A, B)` permite resolver consultas que filtren por `A` mediante coincidencia por prefijo. Se detectaron los siguientes índices 100% redundantes:

1. **Tabla `clases`:**
   - Restricción: `UNIQUE KEY unique_orden_clase (id_instructor_curso, orden_clase)`
   - Índice duplicado: `INDEX idx_instructor_curso (id_instructor_curso)` ➔ **Redundante**.
2. **Tabla `evaluaciones`:**
   - Restricción: Clave única sobre `(id_instructor_curso, orden_evaluacion)`
   - Índice duplicado: `INDEX idx_matricula (id_matricula)` ➔ **Redundante**.
3. **Tabla `entregas_evaluaciones`:**
   - Restricción: Clave sobre la relación
   - Índice duplicado: `INDEX idx_evaluacion (id_evaluacion)` ➔ **Redundante**.
4. **Tabla `comprobante_pagos`:**
   - Restricción: `referencia_transaccion VARCHAR(150) NOT NULL UNIQUE`
   - Índice duplicado: `INDEX idx_ref (referencia_transaccion)` ➔ **Redundante**.

*Impacto:* Cada índice innecesario añade una penalización en disco y tiempo de CPU durante operaciones `INSERT`, `UPDATE` y `DELETE`.

---

## 5. Desconexión de la Entidad de Seguridad (`usuarios`)

La tabla `usuarios` autentica correos y contraseñas y asigna un `id_rol`, pero no tiene conexión referencial con las tablas de los actores de negocio (`instructores` o `estudiantes`).
* **Riesgo:** Si un estudiante o docente actualiza su correo en su perfil personal, no hay garantía de sincronización con la cuenta de acceso, lo que propicia cuentas huérfanas y desarticulación del control de sesiones.

---

## 6. Conclusiones y Próximos Pasos

El esquema requiere una versión correctiva integral (Versión 7.0) que:
1. Reestructure `evaluaciones` y `entregas_evaluaciones` para alinearse con la arquitectura MVC del backend.
2. Añada validaciones `CHECK` a nivel de motor para precios, cupos, fechas y montos.
3. Elimine restricciones perjudiciales (`UNIQUE` en teléfono y en nombres de recursos).
4. Corrija los triggers para proteger aforos en todos los escenarios de actualización y evitar duplicados de curso.
5. Elimine los índices redundantes y proteja la auditoría financiera con `ON DELETE RESTRICT`.
