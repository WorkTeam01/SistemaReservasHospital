<?php
if (session_status() === PHP_SESSION_NONE) session_start();

// Cargar Autoloader
require_once __DIR__ . '/../app/Core/Autoloader.php';

// Cargar variables de entorno
require_once __DIR__ . '/../app/Config/env.php';

// Cargar configuración global
require_once __DIR__ . '/../app/Config/config.php';

use App\Core\Router;

// Inicializar Router
$router = new Router();

// Cargar rutas
require_once __DIR__ . '/../routes/web.php';

// Despachar la petición
$router->dispatch();
