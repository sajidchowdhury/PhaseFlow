<?php

$router = new \App\Router();

// ============================================
// HOME / LANDING
// ============================================
$router->get('/', function() {
    echo "<h1>Welcome to PhaseFlow CRM</h1>";
});

// ============================================
// AUTHENTICATION ROUTES
// ============================================

// Registration
$router->get('/register', 'AuthController@register');
$router->post('/register', 'AuthController@store');

// Login
$router->get('/login', 'AuthController@login');
$router->post('/login', 'AuthController@authenticate');

// Email Verification
$router->get('/verify-email', 'AuthController@verifyEmail');

// Logout
$router->get('/logout', 'AuthController@logout');

// ============================================
// DASHBOARD (Protected Route)
// ============================================
$router->get('/dashboard', function() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Redirect to login if not authenticated
    if (!isset($_SESSION['user_id'])) {
        header("Location:" . BASE_URL . "/login");
        exit;
    }

    // Load Dashboard with Main Layout
    ob_start();
    require __DIR__ . '/../resources/View/dashboard/index.php';
    $content = ob_get_clean();

    require __DIR__ . '/../resources/View/layouts/main.php';
});

// ============================================
// FUTURE ROUTES (Example)
// ============================================
// $router->get('/clients', 'ClientController@index');
// $router->get('/pipeline', 'PipelineController@index');

return $router;