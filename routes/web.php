<?php

use App\Core\Middleware;

// --- Definición de Rutas ---

// Dashboard (Ruta principal)
$router->get('/', function () {
    $controller = new \App\Controllers\DashboardController();
    $controller->index();
});

// Login Demo
$router->get('/login-demo', function () {
    Middleware::guest(); // Solo invitados
    echo "<h1>Login Page (Use AuthController)</h1>";
});

// Logout
$router->get('/logout', function () {
    session_destroy();
    header('Location: ' . URL_BASE . '/login-demo');
    exit;
});
