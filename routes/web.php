<?php

use App\Core\Router;
use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Middleware\AuthMiddleware;

$router = new Router();

// Public routes
$router->get('/', 'HomeController@index');
$router->get('/login', 'AuthController@login');
$router->post('/login', 'AuthController@loginPost');
$router->get('/register', 'AuthController@register');
$router->post('/register', 'AuthController@registerPost');

// Google OAuth
$router->get('/auth/google', 'AuthController@googleLogin');
$router->get('/auth/google/callback', 'AuthController@googleCallback');

// Logout
$router->get('/logout', 'AuthController@logout');

// Protected routes with Auth Middleware
$router->group(['middleware' => AuthMiddleware::class], function($router) {
    $router->get('/dashboard', 'HomeController@dashboard');
    // Add other protected routes here (clients, pipeline, etc.)
});

$router->dispatch();