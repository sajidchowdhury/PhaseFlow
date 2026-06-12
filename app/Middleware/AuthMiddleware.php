<?php
namespace App\Middleware;

class AuthMiddleware {
    public function handle() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id']) || !isset($_SESSION['tenant_id'])) {
            $_SESSION['error'] = 'Please login to continue.';
            header('Location: /login');
            exit;
        }

        // Session timeout (30 min inactivity)
        if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
            session_unset();
            session_destroy();
            header('Location: /login');
            exit;
        }
        $_SESSION['last_activity'] = time();

        // Regenerate session ID periodically
        if (!isset($_SESSION['created_at']) || time() - $_SESSION['created_at'] > 3600) {
            session_regenerate_id(true);
            $_SESSION['created_at'] = time();
        }
    }
}
