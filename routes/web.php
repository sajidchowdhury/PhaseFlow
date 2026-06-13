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

// clients

// ... existing routes ...

$router->get('/clients', 'ClientsController@index');
$router->post('/clients', 'ClientsController@store');
$router->get('/clients/create', 'ClientsController@create');
$router->get('/clients/{id}', 'ClientsController@show');
$router->get('/clients/{id}/edit', 'ClientsController@edit');
$router->post('/clients/{id}', 'ClientsController@update');
$router->post('/clients/{id}/delete', 'ClientsController@destroy');   // ← Fixed here



$router->get('/pipeline', 'PipelineController@Pipeline')->middleware('auth');

$router->get('/projects', 'ProjectsController@Projects')->middleware('auth');
$router->get('/quotations', 'QuotationsController@Quotations')->middleware('auth');
$router->get('/invoices', 'InvoicesController@Invoices')->middleware('auth');
$router->get('/tickets', 'TicketsController@Tickets')->middleware('auth');
$router->get('/accounting', 'AccountingController@Accounting')->middleware('auth');
$router->get('/reports', 'ReportsController@Reports')->middleware('auth');


echo $router->dispatch();