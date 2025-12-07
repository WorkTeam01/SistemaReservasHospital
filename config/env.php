<?php

/**
 * Carga las variables de entorno desde un archivo .env
 * @throws Exception
 */
function loadEnv($path): void
{
    if (!file_exists($path)) {
        throw new Exception(".env file not found. Please create one based on .env.example");
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {

        // Ignorar comentarios
        if (str_starts_with(trim($line), '#')) {
            continue;
        }

        // Dividir en nombre y valor
        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);

        // Eliminar comillas si existen
        if (!empty($value)) {
            $value = trim($value, '"');
            $value = trim($value, "'");
        }

        // Establecer la variable de entorno
        putenv("$name=$value");
        $_ENV[$name] = $value;
    }
}

// Cargar variables de entorno
try {
    loadEnv(__DIR__ . '/../.env');
} catch (Exception $e) {
    die('Error loading .env file: ' . $e->getMessage());
}

/**
 * Obtiene el valor de una variable de entorno
 * 
 * @param string $key Nombre de la variable
 * @param mixed|null $default Valor por defecto si la variable no existe
 * @return mixed
 */
function env(string $key, $default = null)
{
    $value = getenv($key);

    if ($value === false) {
        return $default;
    }

    // Manejar valores booleanos
    switch (strtolower($value)) {
        case 'true':
        case '(true)':
            return true;
        case 'false':
        case '(false)':
            return false;
        case 'null':
        case '(null)':
            return null;
        case 'empty':
        case '(empty)':
            return '';
        default:
            return $value;
    }
}
