<?php

namespace App\Middleware;

class AuthMiddleware
{
    /**
     * Check if user is logged in
     */
 
    public static function check()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id']) || !isset($_SESSION['tenant_id'])) {
            $_SESSION['error'] = 'Please login to continue.';
            header('Location: /login');
            exit;
        }

        // Session timeout (30 minutes)
        if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
            session_unset();
            session_destroy();
            header('Location: /login?timeout=1');
            exit;
        }

        $_SESSION['last_activity'] = time();

        // Security: Regenerate session ID
        if (!isset($_SESSION['created_at']) || (time() - $_SESSION['created_at'] > 3600)) {
            session_regenerate_id(true);
            $_SESSION['created_at'] = time();
        }
    }


public function handle()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Check if user is logged in
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['tenant_id'])) {
            $_SESSION['error'] = 'Please login to continue.';
            header('Location: /login');
            exit;
        }

        // Session timeout (30 minutes)
        if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
            session_unset();
            session_destroy();
            header('Location: /login?timeout=1');
            exit;
        }
        $_SESSION['last_activity'] = time();

        // Security: Regenerate session ID every hour
        if (!isset($_SESSION['created_at']) || (time() - $_SESSION['created_at'] > 3600)) {
            session_regenerate_id(true);
            $_SESSION['created_at'] = time();
        }
    }

    
    /**
     * Check if user has specific role(s)
     */
    public static function hasRole($roles)
    {
        self::check();

        if (!is_array($roles)) {
            $roles = [$roles];
        }

        if (!in_array($_SESSION['user_role'], $roles)) {
            http_response_code(403);
            echo "Access Denied. You don't have permission to access this page.";
            exit;
        }

        return true;
    }

    /**
     * Get current logged-in user's tenant ID
     */
    public static function tenantId()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        return $_SESSION['tenant_id'] ?? null;
    }

    /**
     * Get current logged-in user ID
     */
    public static function userId()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        return $_SESSION['user_id'] ?? null;
    }
}