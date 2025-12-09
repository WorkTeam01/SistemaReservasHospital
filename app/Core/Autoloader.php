<?php

// Autoloader.php - Carga automática de clases PSR-4 (Simplificado)
spl_autoload_register(function ($class) {
    // Prefijo de namespace base
    $prefix = 'App\\';

    // Directorio base donde están las clases
    $base_dir = __DIR__ . '/../';

    // ¿La clase usa el prefijo?
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    // Obtener el nombre relativo de la clase
    $relative_class = substr($class, $len);

    // Reemplazar prefijo con directorio base y '\\' con '/'
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    // Si el archivo existe, requerirlo
    if (file_exists($file)) {
        require $file;
    }
});
