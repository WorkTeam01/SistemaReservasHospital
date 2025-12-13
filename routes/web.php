<?php

use App\Controllers\AuthController;
use App\Controllers\DashboardController;

// --- Rutas de Autenticación ---

// Mostrar formulario de login
$router->get('/login', [AuthController::class, 'showLogin']);

// Procesar login
$router->post('/login', [AuthController::class, 'login']);
//Cerrar sesión
$router->get('/logout', [AuthController::class, 'logout']);

// --- Rutas Protegidas ---

// Dashboard principal (redirige según rol)
$router->get('/', [DashboardController::class, 'index']);
$router->get('/dashboard', [DashboardController::class, 'index']);
