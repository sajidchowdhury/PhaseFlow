<?php
// bootstrap/app.php - Clean Version

session_start();

// Composer Autoloading
require_once __DIR__ . '/../vendor/autoload.php';

// Load .env
$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Load Routes (ONLY ONCE)
require_once __DIR__ . '/../routes/web.php';