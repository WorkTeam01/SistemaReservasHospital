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

// --- Rutas de Pacientes ---
$router->get('/pacientes', [\App\Controllers\PatientController::class, 'index']);
$router->get('/pacientes/crear', [\App\Controllers\PatientController::class, 'showCreate']);
$router->post('/pacientes/crear', [\App\Controllers\PatientController::class, 'create']);
$router->get('/pacientes/check-dni', [\App\Controllers\PatientController::class, 'checkDni']);
