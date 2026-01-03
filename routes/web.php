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

// --- Rutas de Especialidades ---
$router->get('/especialidades', [\App\Controllers\SpecialtyController::class, 'index']);
$router->post('/especialidades/crear', [\App\Controllers\SpecialtyController::class, 'store']);
$router->post('/especialidades/editar', [\App\Controllers\SpecialtyController::class, 'update']);
$router->post('/especialidades/toggle', [\App\Controllers\SpecialtyController::class, 'toggle']);
// AJAX
$router->post('/especialidades/check-name', [\App\Controllers\SpecialtyController::class, 'checkName']);
$router->get('/especialidades/show', [\App\Controllers\SpecialtyController::class, 'show']);

// --- Rutas de Pacientes ---
$router->get('/pacientes', [\App\Controllers\PatientController::class, 'index']);
$router->get('/pacientes/crear', [\App\Controllers\PatientController::class, 'showCreate']);
$router->post('/pacientes/crear', [\App\Controllers\PatientController::class, 'create']);
$router->get('/pacientes/check-dni', [\App\Controllers\PatientController::class, 'checkDni']);
