<?php

namespace App;

/**
 * PhaseFlow CRM - Router Class
 * PSR-4 Compatible with Composer Autoloading
 */
class Router
{
    protected $routes = [];
    protected $basePath = '';

    public function __construct()
    {
        // Detect base path for subfolder support (e.g. /PhaseFlow/public)
        $scriptName = dirname($_SERVER['SCRIPT_NAME']);
        $this->basePath = rtrim($scriptName, '/');
    }

    public function get($uri, $action)
    {
        $this->addRoute('GET', $uri, $action);
    }

    public function post($uri, $action)
    {
        $this->addRoute('POST', $uri, $action);
    }

    protected function addRoute($method, $uri, $action)
    {
        $this->routes[] = [
            'method' => strtoupper($method),
            'uri'    => $this->normalizeUri($uri),
            'action' => $action
        ];
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

        foreach ($this->routes as $route) {
            if ($route['method'] === $requestMethod && $route['uri'] === $requestUri) {
                return $this->callAction($route['action']);
            }
        }

        $this->handleNotFound();
    }

    protected function callAction($action)
    {
        if (is_string($action) && strpos($action, '@') !== false) {
            [$controller, $method] = explode('@', $action);
            $controllerClass = "App\\Controllers\\{$controller}";

            if (class_exists($controllerClass)) {
                $controllerInstance = new $controllerClass();
                if (method_exists($controllerInstance, $method)) {
                    return $controllerInstance->$method();
                }
            }
        }

        if (is_callable($action)) {
            return call_user_func($action);
        }

        die("Action not found: " . print_r($action, true));
    }

    protected function handleNotFound()
    {
        http_response_code(404);
        echo "<h1>404 - Page Not Found</h1>";
        exit;
    }
}