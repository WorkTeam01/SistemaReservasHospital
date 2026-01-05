<?php

namespace App\Core;

use Random\RandomException;

/**
 * Clase Auth - Manejo centralizado de autenticación
 * Proporciona métodos estáticos para CSRF, sesiones y verificación de usuarios
 */
class Auth
{
    /**
     * Genera un token CSRF único para la sesión actual
     * @return string Token CSRF
     * @throws RandomException
     */
    public static function generateCsrfToken(): string
    {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * Válida un token CSRF contra el almacenado en sesión
     * @param string $token Token a validar
     * @return bool True si es válido
     */
    public static function validateCsrfToken(string $token): bool
    {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }

    /**
     * Inicia sesión para un usuario
     * Regenera el ID de sesión por seguridad
     * @param array $user Datos del usuario desde la BD
     */
    public static function login(array $user): void
    {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['user_name'] = $user['name'];
    }

    /**
     * Verifica si hay un usuario autenticado
     * @return bool True si está logueado
     */
    public static function check(): bool
    {
        return isset($_SESSION['user_id']);
    }

    /**
     * Obtiene los datos del usuario actual de la sesión
     * @return array Datos del usuario o nulls si no está logueado
     */
    public static function user(): array
    {
        return [
            'id' => $_SESSION['user_id'] ?? null,
            'name' => $_SESSION['user_name'] ?? null,
            'role' => $_SESSION['user_role'] ?? null
        ];
    }
    
    /**
     * Cierra la sesión del usuario actual de forma segura
     * Limpia variables de sesión, invalida cookies y destruye la sesión
     * @return void
     */
    public static function logout(): void
    {
        // Verificar que la sesión esté activa
        if (session_status() === PHP_SESSION_ACTIVE) {
            // Limpiar todas las variables de sesión
            $_SESSION = [];

            // Destruir la cookie de sesión si existe
            if (isset($_COOKIE[session_name()])) {
                $params = session_get_cookie_params();
                setcookie(
                    session_name(),
                    '',
                    time() - 42000,
                    $params['path'],
                    $params['domain'],
                    $params['secure'],
                    $params['httponly']
                );
            }

            // Destruir la sesión del servidor
            session_destroy();
        }
    }
}
