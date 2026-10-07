# Notas de Desarrollo: Arquitectura Backend (Modelos y Controladores)

Este documento recopila las decisiones arquitectónicas, patrones de diseño y soluciones implementadas durante el desarrollo de los módulos del backend (Fases 1 a 4) de **DS Beauty Academy**.

## 1. Arquitectura Base y Patrones Implementados

Se ha establecido una arquitectura fuertemente modular y orientada a objetos para el acceso a datos y la lógica de negocio, buscando la reutilización de código (DRY) y el Principio de Responsabilidad Única (SRP).

- **Traits (`ApiResponse` y `Validations`):** 
  En lugar de heredar métodos de respuesta HTTP o validación en cada modelo, se utilizaron Traits. Esto permite que cualquier modelo (o incluso controlador en el futuro) pueda inyectar estas capacidades sin forzar una jerarquía de herencia estricta.
- **Interfaces (`Crud`):** 
  Se implementó la interfaz `Crud` (`guardar`, `buscarTodos`, `buscar`, `actualizar`, `eliminar`) para obligar a todos los modelos a mantener una firma consistente. Esto facilita la escalabilidad y predictibilidad del código.
- **Encapsulamiento y Reflexión (Setters Dinámicos):** 
  Todos los modelos implementan el método privado `validarYSetearDatos($data)`. Este método mapea automáticamente las llaves de un arreglo asociativo (ej. `$_POST`) hacia su setter correspondiente (ej. `titulo_clase` -> `setTituloClase()`). 
  - *Ventaja:* El método `guardar` y `actualizar` no necesita saber qué campos existen, simplemente itera sobre las llaves, valida con el setter y arma la consulta SQL dinámicamente.

## 2. Sistema de Validación Centralizado

Toda la lógica de validación fue extraída de los modelos y colocada en el Trait `Validations.php`.
- El método `validator($value, $rule)` acepta tanto Expresiones Regulares (Regex) como Arreglos (Whitelists).
- **Enums y Whitelists:** Para campos en la base de datos que son de tipo `ENUM` (ej. `'Pendiente', 'Aprobado', 'Rechazado'`), la validación en PHP se hace contra un arreglo de opciones permitidas.
- Al aislar la validación, los setters dentro de los modelos solo se encargan de arrojar una `Exception` si el `validator` devuelve `false`.

## 3. Decisiones Críticas en Modelos y Base de Datos

### A. Soft Delete vs Hard Delete (Eliminación Lógica vs Física)
Se tomó la decisión de alinear las funciones de eliminación (`eliminar()`) según la naturaleza del dato dictada por la base de datos:
- **Soft Delete (Lógico):** Utilizado en entidades que tienen historiales, pagos, o dependencias fuertes.
  - *Ejemplos:* `Clases`, `Evaluaciones` (modifican `estatus = 0`), `Matriculas` (`estatus_cupo = 'Cancelado'`), `Certificados` (`estatus_certificado = 'Anulado'`), `ComprobantePagos` (`estatus_comprobante = 'Rechazado'`).
- **Hard Delete (Físico):** Utilizado en catálogos simples o relaciones donde no se requiere mantener el rastro.
  - *Ejemplos:* `Etiquetas`, `CertificadoInstructor`, `Recursos`, y las tablas pivote.
- *Nota sobre seguridad:* En modelos con Hard Delete como `Cursos`, la base de datos cuenta con *Triggers* (ej. `trg_cursos_no_delete_con_matriculas`) que impiden a nivel de motor SQL el borrado si hay alumnos inscritos. El modelo de PHP simplemente capturará la excepción SQL y devolverá un error 500 controlado al usuario.

### B. Manejo de Tablas Pivote Complejas (N:M)
Tablas como `especialidad_instructor` o `recursos_curso` no cuentan con una clave primaria autoincremental simple (tienen Primary Keys compuestas). 
- **Solución implementada:** Se adaptaron los Controladores y Modelos para recibir **arreglos de IDs** (ej. un instructor se asocia a múltiples IDs de especialidad a la vez).
- **Transaccionalidad:** En el método `actualizar` de estas tablas pivote, se implementó `$this->con->beginTransaction()`. El algoritmo primero realiza un `DELETE FROM` de todas las asignaciones anteriores de ese instructor/curso, y luego inserta las nuevas dentro de un bucle. Si alguna inserción falla (ej. ID no válido), se ejecuta un `$this->con->rollBack()`, asegurando la integridad de los datos.

### C. Lógica de Negocio en Entidades (Ej. Cursos)
El método `validarYSetearDatos` permite inyectar lógica de negocio específica antes de la inserción. 
- Por ejemplo, en el modelo `Cursos`, si viene un precio de preventa, a nivel de código se validó que `precio_preventa < precio_normal` y que la `fecha_fin_preventa` no sobrepase el inicio del curso. Esto emula y respalda los `CHECK` integrados en el esquema de la base de datos.

## 4. Estructura de Controladores

Todos los controladores siguen el mismo flujo de vida (Single Request Cycle):
1. Definen un `$module_config` en la parte superior que mapea la Llave Primaria, los Campos esperados de la DB y la vista (`view_path`).
2. Tienen un ruteo interno (Router Básico) basado en comprobar si existe una variable global en `$_POST` (ej. `isset($_POST['guardar'])`).
3. Retornan obligatoriamente JSON para las acciones CRUD (`header('Content-Type: application/json')`) y detienen la ejecución con `exit;`.
4. Si ninguna acción POST se desencadena, asumen que es una petición GET y proceden a hacer un `include` de la vista correspondiente, terminando la ejecución con `die()`.

---
**Conclusión:**
La base del proyecto es ahora altamente cohesiva. Si en el futuro se necesita añadir un nuevo módulo (ej. un Blog o un sistema de Foros), el desarrollador solo debe:
1. Copiar y pegar cualquier Controlador y Modelo existente.
2. Actualizar las propiedades del modelo (`$table`, `$idField`, variables privadas y Setters).
3. Actualizar la configuración `$module_config` en el controlador.
4. El CRUD base funcionará de forma inmediata gracias al mapeo dinámico.
