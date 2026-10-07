# Guía Técnica: Creación del Modelo bajo la Arquitectura MVC en PHP (PDO)

Esta guía detalla el estándar de desarrollo para la creación de **Modelos** en el backend, basado estrictamente en el flujo de trabajo asíncrono (PHP + PDO + JavaScript/Ajax) explicado en clase. El enfoque principal es garantizar la separación de responsabilidades, la seguridad contra inyecciones SQL y el encapsulamiento correcto para el diagrama de clases.

---

## 1. Arquitectura y Reglas de Encapsulamiento

Para cumplir con los estándares académicos y de desarrollo profesional, se aplican dos reglas de oro en la visibilidad de los métodos dentro del Modelo:

* **Métodos Públicos (`+`):** Funciones que **no modifican** la base de datos (con contadas excepciones). Ejemplos: `validarDatos()` y `consultarDatos()`.
* **Métodos Privados (`-`):** Todas las funciones que **modifican** la base de datos de manera directa. Ejemplos: `registrarDatos()`, `actualizarDatos()` y `eliminarDatos()`. Para acceder a ellas desde el controlador, se deben crear métodos puente públicos (patrón Get/Set).

---

## 2. Estructura Base del Modelo (`UsuarioModelo.php`)

El modelo debe iniciar con la definición de sus atributos privados (que coinciden con los campos del formulario y la tabla de la base de datos) y la inclusión de la clase de conexión.

```php
<?php
require_once "Conexion.php";

class UsuarioModelo extends Conexion {
    // Atributos privados para el encapsulamiento
    private $cedula;
    private $nombre;
    private $apellido;
    private $correo;
    private $telefono;
    
    // El constructor hereda la conexión a la base de datos
    public function __construct() {
        parent::__construct();
    }
}
?>

```

---

## 3. Pasos y Condiciones Obligatorias

### Paso 1: La Capa de Validación y Encapsulamiento (`validarDatos`)

Antes de interactuar con la base de datos, el modelo debe verificar la integridad de la información mediante expresiones regulares (`preg_match`). Este método actúa como un "Setter gigante".

* **Condición:** Si una expresión regular falla, el método debe retornar inmediatamente un `string` descriptivo con el error.
* **Condición:** Si todos los datos pasan la validación, se encapsulan en los atributos privados de la clase usando `$this->` y retorna `true`.

```php
public function validarDatos($cedula, $nombre, $apellido, $correo, $telefono) {
    // Definición de expresiones regulares (RegEx)
    $exp_cedula   = "/^[0-9]{7,8}$/";
    $exp_nombre   = "/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,30}$/";
    $exp_apellido = "/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,30}$/";
    $exp_correo   = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,}$/";
    $exp_telefono = "/^(0414|0424|0412|0416|0251)[0-9]{7}$/";

    // Validaciones jerárquicas
    if (!preg_match($exp_cedula, $cedula)) {
        return "error en la cédula";
    }
    if (!preg_match($exp_nombre, $nombre)) {
        return "error en el nombre";
    }
    if (!preg_match($exp_apellido, $apellido)) {
        return "error en el apellido";
    }
    if (!preg_match($exp_correo, $correo)) {
        return "error en el correo";
    }
    if (!preg_match($exp_telefono, $telefono)) {
        return "error en el teléfono";
    }

    // Encapsulamiento si todo está correcto
    $this->cedula   = $cedula;
    $this->nombre   = $nombre;
    $this->apellido = $apellido;
    $this->correo   = $correo;
    $this->telefono = $telefono;

    return true;
}

```

### Paso 2: El Método de Modificación Privado (`registrarDatos`)

Este método ejecuta la sentencia SQL de inserción utilizando marcadores de posición para evitar inyecciones SQL.

* **Condición Obligatoria:** Todo método que interactúe con la base de datos debe estar envuelto en un bloque `try-catch` capturando `PDOException`.
* **Preparación Segura:** Se pueden usar signos de interrogación (`?`) o marcadores por nombre (`:parametro`). En este estándar se priorizan los marcadores por nombre para mayor claridad de código.

```php
private function registrarDatos() {
    try {
        $sql = "INSERT INTO usuarios (cedula, nombre, apellido, correo, telefono) 
                VALUES (:cedula, :nombre, :apellido, :correo, :telefono)";
        
        $stmt = $this->con->prepare($sql);
        
        // Vinculación segura de datos encapsulados
        $stmt->bindValue(':cedula', $this->cedula);
        $stmt->bindValue(':nombre', $this->nombre);
        $stmt->bindValue(':apellido', $this->apellido);
        $stmt->bindValue(':correo', $this->correo);
        $stmt->bindValue(':telefono', $this->telefono);
        
        $stmt->execute();
        return "registro realizado";
        
    } catch (PDOException $e) {
        return "Error en la base de datos: " . $e->getMessage();
    }
}

```

### Paso 3: El Método Puente Público (`getRegistrarDatos`)

Para que el Controlador pueda ejecutar la inserción sin romper el encapsulamiento privado, se define una función pública intermedia.

* **Condición:** No debe recibir parámetros, ya que trabaja directamente con los atributos internos que el paso de validación ya guardó.

```php
public function getRegistrarDatos() {
    return $this->registrarDatos();
}

```

---

## 4. Métodos para el CRUD Completo (Actualizar y Eliminar)

Siguiendo exactamente la misma lógica de diseño, se estructuran las funciones de actualización y desincorporación del sistema.

### Modificación / Actualización de Datos

```php
private function actualizarDatos() {
    try {
        $sql = "UPDATE usuarios SET nombre = :nombre, apellido = :apellido, 
                correo = :correo, telefono = :telefono WHERE cedula = :cedula";
                
        $stmt = $this->con->prepare($sql);
        
        $stmt->bindValue(':nombre', $this->nombre);
        $stmt->bindValue(':apellido', $this->apellido);
        $stmt->bindValue(':correo', $this->correo);
        $stmt->bindValue(':telefono', $this->telefono);
        $stmt->bindValue(':cedula', $this->cedula); // Llave primaria para el WHERE
        
        $stmt->execute();
        return "modificación realizada";
    } catch (PDOException $e) {
        return "Error al actualizar: " . $e->getMessage();
    }
}

public function getActualizarDatos() {
    return $this->actualizarDatos();
}

```

### Eliminación Lógica

* **Condición Profesional:** No se utiliza `DELETE FROM` (eliminación física o Drop). En su lugar se realizan **eliminaciones lógicas**, modificando un estado o estatus dentro de la tabla del usuario para conservar la integridad referencial de los datos históricos.

```php
private function eliminarDatos() {
    try {
        // Se asume la existencia de una columna 'estatus' o 'activo' (1 = Activo, 0 = Inactivo)
        $sql = "UPDATE usuarios SET estatus = 0 WHERE cedula = :cedula";
        
        $stmt = $this->con->prepare($sql);
        $stmt->bindValue(':cedula', $this->cedula);
        
        $stmt->execute();
        return "eliminación realizada";
    } catch (PDOException $e) {
        return "Error al eliminar: " . $e->getMessage();
    }
}

public function getEliminarDatos() {
    return $this->eliminarDatos();
}

```

---

## 5. Integración con el Controlador (Flujo de Ejecución)

El controlador (`UsuarioController.php`) procesa de manera secuencial y ordenada las respuestas del modelo tras recibir la petición asíncrona (Ajax) de la vista:

```php
<?php
require_once "UsuarioModelo.php";

if (!empty($_POST)) {
    // 1. Instanciar el modelo
    $usuario = new UsuarioModelo();
    
    // 2. Ejecutar la capa de validación pasándole los datos globales de $_POST
    $validacion = $usuario->validarDatos(
        $_POST['cedula'],
        $_POST['nombre'],
        $_POST['apellido'],
        $_POST['correo'],
        $_POST['telefono']
    );
    
    // 3. Evaluar respuesta del validador
    if ($validacion !== true) {
        // Si no devuelve true, significa que retornó un string de error.
        echo json_encode(["status" => "error", "message" => $validacion]);
        die(); // Se detiene la ejecución inmediatamente
    }
    
    // 4. Si la validación fue exitosa, procedemos al registro seguro
    $resultado = $usuario->getRegistrarDatos();
    
    // 5. Responder en formato JSON para que el JavaScript del Frontend lo procese
    echo json_encode(["status" => "success", "message" => $resultado]);
    die();
}
?>

```

"""

with open("Guia_Creacion_Modelo_MVC.md", "w", encoding="utf-8") as f:
f.write(markdown_content)

```
El archivo `.md` detallado con todos los pasos, condiciones y la estructura de código limpia para hacer el **Modelo** ha sido generado exitosamente de acuerdo con las metodologías explicadas en clase (PHP + PDO + encapsulamiento estricto para Diagrama de Clases).

El documento incluye:
1. **Reglas de encapsulamiento** (Métodos públicos `+` vs privados `-`).
2. **Estructura Base** del modelo con atributos protegidos.
3. **Paso 1: Capa de Validación Jerárquica** con expresiones regulares (`validarDatos`).
4. **Paso 2: Sentencias SQL Preparadas** con bloques `try-catch` y `bindValue` para mitigar inyecciones de código SQL.
5. **Paso 3 y CRUD Completo:** Estructura de los métodos puente junto a la lógica profesional de **Eliminación Lógica** (sin usar `DELETE`).
6. **Mapeo en el Controlador:** El flujo lógico de cómo el controlador invoca y evalúa cada capa del modelo.

[file-tag: code-generated-file-0-1783564221071377022]

```