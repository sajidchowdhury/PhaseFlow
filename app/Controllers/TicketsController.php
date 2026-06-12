<?php
namespace App\Controllers;

use App\Core\Controller;

class TicketsController extends Controller
{
    public function index()
    {
        header('Location: ' . APP_BASE . '/login');
        exit;
    }

    public function Tickets()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . APP_BASE . '/login');
            exit;
        }

        $pageTitle = 'Tickets';

        // Capture dashboard-specific content (so layout can wrap it)
        ob_start();
        require __DIR__ . '/../../resources/View/tickets/index.php';
        $content = ob_get_clean();

        // Render full page with sidebar, topbar, CSS, JS etc.
        require __DIR__ . '/../../resources/View/layouts/main.php';
    }
}