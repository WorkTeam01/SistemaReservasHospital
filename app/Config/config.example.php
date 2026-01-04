<?php

// Definir la URL base del proyecto usando la variable de entorno o un fallback
define('URL_BASE', env('BASE_URL'));

// Definir el entorno de la aplicación (development, production)
define('APP_ENV', env('APP_ENV', 'development'));
define('VERSION', env('APP_VERSION', '1.0.0'));

// Configurar reporte de errores según el entorno
if (APP_ENV === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Otras configuraciones globales
date_default_timezone_set('America/La_Paz');
