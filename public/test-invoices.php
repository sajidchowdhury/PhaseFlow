<?php
ob_start();
include __DIR__ . '/../App/View/invoices/index.php';
$content = ob_get_clean();

$currentPage = 'invoices';
include __DIR__ . '/../App/View/layouts/main.php';