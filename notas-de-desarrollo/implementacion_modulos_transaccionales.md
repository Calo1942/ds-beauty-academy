# Documentación Técnica: Implementación de Módulos Transaccionales (ACID)
**Proyecto:** DS Beauty Academy  
**Versión de Base de Datos:** 7.0 (InnoDB)  
**Motor de Aplicación:** PHP 7.3+ (PDO) / Arquitectura MVC  
**Fecha:** 28 de Septiembre de 2026  

---

## 1. Fundamentos y Filosofía de Transaccionalidad

Un módulo transaccional en **DS Beauty Academy** no es un CRUD convencional sobre un único catálogo; representa un flujo de negocio misional donde intervienen múltiples tablas interconectadas, recursos compartidos finitos (aforos de cursos) o registros contables/financieros.

Cada módulo transaccional se implementó respetando los principios **ACID**:
- **Atomicidad (A):** Todas las sentencias dentro de un flujo se confirman juntas (`commit`) o se anulan completamente (`rollBack`).
- **Consistencia (C):** Ningún cambio viola las reglas de negocio, llaves foráneas ni los disparadores (`TRIGGERS`) de la base de datos.
- **Aislamiento (I):** Bloqueo pesimista mediante cláusula `FOR UPDATE` en conciliaciones de pago para evitar condiciones de carrera (Race Conditions).
- **Durabilidad (D):** Persistencia garantizada en las tablas transaccionales de InnoDB.

---

## 2. Detalle de Módulos Transaccionales Implementados

### Módulo 1: Matrículas y Reservas Académicas
* **Modelo:** [`src/models/MatriculaModel.php`](file:///C:/xampp/htdocs/un-carlos/ds-beauty-academy-main/src/models/MatriculaModel.php)
* **Controlador:** [`src/Controllers/MatriculaController.php`](file:///C:/xampp/htdocs/un-carlos/ds-beauty-academy-main/src/Controllers/MatriculaController.php)
* **Tablas Intervinientes:** `matriculas`, `instructor_curso`, `cursos`, `estudiantes`.

#### Características Técnicas:
1. **Sincronización con el Modelo Físico:** Se ajustó el atributo `$id_instructor_curso` (reemplazando el desactualizado `$id_curso`), alineando la entidad con la oferta y sección académica.
2. **Método `guardar($data)` Atómico:**
   ```php
   $this->con->beginTransaction();
   $stmt = $this->con->prepare($sql);
   $stmt->execute($values);
   $id = $this->con->lastInsertId();
   $this->con->commit();
   ```
3. **Manejo de Señales de Base de Datos (Disparadores):**
   - El disparador `trg_matricula_check_cupo` intercepta inserciones y valida aforo en tiempo real.
   - Si se supera el límite de cupos o si el estudiante ya está matriculado en el mismo curso (incluso con otro instructor), MySQL emite `SIGNAL SQLSTATE '45000'`.
   - El bloque `catch (PDOException $e)` ejecuta `$this->con->rollBack()` y devuelve una respuesta limpia al usuario vía `ApiResponse::error(400, $e->getMessage())`.
4. **Soft Delete:** El método `eliminar($id)` no purga el registro, sino que actualiza `estatus_cupo = 'Cancelado'`, preservando la auditoría académica y liberando el cupo para otros postulantes.

---

### Módulo 2: Caja, Cobranzas y Conciliación Bancaria
* **Modelo:** [`src/models/ComprobantePagoModel.php`](file:///C:/xampp/htdocs/un-carlos/ds-beauty-academy-main/src/models/ComprobantePagoModel.php)
* **Controlador:** [`src/Controllers/ComprobantePagoController.php`](file:///C:/xampp/htdocs/un-carlos/ds-beauty-academy-main/src/Controllers/ComprobantePagoController.php)
* **Tablas Intervinientes:** `comprobante_pagos`, `matriculas`, `banco`, `instructor_curso`, `cursos`.

#### Características Técnicas:
1. **Corrección de Relaciones:** Se reestructuraron las consultas `buscarTodos()` y `buscar()` para conectar `matriculas -> instructor_curso -> cursos`, eliminando el error `Unknown column m.id_curso`.
2. **Método `conciliarPago($id, $nuevo_estatus)`:**
   - Inicia transacción PDO.
   - Aplica bloqueo pesimista con `SELECT ... FOR UPDATE` sobre el comprobante para prevenir colisiones por verificaciones paralelas.
   - Al actualizar a `'Verificado'`, el disparador `trg_pago_estatus_change` en MySQL conmuta inmediatamente la matrícula vinculada a `estatus_pago = 'Solvente'` y `estatus_cupo = 'Confirmado'`.
   - Si el pago es `'Rechazado'`, revierte la matrícula a `'Deudora'` y `'Reservado'`.
3. **Validación de Referencia Bancaria:** Utiliza `$this->validate_referencia` (`/^[A-Za-z0-9\-_]{5,100}$/`), permitiendo guiones y formatos alfanuméricos bancarios reales.

---

### Módulo 3: Evaluaciones y Avance Pedagógico
* **Modelos:** [`src/models/EvaluacionModel.php`](file:///C:/xampp/htdocs/un-carlos/ds-beauty-academy-main/src/models/EvaluacionModel.php) y [`src/models/EntregaEvaluacionModel.php`](file:///C:/xampp/htdocs/un-carlos/ds-beauty-academy-main/src/models/EntregaEvaluacionModel.php)
* **Controladores:** [`src/Controllers/EvaluacionController.php`](file:///C:/xampp/htdocs/un-carlos/ds-beauty-academy-main/src/Controllers/EvaluacionController.php)
* **Tablas Intervinientes:** `evaluaciones`, `entregas_evaluaciones`, `matriculas`, `certificados`.

#### Características Técnicas:
1. **Normalización 3FN:** La evaluación pertenece a la instancia docente (`instructor_curso`), permitiendo que el profesor redacte las instrucciones una sola vez para todo el grupo de alumnos.
2. **Entregas de Alumnos:** `entregas_evaluaciones` vincula `(id_evaluacion, id_matricula)`. Cada estudiante matriculado sube su asignación con estatus `'Pendiente'`.
3. **Método `calificarEntrega($id, $calificacion, $feedback, $estatus)`:**
   - La docente califica y cambia el estatus a `'Aprobada'`.
   - Esta mutación dispara `trg_entregas_completadas`, que calcula si el estudiante aprobó el 100% de las evaluaciones activas del curso. Si es así y no tiene certificado previo, emite de forma automática el certificado digital en la tabla `certificados`.

---

## 3. Resumen de la Suite de Pruebas Automatizadas

La suite ubicada en [`tests/test_modulos_transaccionales.php`](file:///C:/xampp/htdocs/un-carlos/ds-beauty-academy-main/tests/test_modulos_transaccionales.php) fue ejecutada contra el entorno real de MySQL en XAMPP.

### Matriz de Resultados:

| # | Prueba Transaccional | Escenario Evaluado | Resultado |
| :--- | :--- | :--- | :---: |
| **1** | Registro de 1era Matrícula | Aforo disponible (1 de 2 cupos) | **PASÓ** |
| **2** | Registro de 2da Matrícula | Aforo completado (2 de 2 cupos) | **PASÓ** |
| **3** | Intento de Sobrecupo | Bloqueo por `trg_matricula_check_cupo` | **PASÓ** |
| **4** | Doble Matriculación | Bloqueo al intentar reinscribir al mismo estudiante | **PASÓ** |
| **5.1**| Registro de Comprobante | Inserción en estado `'Pendiente'` | **PASÓ** |
| **5.2**| Conciliación de Pago | Cambio transaccional a `'Verificado'` | **PASÓ** |
| **5.3**| Sincronización Automática | Matrícula pasa a `'Solvente'` y `'Confirmado'` vía Trigger | **PASÓ** |
| **6.1**| Creación de Evaluación | Registro vinculado a `instructor_curso` | **PASÓ** |
| **6.2**| Registro de Entrega | Estudiante envía tarea para la evaluación | **PASÓ** |
| **6.3**| Calificación y Feedback | Docente aprueba entrega (`calificarEntrega`) | **PASÓ** |
| **6.4**| Certificación Automática | Emisión de certificado activada por Trigger | **PASÓ** |
| **7** | Integridad Referencial | Bloqueo de borrado físico en curso con matrículas | **PASÓ** |

**Efectividad Comprobada:** 12 de 12 pruebas superadas con éxito (100%).
