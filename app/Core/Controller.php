<?php

namespace App\Core;

/**
 * PhaseFlow CRM - Base Controller
 */
class Controller
{
    /**
     * Render a view file
     */
    protected function view(string $viewPath, array $data = [])
    {
        extract($data);
        
        // Convert dot notation to path (e.g. dashboard.index → dashboard/index)
        $viewPath = str_replace('.', '/', $viewPath);
        
        $fullPath = __DIR__ . '/../../resources/View/' . $viewPath . '.php';
        
        if (file_exists($fullPath)) {
            require $fullPath;
        } else {
            die("View not found: " . $viewPath);
        }
    }

    /**
     * Redirect helper
     */
    protected function redirect(string $url)
    {
        header("Location: $url");
        exit;
    }

    /**
     * Set flash message
     */
    protected function setFlash(string $type, string $message)
    {
        $_SESSION[$type] = $message;
    }
}