<?php

namespace App\Core;

/**
 * Clase Auth - Manejo centralizado de autenticación
 * Proporciona métodos estáticos para CSRF, sesiones y verificación de usuarios
 */
class Auth
{
    /**
     * Genera un token CSRF único para la sesión actual
     * @return string Token CSRF
     */
    public static function generateCsrfToken()
    {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * Valida un token CSRF contra el almacenado en sesión
     * @param string $token Token a validar
     * @return bool True si es válido
     */
    public static function validateCsrfToken($token)
    {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }

    /**
     * Inicia sesión para un usuario
     * Regenera el ID de sesión por seguridad
     * @param array $user Datos del usuario desde la BD
     */
    public static function login($user)
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
    public static function check()
    {
        return isset($_SESSION['user_id']);
    }

    /**
     * Obtiene los datos del usuario actual de la sesión
     * @return array Datos del usuario o nulls si no está logueado
     */
    public static function user()
    {
        return [
            'id' => $_SESSION['user_id'] ?? null,
            'name' => $_SESSION['user_name'] ?? null,
            'role' => $_SESSION['user_role'] ?? null
        ];
    }

    /**
     * Obtiene el nombre del usuario actual
     * @return string Nombre del usuario o 'Usuario' por defecto
     */
    public static function userName()
    {
        return $_SESSION['user_name'] ?? 'Usuario';
    }

    /**
     * Obtiene el rol del usuario actual
     * @return string|null Rol del usuario
     */
    public static function userRole()
    {
        return $_SESSION['user_role'] ?? null;
    }

    /**
     * Obtiene el ID del usuario actual
     * @return int|null ID del usuario
     */
    public static function userId()
    {
        return $_SESSION['user_id'] ?? null;
    }
}
