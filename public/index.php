<?php
session_start();

// Cargar variables de entorno
require_once __DIR__ . '/../config/env.php';
// Cargar configuración global
require_once __DIR__ . '/../config/config.php';
// Cargar base de datos (Disponible pero no requerida en esta etapa)
require_once __DIR__ . '/../config/db.php';

// Router Simple para Pruebas de Estructura
$request_uri = $_SERVER['REQUEST_URI'];
$script_name = $_SERVER['SCRIPT_NAME'];

// Ruta base
$base_path = str_replace('/index.php', '', $script_name);
$path = str_replace($base_path, '', $request_uri);
$path = strtok($path, '?');

// Rutas de "Maqueta"
switch ($path) {
    case '/':
    case '/dashboard':
        // Simular sesión para la vista (ya que quitamos el controlador)
        if (!isset($_SESSION['user_name'])) {
            $_SESSION['user_name'] = 'Usuario Dev';
        }

        // Cargar la vista real (Por defecto Admin para la demo)
        require_once __DIR__ . '/../views/dashboard/admin.php';
        break;

    case '/login-demo':
        // Vista de prueba para Login (sin lógica)
        echo "<h1>Aquí iría el Login (ver AuthController)</h1>";
        break;

    case '/logout':
        echo "<h1>Cerrando sesión... (Implementar destrucción de sesión aquí)</h1>";
        break;

    default:
        http_response_code(404);
        echo "404 - Página no encontrada (Te falta crear la ruta en public/index.php)";
        break;
}
