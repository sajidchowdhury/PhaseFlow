<?php
ob_start();
include __DIR__ . '/../App/View/tickets/index.php';
$content = ob_get_clean();

$currentPage = 'tickets';
include __DIR__ . '/../App/View/layouts/main.php';