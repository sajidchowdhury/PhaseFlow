<?php
ob_start();
include __DIR__ . '/../App/View/cashbook/index.php';
$content = ob_get_clean();

$currentPage = 'accounting';
include __DIR__ . '/../App/View/layouts/main.php';