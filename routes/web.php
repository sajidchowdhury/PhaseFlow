<?php

// Existing routes + Logout
$router->get('/logout', 'AuthController@logout');
// Apply middleware to protected routes
$router->middleware('AuthMiddleware', function() {
    // Dashboard, Clients, etc.
    $router->get('/dashboard', 'HomeController@index');
});
