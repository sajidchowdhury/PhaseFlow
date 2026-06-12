<?php
// routes/web.php

$router = new \App\Router();

// Public routes (clean URIs - Router strips /PhaseFlow/public base automatically)
$router->get('/', 'HomeController@index');
$router->get('/login', 'AuthController@showLoginForm');
$router->post('/login', 'AuthController@login');
$router->get('/register', 'AuthController@showRegisterForm');
$router->post('/register', 'AuthController@register');
$router->get('/verify-email', 'AuthController@showVerifyEmail');
$router->post('/verify-code', 'AuthController@verifyCode');
$router->post('/resend-verification', 'AuthController@resendVerification');

// Protected routes
$router->get('/app', 'HomeController@dashboard')->middleware('auth');
$router->get('/logout', 'AuthController@logout')->middleware('auth');
$router->post('/logout', 'AuthController@logout')->middleware('auth');

$router->get('/pipeline', 'PipelineController@Pipeline')->middleware('auth');
$router->get('/clients', 'ClientsController@Clients')->middleware('auth');
$router->get('/projects', 'ProjectsController@Projects')->middleware('auth');
$router->get('/quotations', 'QuotationsController@Quotations')->middleware('auth');
$router->get('/invoices', 'InvoicesController@Invoices')->middleware('auth');
$router->get('/tickets', 'TicketsController@Tickets')->middleware('auth');
$router->get('/accounting', 'AccountingController@Accounting')->middleware('auth');
$router->get('/reports', 'ReportsController@Reports')->middleware('auth');


echo $router->dispatch();