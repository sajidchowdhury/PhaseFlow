<?php
// routes/web.php

$router = new \App\Router();
// Public routes
$router->get('/', 'HomeController@index');
$router->get('/login', 'AuthController@showLoginForm');
$router->post('/login', 'AuthController@login');
$router->get('/register', 'AuthController@showRegisterForm');
$router->post('/register', 'AuthController@register');
$router->get('/verify-email', 'AuthController@verifyEmail');
$router->post('/resend-verification', 'AuthController@resendVerification');


// Protected routes
$router->get('/app', 'HomeController@dashboard')->middleware('auth');   // Changed from /dashboard
$router->post('/logout', 'AuthController@logout')->middleware('auth');



// TODO: Add later
// $router->get('/forgot-password', 'AuthController@showForgotForm');
// $router->post('/forgot-password', 'AuthController@sendResetLink');
// $router->get('/reset-password', 'AuthController@showResetForm');
// $router->post('/reset-password', 'AuthController@resetPassword');

echo $router->dispatch();