<?php
ob_start();
include __DIR__ . '/../App/View/settings/index.php';
$content = ob_get_clean();

$currentPage = 'settings';
include __DIR__ . '/../App/View/layouts/main.php';