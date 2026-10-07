Tras una auditoría minuciosa del código fuente, el esquema físico de base de datos (ds_beauty_academy_db.sql), la arquitectura MVC y la interfaz de usuario, te presento el diagnóstico
detallado del sistema DS Beauty Academy.
──────

### 📌 Resumen del Estado Actual

El sistema cuenta con un esquema relacional sólido en su versión 7.0 (con disparadores automáticos para aforos, prevención de doble matriculación y emisión de certificados), además de
componentes visuales con estética Dark Luxury / Glow Pink.

Sin embargo, a nivel de software se encuentra en un estado de MVP preliminar / prototipo incompleto: carece de capas esenciales de seguridad, contiene enlaces y controladores rotos, presenta
datos simulados en áreas críticas y no cuenta con las interfaces necesarias para que los estudiantes e instructores interactúen con la plataforma.
──────

### 1. Brechas Críticas de Seguridad y Autenticación (Lo más Urgente)

1. Inexistencia total de Autenticación y Manejo de Sesiones:
   • No existe session_start(), ni sistema de inicio/cierre de sesión (Login / Logout).
   • En la base de datos existen las tablas roles y usuarios, pero no tienen ningún modelo ni controlador en PHP.
   • El perfil de usuario en el encabezado (navbar.php:14-21) está cableado en código estático ("Dilenmar Sanchez / Administradora").
   • Riesgo: Cualquier usuario que conozca la URL tiene acceso irrestricto y de superadministrador a toda la información del sistema.
2. Cero Control de Acceso Basado en Roles (RBAC):
   • No existe middleware ni validación de permisos en el FrontController.php:30-45 que restrinja qué pantallas puede ver un Administrador, un Instructor o un Estudiante.
3. Vulnerabilidad a Cross-Site Scripting (XSS Almacenado):
   • En casi todas las vistas JavaScript (por ejemplo en estudiante.php:102-115, clase.php y diploma.php), los datos de la base de datos se concatenan directamente a tr.innerHTML sin
   sanitizar cadenas mediante textContent ni funciones de escape HTML.
4. Ausencia de Tokens CSRF:
   • Ninguno de los formularios en los modales ni las peticiones AJAX incluyen tokens anti-CSRF para validar el origen de las peticiones.
5. Exposición de Credenciales y Falta de Entorno (.env):
   • En configSystem.php:6-11, las credenciales de conexión (root, contraseña vacía, localhost) están escritas directamente en el repositorio. Falta soporte para variables de entorno (como
   vlucas/phpdotenv).

──────

### 2. Errores de Ruteo, Incompatibilidades y Funcionalidades Falsas

1. Rutas 404 en el Menú Principal:
   • Pagos: En el menú sidebar.php:119, el enlace apunta a ?url=pago. El FrontController.php:32 busca PagoController.php, pero el archivo en el disco se llama ComprobantePagoController.php.
   Como resultado, da un error 404.
   • Reportes: El enlace ?url=reporte (sidebar.php:128) no tiene ningún controlador (ReporteController.php no existe).
   • Configuración: El enlace ?url=configuracion (sidebar.php:149) tampoco tiene controlador (ConfiguracionController.php no existe).
2. Ruptura de la Arquitectura de Peticiones Asíncronas:
   • En matricula.php:66 y comprobante_pago.php:55, las funciones fetch llaman directamente a la ruta física del archivo (src/Controllers/MatriculaController.php) en lugar de pasar por el
   router unificado (?url=matricula). Al llamarse de forma aislada, fallan las rutas relativas de autocarga de Composer.
3. Incompatibilidad de Nombre de Columna en Recursos Didácticos:
   • En la base de datos la columna es nombre_recurso.
   • Sin embargo, en RecursoModel.php:18, RecursoController.php:10 y recursoModalCrear.php:11 se utiliza titulo_recurso.
   • Impacto: Cualquier intento de registrar o editar un recurso genera una excepción SQL por columna inexistente.
4. Botones y Formularios Inexistentes (Simulados):
   • En matricula.php:22, el botón "Nueva Matrícula" tiene únicamente un alert('Formulario de nueva matrícula'). No existe modal para inscribir estudiantes.
   • En comprobante_pago.php, no existe botón ni formulario para registrar comprobantes de pago; solo está la tabla de conciliación.

──────

### 3. Entidades Huérfanas y Lógica de Negocio Desconectada

1. Gestión de la Oferta Académica (instructor_curso):
   • Es la tabla pivote sobre la cual giran las clases, las matrículas y las evaluaciones.
   • Problema: No existe pantalla, modal ni controlador para asignar qué instructor dicta qué curso.
   • En los modales de creación de clases (claseModalCrear.php:26-28) y evaluaciones, se le pide al usuario que escriba manualmente un ID numérico (<input type="number"
   name="id_instructor_curso">), en vez de un <select> intuitivo que liste "Curso - Instructor".
2. Módulo de Entregas y Calificaciones (entregas_evaluaciones):
   • Existe el modelo EntregaEvaluacionModel.php, pero no existe controlador ni vistas.
   • Los estudiantes no pueden adjuntar sus asignaciones prácticas y los instructores no tienen pantalla para evaluarlas, colocarles nota ni darles feedback. Como consecuencia, el trigger
   trg_entregas_completadas (que emite el certificado al aprobar el 100% de evaluaciones) nunca se llega a disparar desde el frontend.
3. Tablas Muchos a Muchos sin Implementar:
   • especialidad_instructor: No hay interfaz para asignarle especialidades a un instructor.
   • recursos_curso: No hay vinculación de recursos didácticos a cursos específicos.

──────

### 4. Cero Manejo Real de Archivos Físicos (File Uploads)

En una academia de belleza se manejan comprobantes bancarios, fotos de prácticas de maquillaje/cejas, guías en PDF y certificados:

• En todos los formularios actuales, los campos de archivo son simples entradas de texto: <input type="text" name="url_archivo_recurso"> o <input type="text" name="url_img_comprobante">.
• Falta:
• Formularios con enctype="multipart/form-data".
• Capa de subida en PHP ($\_FILES) con validación de tipos MIME (ej. .pdf, .png, .jpg).
• Límite de tamaño de subida y saneamiento de nombres de archivos.
• Directorio organizado de almacenamiento físico (ej. /uploads/comprobantes/, /uploads/tareas/).

──────

### 5. Generación de Certificados y Diplomas en PDF

• La base de datos guarda url_pdf_certificado y url_pdf_diploma.
• Falta:
• Integración de una librería de generación de documentos PDF (como Dompdf, FPDF o TCPDF).
• Plantilla visual de diploma/certificado con logotipo de la academia, nombre del graduado, fecha, firma de la instructora y código QR o serial de verificación único.

──────

### 6. Datos Simulados en el Dashboard

• En DashboardModel.php:13-100, todos los métodos (getStatistics, getRecentEnrollments, getUpcomingPayments) retornan arrays con datos estáticos falsos ("Alek Santana", años 2022-2023, $12,
450.00).
• Falta: Conectar el Dashboard a consultas reales (SELECT COUNT(\*), SELECT SUM(monto), etc.) para reflejar ingresos, cupos y matrículas en tiempo real.
──────

### 7. Portales para Estudiantes e Instructores

El sistema actual está concebido únicamente como un panel administrativo interno. Para una academia de belleza real hace falta:

1. Catálogo Público / Landing Page: Donde las alumnas puedan ver la oferta de Masterclasses y cursos completos, fecha de preventa, temarios y cupos disponibles.
2. Portal del Estudiante:
   • Ver sus cursos matriculados y el reproductor de clases en video.
   • Descargar los recursos didácticos (listas de insumos, guías).
   • Enviar las entregas de sus evaluaciones (fotos de trabajos en modelos).
   • Descargar sus certificados aprobados.
3. Portal del Instructor:
   • Panel donde la profesora ve únicamente los cursos que tiene asignados, su lista de alumnas inscritas y las tareas pendientes por calificar.

──────

### 8. Notificaciones y Servicios Externos

• Servicio de Correo Electrónico (PHPMailer):
• No existe notificación por correo cuando se matricula una estudiante, cuando su pago es aprobado/rechazado, o cuando se emite su certificado digital.
• Auditoría del Sistema:
• Falta registro de logs de auditoría (quién aprobó qué pago, en qué fecha y desde qué dirección IP).

──────

### 📋 Plan de Acción Recomendado (Roadmap de Implementación)

| Fase                                | Prioridad                                                                                             | Acciones Clave                                                                                                                              |
| ----------------------------------- | ----------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------- |
| Fase 1: Estabilización y Corrección | 🔴 Alta                                                                                               | • Corregir ruta de pagos PagoController.php y enlaces directos de fetch.• Renombrar titulo_recurso a nombre_recurso en el                   |
|                                     | modelo/controlador/modal.• Reemplazar datos simulados de DashboardModel.php con consultas SQL reales. |
| Fase 2: Seguridad y Autenticación   | 🔴 Alta                                                                                               | • Crear UsuarioModel, UsuarioController y vista de Login.• Implementar manejo seguro de sesiones en PHP y protección contra CSRF.• Escapar  |
|                                     | variables en JavaScript para neutralizar riesgos XSS.                                                 |
| Fase 3: Flujo Académico Completo    | 🟡 Media                                                                                              | • Crear interfaz para asignar instructores a cursos (instructor_curso).• Crear modal funcional para inscripción de matrículas.• Implementar |
|                                     | EntregaEvaluacionController y la vista para calificar entregas de evaluaciones.                       |
| Fase 4: Almacenamiento y Documentos | 🟡 Media                                                                                              | • Implementar subida física de archivos ($\_FILES) para comprobantes y tareas.• Integrar Dompdf o FPDF para emitir diplomas y certificados  |
|                                     | con diseño formal.                                                                                    |
| Fase 5: Notificaciones y Portales   | 🟢 Baja                                                                                               | • Integrar PHPMailer para alertas por correo.• Separar las vistas según el rol (Admin, Alumna e Instructora).                               |
