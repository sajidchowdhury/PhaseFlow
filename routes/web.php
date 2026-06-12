<?php
// routes/web.php

$router = new \App\Router();

// Public routes
$router->get('/', 'HomeController@index');
$router->get('/login', 'AuthController@showLoginForm');
$router->post('/login', 'AuthController@login');           // ← This must match form action
$router->get('/register', 'AuthController@showRegisterForm');
$router->post('/register', 'AuthController@register');
$router->get('/verify-email', 'AuthController@verifyEmail');
$router->post('/resend-verification', 'AuthController@resendVerification');

// Protected routes (use /home to avoid phpMyAdmin conflict)
$router->get('/home', 'HomeController@dashboard')->middleware('auth');
$router->post('/logout', 'AuthController@logout')->middleware('auth');

echo $router->dispatch();
