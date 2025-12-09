<?php

namespace App\Core;

/**
 * Clase Middleware básica
 * Maneja lógica de protección de rutas.
 */
class Middleware
{
    /**
     * Verifica si el usuario está logueado.
     * Si no, redirige al login.
     */
    public static function auth()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . URL_BASE . '/login');
            exit;
        }
    }

    /**
     * Verifica si el usuario es Admin.
     */
    public static function admin()
    {
        self::auth(); // Primero chequear login
        if ($_SESSION['user_role'] !== 'admin') {
            die("Acceso Denegado: Se requieren permisos de Administrador.");
        }
    }

    /**
     * Verifica si el usuario es Visitante (NO logueado).
     * Útil para login (si ya estás logueado, te manda al dash).
     */
    public static function guest()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['user_id'])) {
            header('Location: ' . URL_BASE . '/dashboard');
            exit;
        }
    }
}
