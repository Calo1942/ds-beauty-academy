<?php

// Directorio fuente src del proyecto (convención utilizada para incluir vistas: __ROOT__ . '/views/...')
define('__ROOT__', __DIR__ . "/../..");

// Directorio raíz del proyecto (donde reside .env, vendor, etc.)
define('__PROJECT_ROOT__', realpath(__DIR__ . "/../../..") ?: dirname(__DIR__, 3));

/**
 * Cargador ligero y nativo de variables de entorno (.env)
 * Carga las variables definidas en la raíz del proyecto sin requerir dependencias externas.
 */
if (!function_exists('cargarVariablesEntorno')) {
    function cargarVariablesEntorno(string $ruta): void
    {
        if (!is_file($ruta) || !is_readable($ruta)) {
            return;
        }

        $lineas = file($ruta, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if ($lineas === false) {
            return;
        }

        foreach ($lineas as $linea) {
            $linea = trim($linea);

            // Ignorar líneas vacías y comentarios
            if ($linea === '' || strpos($linea, '#') === 0) {
                continue;
            }

            if (strpos($linea, '=') === false) {
                continue;
            }

            [$clave, $valor] = explode('=', $linea, 2);
            $clave = trim($clave);
            $valor = trim($valor);

            // Remover comillas envolventes dobles o simples
            if (
                (strpos($valor, '"') === 0 && substr($valor, -1) === '"') ||
                (strpos($valor, "'") === 0 && substr($valor, -1) === "'")
            ) {
                $valor = substr($valor, 1, -1);
            }

            if (!isset($_SERVER[$clave])) {
                $_SERVER[$clave] = $valor;
            }
            if (!isset($_ENV[$clave])) {
                $_ENV[$clave] = $valor;
            }
            putenv("{$clave}={$valor}");
        }
    }
}

/**
 * Función global auxiliar para obtener valores del entorno con valor por defecto.
 */
if (!function_exists('env')) {
    function env(string $clave, $defecto = null)
    {
        $valor = getenv($clave);

        if ($valor === false) {
            $valor = $_ENV[$clave] ?? $_SERVER[$clave] ?? $defecto;
        }

        if ($valor === null) {
            return $defecto;
        }

        switch (strtolower((string) $valor)) {
            case 'true':
            case '(true)':
                return true;
            case 'false':
            case '(false)':
                return false;
            case 'empty':
            case '(empty)':
                return '';
            case 'null':
            case '(null)':
                return null;
        }

        return $valor;
    }
}

// Cargar automáticamente el archivo .env si existe en la raíz
cargarVariablesEntorno(__PROJECT_ROOT__ . '/.env');

// Parámetros de conexión a la base de datos (con fallback a valores por defecto)
define('__LOCAL__', env('DB_HOST', 'localhost'));
define('__DB_NAME__', env('DB_NAME', 'ds_beauty_academy_db'));
define('__USER__', env('DB_USER', 'root'));
define('__PASS__', env('DB_PASS', ''));
define('__CHARSET__', 'utf8mb4');
