<?php
ob_start();
include __DIR__ . '/../App/View/reports/index.php';
$content = ob_get_clean();

$currentPage = 'reports';
include __DIR__ . '/../App/View/layouts/main.php';