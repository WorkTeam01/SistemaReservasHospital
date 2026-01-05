<?php

namespace App\Core;

class Router
{
    private $routes = [];

    /**
     * Registra una ruta GET.
     * 
     * @param string $path La URL (ej: '/dashboard')
     * @param array $callback Arreglo [Controlador, Metodo]
     */
    public function get($path, $callback)
    {
        $this->routes['GET'][$path] = $callback;
    }

    /**
     * Registra una ruta POST.
     * 
     * @param string $path La URL (ej: '/auth/login')
     * @param array $callback Arreglo [Controlador, Metodo]
     */
    public function post($path, $callback)
    {
        $this->routes['POST'][$path] = $callback;
    }

    /**
     * Despacha la petición actual.
     */
    public function dispatch()
    {
        $method = $_SERVER['REQUEST_METHOD'];

        // Obtener ruta base y limpiar
        $script_name = $_SERVER['SCRIPT_NAME'];
        $request_uri = $_SERVER['REQUEST_URI'];
        $base_path = str_replace('/index.php', '', $script_name);
        $path = str_replace($base_path, '', $request_uri);
        $path = strtok($path, '?'); // Quitar query params ?id=1

        // Normalizar path (si está vacío es /)
        if ($path === '') $path = '/';

        // Buscar coincidencia
        $callback = $this->routes[$method][$path] ?? false;

        if ($callback) {
            // Sí es un Closure (función anónima), ejecutarlo directamente
            if ($callback instanceof \Closure) {
                $callback();
            }
            // Si es un array [Controller, method]
            elseif (is_array($callback)) {
                $controllerClass = $callback[0];
                $controllerMethod = $callback[1];

                // Verificar si la clase existe (gracias al Autoloader)
                if (class_exists($controllerClass)) {
                    $controller = new $controllerClass();
                    if (method_exists($controller, $controllerMethod)) {
                        // Ejecutar el método
                        $controller->$controllerMethod();
                    } else {
                        die("Error 500: Método '$controllerMethod' no encontrado en '$controllerClass'.");
                    }
                } else {
                    die("Error 500: Controlador '$controllerClass' no encontrado.");
                }
            }
        } else {
            // 404 - Página no encontrada
            ErrorHandler::notFound();
        }
    }
}
