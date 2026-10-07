# Auditoría y Guía Técnica del Front-End
**Proyecto:** DS Beauty Academy  
**Directorio:** `src/views`  
**Estilo Visual:** Dark Luxury / Glow Pink (`--accent: #E83382`, `--bg-dark: #0f0f11`)  
**Fecha:** 28 de Septiembre de 2026  

---

## 1. Resumen Ejecutivo del Front-End

Se completó una auditoría integral de la interfaz de usuario, arquitectura de componentes, diseño responsive y estilos CSS en **DS Beauty Academy**. 

Se identificaron y corrigieron:
1. **Vistas incompletas o inexistentes:** Archivos modales faltantes que arrojaban advertencias PHP en tiempo de ejecución.
2. **Ausencia total de diseño responsive:** El archivo `styles.css` carecía de reglas `@media`, provocando que en dispositivos móviles y tablets el sidebar fijo de 280px aplastara el contenido.
3. **Manejo de excepciones 404:** Se creó una vista elegante para rutas en construcción o controladores no definidos.
4. **Preparación técnica para DataTables y SweetAlert2:** Se trazó la ruta de migración para evitar colisiones con el JavaScript actual.

---

## 2. Mapa Completo de Vistas y Componentes

Tras las implementaciones realizadas, el catálogo de vistas queda estructurado de la siguiente forma:

| Módulo | Ruta URL | Vista Principal | Componentes DataTable | Modales Incluidos | Estado |
| :--- | :--- | :--- | :--- | :--- | :---: |
| **Dashboard** | `?url=dashboard` | `dashboard/dashboard.php` | Métricas y KPIs | N/A | **Completo** |
| **Cursos** | `?url=curso` | `curso/curso.php` | `cursoDataTable.php` | Crear, Editar, Detalle | **Completo** |
| **Instructores** | `?url=instructor` | `instructor/instructor.php` | `instructorDataTable.php` | Crear, Editar, Detalle | **Completo** |
| **Estudiantes** | `?url=estudiante` | `estudiante/estudiante.php` | `estudianteDataTable.php` | Crear, Editar, Detalle | **Completo** |
| **Bancos** | `?url=banco` | `banco/banco.php` | `bancoDataTable.php` | Crear, Editar, Detalle | **Completo** |
| **Clases** | `?url=clase` | `clase/clase.php` | `claseDataTable.php` | Crear, Editar, Detalle | **Completo** |
| **Diplomas** | `?url=diploma` | `diploma/diploma.php` | `diplomaDataTable.php` | Crear, Editar, Detalle | **Completo** |
| **Especialidades** | `?url=especialidad` | `especialidad/especialidad.php` | `especialidadesDataTable.php` | Crear, Editar, Detalle | **Completo** |
| **Recursos** | `?url=recurso` | `recurso/recurso.php` | `recursoDataTable.php` | `recursoModalCrear`, `recursoModalEditar`, `recursoModalDetalle` | **Completado** ✅ |
| **Evaluaciones** | `?url=evaluacion` | `evaluacion/evaluacion.php` | `evaluacionDataTable.php` | `evaluacionModalCrear`, `evaluacionModalEditar`, `evaluacionModalDetalle` | **Creado** ✅ |
| **Certificados** | `?url=certificado` | `certificado/certificado.php`| `certificadoTable` | Vista y descarga PDF | **Creado** ✅ |
| **Etiquetas** | `?url=etiqueta` | `etiqueta/etiqueta.php` | `etiquetaTable` | `modalCrear` | **Creado** ✅ |
| **Matrículas** | `?url=matricula` | `matricula/matricula.php` | `matriculaTable` | Cancelación y badges | **Creado** ✅ |
| **Pagos** | `?url=pago` | `pago/comprobante_pago.php` | `pagoTable` | Conciliación inmediata | **Creado** ✅ |
| **En Desarrollo / 404** | Cualquier ruta rota | `error/404.php` | N/A | Diseño estético temático | **Creado** ✅ |

---

## 3. Implementación del Sistema Responsive y Media Queries

Se inyectaron reglas CSS adaptativas en [`src/views/styles.css`](file:///C:/xampp/htdocs/un-carlos/ds-beauty-academy-main/src/views/styles.css) y se adaptó [`src/views/layouts/navbar.php`](file:///C:/xampp/htdocs/un-carlos/ds-beauty-academy-main/src/views/layouts/navbar.php):

### A. Botón Toggle y Sidebar Off-Canvas para Móviles
* **En el Navbar:** Se incorporó `<button class="btn-sidebar-toggle" id="btnSidebarToggle">` y `<div class="sidebar-overlay" id="sidebarOverlay">`.
* **Comportamiento:** En pantallas menores a `992px`, el sidebar se oculta automáticamente hacia la izquierda (`transform: translateX(-100%)`). Al pulsar el botón hamburguesa, se despliega fluidamente con animación cubic-bezier y se activa un backdrop semitransparente con efecto blur.

### B. Breakpoints Definidos:
1. **Laptops y Tablets (`@media (max-width: 992px)`):**
   - El margen izquierdo forzado `.main-wrapper { margin-left: 0 !important; width: 100% !important; }` libera todo el ancho de pantalla para la aplicación.
   - Los encabezados de página (`.page-header`) se apilan verticalmente para que los botones de acción no colisionen con los títulos.
2. **Móviles y Phablets (`@media (max-width: 768px)`):**
   - Modales adaptados: ancho del `95%` con margen de seguridad.
   - Se añadió `max-height: 90vh` en `.modal-content` y `overflow-y: auto; max-height: calc(90vh - 140px);` en `.modal-body`. Los formularios largos ya no se cortan ni impiden pulsar el botón "Guardar".
   - Tarjetas de estadísticas y cuadrículas reorganizadas a 1 columna única (`1fr`).
3. **Móviles Pequeños (`@media (max-width: 576px)`):**
   - Ocultamiento de textos no esenciales en el topbar (como el rol del usuario) para evitar desbordes horizontales.
   - Botones del footer de modales apilados verticalmente con ancho del 100%.

### C. Desplazamiento Horizontal en Tablas (`.table-container`)
Todas las tablas cuentan con `overflow-x: auto; -webkit-overflow-scrolling: touch;` y una anchura mínima (`min-width: 650px`), permitiendo deslizar las columnas con el dedo en pantallas táctiles sin que la página web entera sufra descuadres.

---

## 4. Vista de Manejo de Excepciones: "404 Estamos trabajando en ello"

* **Archivo Creado:** [`src/views/error/404.php`](file:///C:/xampp/htdocs/un-carlos/ds-beauty-academy-main/src/views/error/404.php)
* **Integración en el Router:** Modificado [`src/Controllers/FrontController.php`](file:///C:/xampp/htdocs/un-carlos/ds-beauty-academy-main/src/Controllers/FrontController.php):
  ```php
  if (file_exists($controllerFile)) {
      require $controllerFile;
  } else {
      http_response_code(404);
      require __DIR__ . '/../views/error/404.php';
      exit;
  }
  ```
* **Diseño:** Mantiene la coherencia visual del proyecto:
  - Icono flotante con aura de neón rosa animada (`pulseGlow`).
  - Badge `"Módulo en Construcción"`.
  - Título `"404 - Estamos trabajando en ello"`.
  - Explicación estética y botón con retorno directo al Dashboard.

---

## 5. Guía de Preparación e Integración de SweetAlert2 y DataTables

### 5.1. Migración a SweetAlert2

Actualmente las vistas usan funciones bloqueantes nativas:
```javascript
// Actual (Obsoleto)
if (!confirm('¿Está seguro de eliminar?')) return;
alert(result.message);
```

#### Paso 1: Importar SweetAlert2 en el Layout
Agregar en el `<head>` o antes del cierre de `<body>` en las vistas:
```html
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
```

#### Paso 2: Crear Helper Global de Estilos (`src/views/layouts/alerts.js`)
Configurar el tema oscuro acorde a DS Beauty Academy:
```javascript
const SwalTheme = Swal.mixin({
    background: '#1c1c1e',
    color: '#ffffff',
    confirmButtonColor: '#E83382',
    cancelButtonColor: '#2a2a2c',
    customClass: {
        popup: 'border-luxury'
    }
});

// Toast rápido para respuestas exitosas
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    background: '#1c1c1e',
    color: '#ffffff'
});
```

#### Paso 3: Reemplazo en las funciones CRUD
```javascript
// Diálogo de Confirmación Asíncrono
async function eliminarEstudiante(id) {
    const res = await SwalTheme.fire({
        title: '¿Eliminar estudiante?',
        text: 'Esta acción cambiará el estatus a inactivo.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    });

    if (res.isConfirmed) {
        const formData = new FormData();
        formData.append('eliminar', id);
        const result = await apiRequest(formData);
        
        if (result.code === 200) {
            Toast.fire({ icon: 'success', title: result.message });
            buscarTodos();
        } else {
            SwalTheme.fire('Error', result.message, 'error');
        }
    }
}
```

---

### 5.2. Migración a DataTables

Actualmente cada vista tiene un script de búsqueda manual que itera los `<tr>`:
```javascript
// Actual (Incompatible con DataTables)
document.getElementById('searchX').addEventListener('input', ...);
function renderTable(data) { tbody.innerHTML = ...; }
```

#### Paso 1: Importar DataTables (Tema Bootstrap 5 o CSS Nativo)
```html
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
```

#### Paso 2: Adaptar el CSS para Modo Oscuro
DataTables por defecto aplica fondos claros. Se deben mapear las variables en `styles.css`:
```css
.dataTables_wrapper {
    color: var(--text-main);
    padding: 16px 0;
}
.dataTables_wrapper .dataTables_length select,
.dataTables_wrapper .dataTables_filter input {
    background-color: #1e1e20;
    border: 1px solid var(--border-color);
    color: white;
    border-radius: 8px;
    padding: 6px 12px;
}
.dataTables_wrapper .dataTables_paginate .paginate_button {
    color: var(--text-muted) !important;
    border-radius: 6px;
}
.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background: var(--accent) !important;
    color: white !important;
    border: none;
}
```

#### Paso 3: Inicialización en JavaScript
En lugar de reconstruir manualmente el `innerHTML`, se destruye o limpia la instancia de DataTables:
```javascript
let dataTableInstance = null;

function renderTable(data) {
    if (dataTableInstance) {
        dataTableInstance.destroy();
    }

    const tbody = document.querySelector('#miTabla tbody');
    tbody.innerHTML = '';

    data.forEach(item => {
        // poblar filas ...
    });

    dataTableInstance = $('#miTabla').DataTable({
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
        },
        responsive: true,
        pageLength: 10
    });
}
```
*Con este cambio, se remueve el input de búsqueda manual en la cabecera porque DataTables incluye su propio buscador instantáneo con ordenamiento multicomponente.*
