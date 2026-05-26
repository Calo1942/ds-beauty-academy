# Documentación de desarrollo para proyecto de DS Beauty Academy

## Convenciones del Proyecto

- Los métodos deben tener nombres en español para mayor claridad
- Los nombres de lo relacionado con la lógica del negocio (Video, Curso, Estudiante, Etc) se escribirán en español.
- Las partes más técnicas del sistema serán escritas en inglés

---

## Convenciones de repuestas del Back-End:

Formato petición exitosa

```json
// respuesta exitosa
{
  "status": "success",
  "code": 200,
  "message": "Dato creado exitosamente",
  "data": ""
}
```

Formato error en la petición

```json
// respuesta error
{
  "status": "error",
  "code": 404,
  "message": "Dato no encontrado",
  "data": ""
}
```

## Guia para uso de Git

### 🔤 Tipos de Prefijos

| Prefijo    | Descripción                                                                   |
| ---------- | ----------------------------------------------------------------------------- |
| `feat`     | Agrega una nueva característica o funcionalidad al proyecto.                  |
| `fix`      | Corrige un error o bug existente.                                             |
| `docs`     | Cambios relacionados con la documentación (README, comentarios, etc.).        |
| `style`    | Cambios de formato o estilo que no afectan la lógica (indentación, espacios). |
| `refactor` | Reestructuración del código sin modificar su comportamiento.                  |
| `test`     | Agrega o modifica pruebas automatizadas.                                      |
| `chore`    | Tareas de mantenimiento que no afectan el código de producción.               |
| `build`    | Cambios en el sistema de compilación o dependencias.                          |

### 📝 Ejemplos de Mensajes de Commit

- `feat: agregar componente de búsqueda`
- `fix: corregir error en validación de formulario`
- `style: aplicar formato con Prettier`
- `refactor: simplificar lógica de autenticación`
- `docs: actualizar guía de instalación`
- `test: agregar pruebas para el componente de login`
- `chore: actualizar dependencias del proyecto`
- `build: configurar Webpack para producción`

### 📌 Notas:

- Usar el **modo imperativo** en el resumen (ej. "agregar", no "agregado").
- Mantener el resumen **conciso y claro** (máximo 50 caracteres).
- Si el cambio requiere explicación adicional, agregarla en el cuerpo del mensaje.
- Evitar commits genéricos como "cambios varios" o "actualización".
