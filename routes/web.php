<?php

$router = new \App\Router();


$router->get('/', 'AuthController@register');

// Registration
$router->get('/register', 'AuthController@register');
$router->post('/register', 'AuthController@store');

// Login
$router->get('/login', 'AuthController@login');
$router->post('/login', 'AuthController@authenticate');

// Email Verification (6-digit code)
$router->get('/verify-code', 'AuthController@showVerifyCodePage');
$router->post('/verify-code', 'AuthController@verifyCode');

// Logout
$router->get('/logout', 'AuthController@logout');

// Dashboard
$router->get('/dashboard', function() {
    \App\Middleware\AuthMiddleware::check();

    ob_start();
    require __DIR__ . '/../resources/View/dashboard/index.php';
    $content = ob_get_clean();

    require __DIR__ . '/../resources/View/layouts/main.php';
});

return $router;