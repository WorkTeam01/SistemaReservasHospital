<?php

namespace App\Core;

/**
 * Clase Base Controller
 * Provee métodos comunes para todos los controladores.
 */
class Controller
{
    /**
     * Renderiza una vista con datos opcionales.
     * 
     * @param string $view Ruta relativa de la vista (ej: 'auth/login')
     * @param array $data Datos asociativos para pasar a la vista
     */
    protected function render($view, $data = [])
    {
        // Extraer datos para que sean variables locales en la vista
        extract($data);

        // Ruta física del archivo de vista
        $viewFile = __DIR__ . "/../../views/{$view}.php";

        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            die("Error: La vista '{$view}' no existe.");
        }
    }

    /**
     * Renderiza una vista con layout (header, sidebar, footer).
     * 
     * @param string $view Ruta relativa de la vista (ej: 'dashboard/admin')
     * @param array $data Datos asociativos para pasar a la vista
     */
    protected function renderWithLayout($view, $data = [])
    {
        // Extraer datos para que sean variables locales en la vista
        extract($data);

        // Definir variables para CSS/JS específicos de la página
        $pageStyles = $data['pageStyles'] ?? [];
        $pageScripts = $data['pageScripts'] ?? [];
        $pageTitle = $data['pageTitle'] ?? 'Sistema de Reservas Hospital';

        // Ruta física del archivo de vista
        $viewFile = __DIR__ . "/../../views/{$view}.php";

        if (!file_exists($viewFile)) {
            die("Error: La vista '{$view}' no existe.");
        }

        // Cargar header
        require_once __DIR__ . "/../../views/layouts/header.php";

        // Cargar sidebar (ya incluye la apertura de content-wrapper)
        require_once __DIR__ . "/../../views/layouts/sidebar.php";

        // Cargar contenido de la vista
        require_once $viewFile;

        // Cargar footer (ya incluye el cierre de content-wrapper)
        require_once __DIR__ . "/../../views/layouts/footer.php";
    }

    /**
     * Redirecciona a otra URL interna.
     * 
     * @param string $path Ruta interna (ej: '/dashboard')
     */
    protected function redirect($path)
    {
        // Asegurar que usamos URL_BASE si la ruta no es absoluta
        if (!str_starts_with($path, 'http')) {
            $path = URL_BASE . $path; // Usar backslash para constante global
        }
        header("Location: $path");
        exit;
    }

    /**
     * Devuelve una respuesta JSON (útil para AJAX).
     * 
     * @param mixed $data Datos a devolver
     * @param int $status Código HTTP
     */
    protected function json($data, $status = 200)
    {
        header("Content-Type: application/json");
        http_response_code($status);
        echo json_encode($data);
        exit;
    }
}
