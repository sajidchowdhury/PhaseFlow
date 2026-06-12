<?php
// bootstrap/app.php

session_start();

// Composer autoloading
require_once __DIR__ . '/../vendor/autoload.php';

// Load environment variables
$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Load routes
require_once __DIR__ . '/../routes/web.php';


// ========================
// Load Environment Variables
// ========================
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

define('BASE_URL', 'http://localhost/PhaseFlow/public');

// ========================
// Load Database Connection
// ========================
require_once __DIR__ . '/../config/database.php';

// ========================
// Load Routes
// ========================
$router = require __DIR__ . '/../routes/web.php';

// ========================
// Dispatch Request
// ========================
$router->dispatch();