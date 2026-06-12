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
            header("Location: /PhaseFlow/public/login");
            exit;
        }

        return true;
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