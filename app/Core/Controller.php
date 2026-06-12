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
     * Set flash message
     */
    protected function setFlash(string $type, string $message)
    {
        $_SESSION[$type] = $message;
    }


    protected function sanitizeInput($input)
{
    return array_map(function($value) {
        return is_string($value) ? trim(strip_tags($value)) : $value;
    }, $input);
}


protected function uploadImage($file, $folder = 'uploads')
{
    $targetDir = "public/assets/images/{$folder}/";
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }
    $fileName = time() . '_' . basename($file['name']);
    $targetFile = $targetDir . $fileName;
    
    if (move_uploaded_file($file['tmp_name'], $targetFile)) {
        return "assets/images/{$folder}/" . $fileName;
    }
    return false;
}

protected function redirect($url)
{
    header("Location: {$url}");
    exit;
}


    }