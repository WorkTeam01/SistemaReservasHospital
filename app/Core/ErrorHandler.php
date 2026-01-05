<?php

namespace App\Core;

/**
 * Clase ErrorHandler - Manejo centralizado de errores
 * Proporciona métodos helper para mostrar páginas de error
 */
class ErrorHandler
{
    /**
     * Muestra la página de error correspondiente
     * @param int $code Código de error HTTP
     * @return void
     */
    public static function showError(int $code = 500): void
    {
        // Establecer código de respuesta HTTP
        http_response_code($code);

        // Limpiar cualquier salida anterior
        if (ob_get_level() > 0) {
            ob_end_clean();
        }

        // Determinar qué vista de error mostrar
        $errorView = match($code) {
            404 => __DIR__ . '/../../views/errors/404.php',
            503 => __DIR__ . '/../../views/errors/503.php',
            default => __DIR__ . '/../../views/errors/500.php',
        };

        // Verificar que la vista existe, si no usar 500 por defecto
        if (!file_exists($errorView)) {
            $errorView = __DIR__ . '/../../views/errors/500.php';
        }

        // Incluir la vista de error
        if (file_exists($errorView)) {
            require_once $errorView;
        } else {
            // Fallback si no hay vista de error
            echo "<h1>Error $code</h1>";
            echo "<p>Ha ocurrido un error inesperado.</p>";
        }

        exit;
    }

    /**
     * Muestra error 404 - Página no encontrada
     * @return void
     */
    public static function notFound(): void
    {
        self::showError(404);
    }

    /**
     * Muestra error 500 - Error interno del servidor
     * @return void
     */
    public static function serverError(): void
    {
        self::showError(500);
    }

    /**
     * Muestra error 503 - Servicio no disponible
     * @return void
     */
    public static function serviceUnavailable(): void
    {
        self::showError(503);
    }
}

