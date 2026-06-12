<?php

namespace App;

/**
 * PhaseFlow CRM - Router with Middleware Support
 */
class Router
{
    protected $routes = [];
    protected $basePath = '';

    public function __construct()
    {
        $scriptName = dirname($_SERVER['SCRIPT_NAME']);
        $this->basePath = rtrim($scriptName, '/');
    }

    public function get($uri, $action)
    {
        return $this->addRoute('GET', $uri, $action);
    }

    public function post($uri, $action)
    {
        return $this->addRoute('POST', $uri, $action);
    }

    protected function addRoute($method, $uri, $action)
    {
        $route = [
            'method'     => strtoupper($method),
            'uri'        => $this->normalizeUri($uri),
            'action'     => $action,
            'middleware' => []
        ];

        $this->routes[] = $route;
        $routeIndex = count($this->routes) - 1;

        return new RouteRegistrar($this, $routeIndex);
    }

    protected function normalizeUri($uri)
    {
        return rtrim($uri, '/') ?: '/';
    }

    public function dispatch()
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestUri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $requestUri    = str_replace($this->basePath, '', $requestUri);
        $requestUri    = $this->normalizeUri($requestUri);

        foreach ($this->routes as $index => $route) {
            if ($route['method'] === $requestMethod && $route['uri'] === $requestUri) {
                // Execute middlewares
                foreach ($route['middleware'] as $mw) {
                    $this->executeMiddleware($mw);
                }
                return $this->callAction($route['action']);
            }
        }

        $this->handleNotFound();
    }

    protected function executeMiddleware($middlewareName)
    {
        $class = "App\\Middleware\\" . ucfirst($middlewareName) . "Middleware";
        if (class_exists($class)) {
            $instance = new $class();
            $instance->handle();
        } else {
            die("Middleware '$middlewareName' not found.");
        }
    }

    protected function callAction($action)
    {
        if (is_string($action) && strpos($action, '@') !== false) {
            [$controller, $method] = explode('@', $action);
            $controllerClass = "App\\Controllers\\{$controller}";

            if (class_exists($controllerClass)) {
                $instance = new $controllerClass();
                if (method_exists($instance, $method)) {
                    return $instance->$method();
                }
            }
        }

        die("Action not found: " . print_r($action, true));
    }

    protected function handleNotFound()
    {
        http_response_code(404);
        echo "<h1>404 - Page Not Found</h1>";
        exit;
    }

    public function addMiddlewareToRoute(int $routeIndex, $middleware)
    {
        $this->routes[$routeIndex]['middleware'][] = $middleware;
    }
}