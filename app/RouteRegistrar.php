<?php

namespace App;

/**
 * PhaseFlow CRM - RouteRegistrar for Middleware Chaining
 */
class RouteRegistrar
{
    protected $router;
    protected $routeIndex;

    public function __construct(Router $router, int $routeIndex)
    {
        $this->router = $router;
        $this->routeIndex = $routeIndex;
    }

    public function middleware($middleware)
    {
        $this->router->addMiddlewareToRoute($this->routeIndex, $middleware);
        return $this;
    }
}