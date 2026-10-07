# Notas de Desarrollo Front-End - DS Beauty Academy

Este documento explica la estructura y el funcionamiento de los módulos en el front-end. Todos los módulos (Bancos, Instructores, Estudiantes, Especialidades y Diplomas) siguen el mismo patrón arquitectónico para mantener la consistencia y facilitar el mantenimiento.

## 1. Estructura de Carpetas

Cada módulo se encuentra en `src/views/[nombre_modulo]/` y se organiza de la siguiente manera:

- `[nombre_modulo].php`: Archivo principal que orquestra la vista y contiene la lógica JavaScript.
- `components/`:
  - `[nombre_modulo]DataTable.php`: Estructura HTML de la tabla de datos.
  - `modales/`:
    - `[nombre_modulo]ModalCrear.php`: Formulario para nuevos registros.
    - `[nombre_modulo]ModalEditar.php`: Formulario para edición.
    - `[nombre_modulo]ModalDetalle.php`: Vista detallada con avatar.

## 2. Flujo de Funcionamiento (JavaScript)

La lógica se basa en AJAX para evitar recargas de página, utilizando la función `fetch`.

### Funciones Principales:

- **`buscarTodos()`**: Se ejecuta al cargar la página. Llama al controlador para obtener todos los registros activos y los pasa a `renderTable()`.
- **`apiRequest(url, data)`**: Función centralizada para realizar peticiones POST al servidor. Maneja errores de conexión y retorna la respuesta en formato JSON.
- **`renderTable(data)`**: Limpia el cuerpo de la tabla y genera dinámicamente las filas (`<tr>`) con los datos recibidos, incluyendo los botones de acción (Ver, Editar, Eliminar).

### Operaciones CRUD:

- **Guardar**: Recolecta los datos del formulario de creación usando `FormData`, añade el flag `guardar` y lo envía al controlador.
- **Editar**: Obtiene los datos de un registro específico por su ID y rellena los campos del formulario de edición.
- **Actualizar**: Envía los datos modificados al controlador con el flag `actualizar`.
- **Eliminar**: Solicita confirmación al usuario y envía el ID al controlador con el flag `eliminar` (realiza un borrado lógico cambiando el estatus a 'inactivo').

## 3. Estructura del Layout (Jerarquía Visual)

El sistema utiliza un diseño de "Sidebar Fijo" con contenido desplazable a la derecha. La estructura jerárquica en el HTML es:

1.  **`sidebar.php`**: Se posiciona de forma fija (`fixed`) a la izquierda. No ocupa espacio en el flujo normal del documento.
2.  **`main-wrapper` (Div)**: Es el contenedor principal de la derecha. Tiene un `margin-left` igual al ancho del sidebar para que el contenido no quede oculto detrás de este.
    - **`navbar.php`**: Contiene el encabezado superior (`topbar`) con las notificaciones y el perfil de usuario. Se abre el `main-wrapper` dentro de este archivo.
    - **`<main class="content">`**: El área donde se carga el contenido específico de cada módulo.
3.  **Cierre de `main-wrapper`**: Se debe cerrar manualmente al final de cada archivo de vista de módulo (después de la etiqueta `</main>`).

### Diagrama de Estructura:

```text
<body>
  ├── <aside class="sidebar"> ... </aside>
  └── <div class="main-wrapper">
        ├── <header class="topbar"> ... </header>
        └── <main class="content">
              ├── [Encabezado de Página]
              ├── [Tabla de Datos]
              └── [Modales]
        </main>
      </div>
</body>
```

## 4. Componentización de Vistas

Para mantener el código limpio, cada módulo divide su interfaz en componentes reutilizables dentro de la carpeta `components/`:

### A. Tabla de Datos (`DataTable.php`)

Contiene la estructura `<table>` con los encabezados correspondientes. El cuerpo (`<tbody>`) se deja vacío para ser llenado dinámicamente por JavaScript. Incluye también el campo de búsqueda (`<input type="text">`).

### B. Modales (`modales/`)

Cada operación tiene su propio archivo de modal para evitar archivos PHP gigantes:

- **Crear**: Contiene el formulario limpio.
- **Editar**: Contiene el formulario con IDs específicos para ser llenados vía JS.
- **Detalle**: Estructura visual para mostrar información extendida y el avatar del usuario.

## 5. Lógica de JavaScript y Estilos

- **Consistencia**: Todos los módulos usan las mismas clases CSS (`btn`, `btn-action`, `modal`, `detail-item`) definidas en `src/views/styles.css`.
- **Formateo**: Se utiliza `text-transform: capitalize` en CSS combinado con `.toLowerCase()` en JS para asegurar que los nombres propios se vean correctamente (Ej: "JUAN PEREZ" -> "Juan Perez").
- **Interactividad**: Los modales usan la clase `.show` para aparecer con una transición suave de opacidad.
