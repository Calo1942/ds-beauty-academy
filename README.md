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

# Principios SOLID: Fundamentos del Diseño Orientado a Objetos

Los principios SOLID son cinco reglas fundamentales del diseño y la programación orientada a objetos. Sirven para escribir código limpio, fácil de mantener y escalable, evitando que el software se vuelva complejo y rígido a medida que crece.

### Conoce los cinco conceptos básicos en los que se dividen
1. **Responsabilidad Única (SRP):**
Una clase o módulo solo debe tener una razón para cambiar. Es decir, debe realizar una única tarea específica para no mezclar funcionalidades.
2. **Abierto / Cerrado (OCP)**
Las entidades de software deben estar abiertas a extensión, pero cerradas a modificación. Esto permite añadir nuevas funcionalidades sin alterar el código base existente.
3. **Sustitución de Liskov (LSP)**
Los objetos de un programa deben ser reemplazables por objetos de sus subclases sin alterar el funcionamiento del programa. Las clases hijas no deben romper el comportamiento de la clase padre.
4. **Segregación de Interfaces (ISP)**
Es mejor tener muchas interfaces específicas que una sola interfaz de propósito general. Las clases no deben estar obligadas a implementar métodos que no utilizan.
5. **Inversión de Dependencias (DIP)**
Los módulos de alto nivel no deben depender de módulos de bajo nivel. Ambos deben depender de abstracciones (como las interfaces) para reducir el acoplamiento entre clases.