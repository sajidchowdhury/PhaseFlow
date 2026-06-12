<?php
// routes/web.php

$router = new \App\Router();

$router->post('/PhaseFlow/public/register', 'AuthController@register');


// Public routes
$router->get('/', 'HomeController@index');
$router->get('/login', 'AuthController@showLoginForm');
$router->post('/login', 'AuthController@login');
$router->get('/register', 'AuthController@showRegisterForm');
$router->post('/register', 'AuthController@register');     // ← Must exist
$router->get('/verify-email', 'AuthController@verifyEmail');

// Protected routes
$router->get('/app', 'HomeController@dashboard')->middleware('auth');
$router->post('/logout', 'AuthController@logout')->middleware('auth');

echo $router->dispatch();